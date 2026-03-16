<?php
// config/database.php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $config = require __DIR__ . '/config.php';
        $this->host = $config['database']['host'];
        $this->db_name = $config['database']['dbname'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("SET NAMES 'UTF8'");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
