<?php
namespace LH;

use PDO;
use PDOException;
use LH\Config\DatabaseConfig;

class Database 
{
    private static $instance = null;
    private $connection;

    private function __construct() 
    {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DatabaseConfig::HOST . ";dbname=" . DatabaseConfig::DB_NAME,
                DatabaseConfig::USERNAME,
                DatabaseConfig::PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() 
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() 
    {
        return $this->connection;
    }

    private function __clone() {}
    public function __wakeup() {}
}