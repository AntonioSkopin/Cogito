<?php

class User {
    // Properties
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $created;
    public $modified;

    // Constructor with database connection as parameter
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to create a new user
    public function create() {
        // Query to insert a new user
        $query = "INSERT INTO ".$this->table_name. " 
        SET username=:username, email=:email, password=:password";
        
        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // Bind the values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);

        // Hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        // Execute the query & check if query was successful
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}