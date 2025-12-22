<?php

namespace App\Services;

use PDO;

class DatabaseSingleton
{
    private static ?DatabaseSingleton $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $this->connection = new PDO(
            "mysql:host=127.0.0.1;dbname=laravel",
            "root",
            "123456789"
        );

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): DatabaseSingleton
    {
        if (!self::$instance) {
            self::$instance = new DatabaseSingleton();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
