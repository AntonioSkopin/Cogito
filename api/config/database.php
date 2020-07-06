<?php

class Database {
    // Properties
    private $host = "localhost";
    private $db_name = "cogito";
    private $username = "root";
    private $password = "";
    public conn;

    // Method to get the database connection
    public function getConnection() {
        $this->conn = null;

        // Get the connection
        try {
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);
        } catch (PDOException $ex) {
            die("Connection error: " . $ex->getMessage());
        }

        // Return connection
        return $this->conn;
    }
}