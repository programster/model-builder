<?php

require_once(__DIR__ . '/../bootstrap.php');


// Start session here instead of bootstrap.php because things like CLI scripts dont use a session.
session_start(); 


if (ENVIRONMENT === 'dev')
{
    $displayErrorDetails = true;
    $logErrors = true;
    $logErrorDetails = true;
}
else 
{
    $displayErrorDetails = false;
    $logErrors = true;
    $logErrorDetails = true;
}


$app = Slim\Factory\AppFactory::create();


///////// 
// error middleware so we capture parse errors and return JSON error responses for them.
// https://www.slimframework.com/docs/v4/middleware/error-handling.html

$app->addRoutingMiddleware();

// Define Custom Error Handler
$customErrorHandler = function (
    Slim\Psr7\Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {
    if ($logger !== null)
    {
        $logger->error($exception->getMessage());
    }
    

    $payload = array(
        'error' => [
            "message" => $exception->getMessage()
        ]
    );

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );
    
    $response = $response->withStatus(500);
    return $response;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);


///////end of adding error middleware


// Define app routes below
DataPointController::registerRoutes($app);
HomeController::registerRoutes($app);
ModelController::registerRoutes($app);
ParameterGroupController::registerRoutes($app);
ProcessController::registerRoutes($app);


$app->run();