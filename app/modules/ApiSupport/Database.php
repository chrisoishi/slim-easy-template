<?php

namespace App\Modules\ApiSupport;

use Medoo\Medoo;

class Database
{
    public static $instance;

    private function __construct()
    {
        //
    }

    public static function getInstance(): Medoo
    {

        if (!isset(self::$instance)) {
            self::$instance = new Medoo([
                // required
                'database_type' => 'mysql',
                'database_name' => $_ENV["DB_NAME"],
                'server' => $_ENV["DB_HOST"],
                'username' => $_ENV["DB_USER"],
                'password' => $_ENV["DB_PASS"],
            ]);
        }

        return self::$instance;
    }
}
