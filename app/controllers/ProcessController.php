<?php

/* 
 * 
 */


class ProcessController extends AbstractSlimController
{
    public static function registerRoutes(\Slim\App $app) 
    {
        $app->get('/api/processes/{uuid}', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new ProcessController($request, $response, $args);
            $processId = $args['uuid'];
            return $controller->getProcessDetails($processId);
        });
        
        $app->get('/api/processes/{uuid}/parameters', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new ProcessController($request, $response, $args);
            $processId = $args['uuid'];
            return $controller->getProcessParameterDetails($processId);
        });
        
        $app->get('/processes/{uuid}', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new ProcessController($request, $response, $args);
            $processId = $args['uuid'];
            return $controller->showEditProcess($processId);
        });
        
        $app->post('/processes/{uuid}/test', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new ProcessController($request, $response, $args);
            $processId = $args['uuid'];
            return $controller->runTest($processId);
        });
        
        $app->get('/process-history/{uuid}/view', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new ProcessController($request, $response, $args);
            $historyUuid = $args['uuid'];
            return $controller->showProcessHistory($historyUuid);
        });
    }
    
    
    /**
     * Handle request to test function. 
     */
    private function runTest(string $processId)
    {
        try 
        {
            //die(print_r($this->m_request->getHeaders(), true));
            $process = ProcessTable::getInstance()->load($processId);
            $activeProcess = ActiveProcessTable::getInstance()->loadForProcess($process);
            $allPostPutVars = json_decode($this->m_request->getBody()->getContents(), true);
            $parameters = ActiveParameterTable::getInstance()->loadForProcess($activeProcess);
            
            if (!isset($allPostPutVars['code']))
            {
                throw new ExceptionMissingParameter("code");
            }

            $code = $allPostPutVars['code'];
            
            $parameterNames = [];
            $parameterValues = [];
            
            foreach ($parameters as $parameter)
            {
                /* @var $parameter ActiveParameterRecord */
                $parameterNames[] = '$' . $parameter->getName();
                $activeDataPoint = $parameter->fetchActiveDataPoint();
                $parameterValue = ($parameter->getTestValue() !== null) ? json_decode($parameter->getTestValue(), true) : json_decode($activeDataPoint->getSampleValue(), true);
                $parameterValues[$parameter->getName()] = $parameterValue;
            }        
            
            // formatting sucks here, but this helps with debugging if there is a parse error thrown in the code.
            $code = 
"function myTestFunction(" . implode(",", $parameterNames). ") {
{$code}
}";

            $callback = function() use($code, $parameterValues) {
                eval($code); // if this causes a parse error, this can only be handled by the shutdown function: https://bit.ly/3AGpoS0
                return myTestFunction(...$parameterValues);
            };

            set_error_handler(function ($severity, $message, $file, $line) {
                throw new ExceptionEvalError($message, $severity, $file, $line);
            });
            $output = $callback();
            restore_error_handler();

            // @todo - validate against the output data point schema here.
            $outputActiveDataPoint = $activeProcess->fetchOutputActiveDataPoint();
            $schemaString = $outputActiveDataPoint->getValidationSchema();
            $schema = \Swaggest\JsonSchema\Schema::import(json_decode($schemaString));
            $stringToValidate = Safe\json_encode($output);
            
            try
            {
                // its annoying, but we need to do this to convert from any array form to object form for validation.
                $schema->in(json_decode(json_encode($output)));
            } 
            catch (Exception $ex) 
            {
                throw new ExceptionSchemaValidationFailed($schemaString, $stringToValidate, $ex);
            }

            $responseData = [
                'test_result' => [
                    'status' => 'success',
                    'function' => $code,
                    'parameter_values' => $parameterValues,
                    'output' => $output
                ]
            ];

            $response = SlimLib::createJsonResponse($responseData, $this->m_response);
        }
        catch (ExceptionSchemaValidationFailed $schemaValidationException)
        {
            $responseData = [
                'error' => array(
                    'message' => $schemaValidationException->getMessage(),
                    'status' => 'validation_error',
                    'exception' => $schemaValidationException
                )
            ];

            $response = SlimLib::createJsonResponse($responseData, $this->m_response, 400);
        }
        catch (ExceptionEvalError $evalError)
        {
            $responseData = [
                'error' => array(
                    'message' => $evalError->getMessage(),
                    'status' => 'code_error',
                    'exception' => $evalError
                )
            ];

            $response = SlimLib::createJsonResponse($responseData, $this->m_response, 400);
        }
        catch (ExceptionMissingParameter $missingParameterException)
        {
            $responseData = [
                'error' => array(
                    'message' => $missingParameterException->getMessage(),
                    'status' => 'request_error',
                    'exception' => $missingParameterException
                )
            ];
            
            $response = SlimLib::createJsonResponse($responseData, $this->m_response, 400);
        }
        catch (Exception $ex) 
        {
            $responseData = [
                'error' => array(
                    'message' => $ex->getMessage(),
                    'status' => 'exception_error',
                    'exception' => $ex
                )
            ];

            $response = SlimLib::createJsonResponse($responseData, $this->m_response, 500);
        }
        catch (Throwable $e) 
        {
            $probableErroneousLineNumber = ($e->getLine() - 1); // for some reason skewed by 1 with eval.
            
            $responseData = [
                'error' => array(
                    'message' => "There appears to be something wrong on line {$probableErroneousLineNumber} with the code that was sent up. Please check the code.",
                    'status' => 'code_error',
                    'exception' => $e->getMessage(),
                    'line' => ($e->getLine() - 1),
                )
            ];

            $response = SlimLib::createJsonResponse($responseData, $this->m_response, 400);
        }
        
        return $response;
    }
    
    
    /**
     * Show the edit data point page/form.
     * @param string $processId
     * @return type
     */
    private function getProcessDetails(string $processId)
    {
        $process = ProcessTable::getInstance()->load($processId);
        
        $activProcesses = ActiveProcessTable::getInstance()->loadWhereAnd(['process_id' => $processId]);
        $processHistory = ProcessHistoryTable::getInstance()->loadWhereAnd(['process_id' => $processId]);
        
        if (count($activProcesses) > 0) 
        {
            $activeProcess = $activProcesses[0];
        }
        else
        {
            $activeProcess = null;
        }
        
                
        $responseData = array(
            'process' => $process,
            'active_process' => $activeProcess,
            'process_history' => $processHistory
        );
                
        
        return SlimLib::createJsonResponse($responseData, $this->m_response);
    }
    
    
    public function getProcessParameterDetails(string $processId)
    {
        $activProcesses = ActiveProcessTable::getInstance()->loadWhereAnd(['process_id' => $processId]);
        
        if (count($activProcesses) > 0) 
        {
            $activeProcess = $activProcesses[0];
        }
        else
        {
            throw new Exception("Process is not active");
        }
        
        $parameters = ActiveParameterTable::getInstance()->loadForProcess($activeProcess);
        
        $responseData = array(
            'active_parameters' => $parameters,
        );
        
        return SlimLib::createJsonResponse($responseData, $this->m_response);
    }
    
    
    /**
     * Show the edit data point page/form.
     * @param string $dataPointId
     * @return type
     */
    private function showEditProcess(string $dataPointId)
    {
        /* @var $activeProcess ModelRecord */
        $activeProcesses = ActiveProcessTable::getInstance()->loadWhereAnd(['process_id' => $dataPointId]);
        $activeProcess = \Programster\CoreLibs\ArrayLib::getFirstElement($activeProcesses);
        /* @var $activeProcess ActiveProcessRecord */
        /* @var $process ProcessRecord */
        $process = ProcessTable::getInstance()->load($activeProcess->getProcessId());
        $model = ModelTable::getInstance()->load($process->getModelId());
        $modelDataPoints = ActiveDataPointTable::getInstance()->loadForModel($model);
        
        $content = new ViewPageEditProcess($activeProcess, ...$modelDataPoints);
        $view = new ViewHtmlShell($content);
        $body = $this->m_response->getBody();
        $body->write((string)$view); // returns number of bytes written
        $newResponse = $this->m_response->withBody($body);
        return $newResponse;
    }
    
    
    private function showProcessHistory()
    {
        die("@todo");
    }
}
