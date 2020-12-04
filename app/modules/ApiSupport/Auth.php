<?php

namespace App\Modules\ApiSupport;

use Exception;
use App\Models\User;
use App\Modules\ApiSupport\Models\AuthToken;

class Auth
{
    /**
     * @var User
     */
    public static $user;

    public static function register($rawData)
    {
        $u = new User($rawData);
        $u->save();
        return $u;
    }
    public static function login($email, $password, $description = "")
    {
        $users = User::list("*", ["email" => $email, "password" => $password]);

        if (!$users->isEmpty()) {
            $u = $users->first();
            $u->set("id", intval($u->get("id")));
            $authToken = AuthToken::generate($users->first(), $description);
            $authToken->save(true);
            return $authToken;
        }
        throw new Exception("failed login");
    }

    public static function refreshToken($refreshToken)
    {
        $tokens = AuthToken::list("*", ["refresh_token" => $refreshToken]);

        if (!$tokens->isEmpty()) {
            $token = $tokens->first();
            $token->refresh();
            $token->save(true);
            return $token;
        }
        throw new Exception("invalid refresh token");
    }

    public static function loginWithToken($token)
    {
        $tokens = AuthToken::list("*", ["token" => $token]);

        if (!$tokens->isEmpty()) {
            $token = $tokens->first();
            if (!$token->isExpirate()) {
                Auth::$user = User::find($token->get("user_id"));
                return Auth::$user;
            }
            throw new Exception("token has expirated");
        }
        throw new Exception("invalid token");
    }

}
