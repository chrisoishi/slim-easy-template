<?php

namespace App\Modules\ApiSupport\Models;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Modules\ApiSupport\Model;

class AuthToken extends Model
{
    const table = "auth_tokens";
    const pk = "token";
    const casts = ["user_id" => "integer"];


    public function refresh()
    {
        $uid = $this->get("user_id");
        $now = Carbon::now();
        $lastUpdate = new Carbon($this->get("updated_at"));
        $diff = $now->diffInDays($lastUpdate);
        $this->delete();
        if ($diff < 5) {
            $token = md5($uid . $now->format('Y-m-d H:i:s'));
            $this->set("token", $token);
            $this->setRefreshToken();
        } else {

            throw new Exception("expired refresh token");
        }
    }

    private  function setRefreshToken()
    {
        $oldToken = $this->get("token");
        $now = new DateTime();
        $newToken = md5($oldToken . $now->format('Y-m-d H:i:s'));
        $this->set("refresh_token", $newToken);
    }

    public static function generate(User $u, $description = ""): AuthToken
    {
        $at = new AuthToken(["user_id" => $u->get("id"),  "description" => $description]);
        $at->refresh();
        return $at;
    }

    public function isExpirate()
    {
        $now = Carbon::now();
        $lastUpdate = new Carbon($this->get("updated_at"));
        $diff = $now->diffInHours($lastUpdate);
        if ($diff > 2) return true;
        return false;
    }
}
