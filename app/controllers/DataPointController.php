<?php

/* 
 * 
 */


class DataPointController extends AbstractSlimController
{
    public static function registerRoutes(\Slim\App $app) 
    {        
        $app->get('/api/data-points/for-model/{uuid}', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new DataPointController($request, $response, $args);
            $modelId = $args['uuid'];
            return $controller->fetchDataPointsForModel($modelId);
        });
        
        
        $app->get('/data-points/{uuid}', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new DataPointController($request, $response, $args);
            $dataPointId = $args['uuid'];
            return $controller->showEditDataPoint($dataPointId);
        });
        
        $app->get('/data-point-history/{uuid}/view', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new DataPointController($request, $response, $args);
            $historyUuid = $args['uuid'];
            return $controller->showDataPointHistory($historyUuid);
        });
    }
    
    
    /**
     * Fetch the data points that belong to the specified model.
     * @param string $modelId
     * @return type
     */
    private function fetchDataPointsForModel(string $modelId)
    {
        $model = ModelTable::getInstance()->load($modelId);
        $activeDataPoints = ActiveDataPointTable::getInstance()->loadForModel($model);
        
        $responseData = [
            'active_data_points' => $activeDataPoints,
        ];
        
        return SlimLib::createJsonResponse($responseData, $this->m_response, 200);
    }
    
    
    /**
     * Show the edit data point page/form.
     * @param string $dataPointId
     * @return type
     */
    private function showEditDataPoint(string $dataPointId)
    {
        /* @var $activeDataPoint ModelRecord */
        $activeDataPoints = ActiveDataPointTable::getInstance()->loadWhereAnd(['data_point_id' => $dataPointId]);
        $activeDataPoint = \Programster\CoreLibs\ArrayLib::getFirstElement($activeDataPoints);
        $content = new ViewPageEditDataPoint($activeDataPoint);
        $view = new ViewHtmlShell($content);
        $body = $this->m_response->getBody();
        $body->write((string)$view); // returns number of bytes written
        $newResponse = $this->m_response->withBody($body);
        return $newResponse;
    }
}
