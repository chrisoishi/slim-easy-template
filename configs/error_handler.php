<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use \Slim\App;



function errorHandlingSetup(App $app)
{

    $app->addRoutingMiddleware();

    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
    ) use ($app) {
        $payload = ['error' => $exception->getMessage()];
        $payload["stack"] = $exception->getTraceAsString();
        $response = $app->getResponseFactory()->createResponse();
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );
        return $response;
    };

    // Add Error Middleware
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
};
