<?php

namespace Framework\Database;

use Framework\Config;
use PDO;

/** @mixin PDO */
class DB
{
    private static $instance = null;

    private PDO $connection;

    private function __construct(string $host, string $dbName, string $user, string $pass)
    {
        $this->connection = new PDO(
            'mysql:host=' . $host . ';dbname=' . $dbName . ';charset=utf8mb4',
            $user,
            $pass,
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DB(Config::get('DB_HOST'), Config::get('DB_NAME'), Config::get('DB_USER'), Config::get('DB_PASS'));
        }
        return self::$instance;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->connection, $method], $args);
    }
}
