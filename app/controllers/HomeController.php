<?php

namespace App\Controllers;

use App\Models\User;
use App\Modules\ApiSupport\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class HomeController
{

    public function __construct()
    {
    }

    public function home(Request $request, Response $response, array $args): Response
    {

        $response->getBody()->write("Hello.. you are logged with: ".Auth::$user->toJSON());
        return $response;
    }
}
