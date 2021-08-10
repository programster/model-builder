<?php

/* 
 * 
 */


class ModelController extends AbstractSlimController
{
    public static function registerRoutes(\Slim\App $app) 
    {
        $app->get('/models', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $homeController = new ModelController($request, $response, $args);
            return $homeController->index();
        });
        
        $app->get('/models/{uuid}', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $homeController = new ModelController($request, $response, $args);
            $modelId = $args['uuid'];
            return $homeController->showModel($modelId);
        });
    }
    
    
    /**
     * Display hello world.
     */
    private function index()
    {
        $models = ModelTable::getInstance()->loadAll();
        //$processForm = new ViewProcessForm();
        //$processForm = new ViewBootstrapCard("My Card", "This is the content of my card", "https://i.guim.co.uk/img/media/10c93273187f3881bf1535fba8bfe4827c9483c6/616_115_1273_763/master/1273.jpg?width=620&quality=85&auto=format&fit=max&s=f7c5aa462275c397840cfd0c3a7474dd");
        //$containedForm = new ViewBootstrapContainer($processForm);
        
        
        
        $content = new ViewPageModelList(...$models);
        $view = new ViewHtmlShell($content);
        $body = $this->m_response->getBody();
        $body->write((string)$view); // returns number of bytes written
        $newResponse = $this->m_response->withBody($body);
        return $newResponse;
    }
    
    
    private function showModel(string $modelId)
    {
        /* @var $modelRecord ModelRecord */
        $modelRecord = ModelTable::getInstance()->load($modelId);
        $processes = $modelRecord->fetchActiveProcesses();
        $dataPoints = $modelRecord->fetchActiveDataPoints();
        
        $link = new Link($modelRecord->getName(), null);
        $navbar = new ViewNavbar($link);
        $content = $navbar . new ViewPageModelOverview($processes, $dataPoints);
        $view = new ViewHtmlShell($content);
        $body = $this->m_response->getBody();
        $body->write((string)$view); // returns number of bytes written
        $newResponse = $this->m_response->withBody($body);
        return $newResponse;
    }
}
