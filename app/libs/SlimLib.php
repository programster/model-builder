<?php

/* 
 * A library for interfacing with slim.
 */

class SlimLib
{
    /**
     * Create a redirect response;
     * @param string $newLocation - where you wish to redirect the user.
     * @param Slim\Psr7\Response $response - the existing response to work with
     * @param bool $useTemporaryRedirect - whether this redirect is temporary, or browser should cache it.
     * @return Slim\Psr7\Response - the redirect response.
     */
    public static function redirect(
        string $newLocation, 
        Slim\Psr7\Response $response, 
        bool $useTemporaryRedirect=true
    ) : Slim\Psr7\Response
    {
        $httpCode = ($useTemporaryRedirect) ? 302 : 301;
        $response = $response->withHeader('Location', $newLocation);
        $response = $response->withStatus($httpCode);
        return $response;
    }
    
    

    /**
     * Create a JSON response
     * @param array|JsonSerializable $responseData
     * @param Slim\Psr7\Response $existingResponse
     * @param int $httpCode - optionally set the code to return if not 200
     * @return Slim\Psr7\Response
     */
    public static function createJsonResponse(
        array |JsonSerializable $responseData, 
        Slim\Psr7\Response $existingResponse,
        int $httpCode = 200
    ) : Slim\Psr7\Response
    {
        $responseBody = json_encode($responseData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $body = $existingResponse->getBody();
        $body->write($responseBody);
        $newResponse = $existingResponse->withHeader('Content-Type', 'application/json');
        $newResponse = $newResponse->withStatus($httpCode);
        return $newResponse;
    }
}