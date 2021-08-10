<?php

/*
 * A basic controller for a slim application.
 */

class HomeController extends AbstractSlimController
{    
    public static function registerRoutes($app) 
    {
        // Show the home page.
        $app->get('/', function (Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) {
            $homeController = new HomeController($request, $response, $args);
            return $homeController->index();
        });
    }
    
    
    /**
     * Display hello world.
     */
    public function index() : Slim\Psr7\Response
    {
        if (isset($_SESSION['model']) === FALSE)
        {
            // show the models page to direct the user to select a model to view/edit, or create a new one.
            $response = SlimLib::redirect("/models", $this->m_response);
        }
        else
        {
            // show the model overview page.
            $modelId = $_SESSION['model'];
            $response = SlimLib::redirect("/models/{$modelId}", $this->m_response);
        }
        
        return $response;
    }
}