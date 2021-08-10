<?php

/*
 * A basic controller for a slim application.
 */

abstract class AbstractSlimController
{
    protected Slim\Psr7\Request $m_request;
    protected Slim\Psr7\Response $m_response;
    protected $m_args;
    
    
    public function __construct(Slim\Psr7\Request $request, Slim\Psr7\Response $response, $args) 
    {
        $this->m_request = $request;
        $this->m_response = $response;
        $this->m_args = $args;
    }
    
    
    abstract public static function registerRoutes(Slim\App $app);
}