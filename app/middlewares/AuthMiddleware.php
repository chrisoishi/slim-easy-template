<?php

namespace App\Middlewares;

use Exception;
use App\Modules\ApiSupport\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware implements Middleware
{
    public function __construct()
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $authorization = $request->getHeader("authorization");
        if ($authorization == null) throw new Exception("Authorization header required");
        $ex = explode("Bearer ", $authorization[0]);
        if (count($ex) < 2) throw new Exception("Authorization header invalid");
        Auth::loginWithToken($ex[1]);
        return $handler->handle($request);
    }
}
