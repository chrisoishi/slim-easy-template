<?php

use \Slim\App;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middlewares\AuthMiddleware;


function routes(App $app)
{

    $app->get('/', HomeController::class . ":home")->add(new AuthMiddleware());

    $app->post('/auth/register', AuthController::class . ":register");
    $app->post('/auth/login', AuthController::class . ":login");
    $app->post('/auth/refresh-token', AuthController::class . ":refreshToken");
    $app->post('/auth/forgot', AuthController::class . ":forgot");
};
