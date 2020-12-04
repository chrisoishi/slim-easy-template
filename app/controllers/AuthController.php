<?php

namespace App\Controllers;

use App\Modules\ApiSupport\Auth;
use App\Modules\ApiSupport\Controller;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{

    public function __construct()
    {
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();
        $body = $this->checkBodyRequest($request, [
            "email" => v::stringType()->email(),
            "password" => v::stringType()->length(6, null),
            "password_confirm" => v::stringType()->equals($body["password"]),
            "name" => v::stringType()->length(1, null)
        ]);
        $body["password"] = md5($body["password"]);
        $u = Auth::register($body);
        return $this->success($response, "register success", ["user" => $u->getData()]);
    }
    public function login(Request $request, Response $response, array $args): Response
    {
        $body = $this->checkBodyRequest($request, [
            "email" => v::stringType()->email(),
            "password" => v::stringType()->length(6, null)
        ]);
        $body["password"] = md5($body["password"]);
        $auth = Auth::login($body["email"],$body["password"],"login test");
        return $this->success($response, "login success", ["auh_token" => $auth->getData()]);
    }
    public function refreshToken(Request $request, Response $response, array $args): Response
    {
        $body = $this->checkBodyRequest($request, [
            "refresh_token" => v::stringType()
        ]);
        $authToken = Auth::refreshToken($body["refresh_token"]);
        return $this->success($response, "refresh success", ["auh_token" => $authToken->getData()]);
    }
    public function forgot(Request $request, Response $response, array $args): Response
    {
        return $response;
    }
}
