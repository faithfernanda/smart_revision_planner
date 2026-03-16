<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'smart_revision_planner';
    private $username = 'postgres'; // Default, change as needed
    private $password = 'password'; // Default, change as needed
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
