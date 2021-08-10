<?php

/* 
 * 
 */


class ParameterGroupController extends AbstractSlimController
{
    public static function registerRoutes(\Slim\App $app) 
    {
        $app->get('/api/parameter-groups/{uuid}', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $controller = new ParameterGroupController($request, $response, $args);
            $processId = $args['uuid'];
            return $controller->getParameterGroupDetails($processId);
        });
    }
    
    
    
    private function getParameterGroupDetails(string $groupId)
    {
        $parameterGroup = ParameterGroupTable::getInstance()->load($groupId);
        $activeParameters = ActiveParameterTable::getInstance()->loadWhereAnd(['parameter_group_id' => $groupId]);
        
        $responseData = array(
            'active_parameters' => $activeParameters
        );
                
        
        return SlimLib::createJsonResponse($responseData, $this->m_response);
    }
}
