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

    // Method to check if email exists in the database
    public function emailExists() {
        // query to check if email exists
        $query = "SELECT id, username, password
        FROM " . $this->table_name . "
        WHERE email = ?
        LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0) {

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }
}