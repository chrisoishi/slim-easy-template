<?php

namespace App\Models;

use App\Modules\ApiSupport\Model;

class User extends Model
{
    const table = "users";
    const pk = "id";
    const protected = ["password"];

}
