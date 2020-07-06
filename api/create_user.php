<?php

// required headers
header("Access-Control-Allow-Origin: http://localhost/Cogito/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Files needed to connect to the database
include_once 'config/database.php';
include_once 'objects/user.php';

// Get the database connection
$database = new Database();
$db = $database->getConnection();

// Instantiate user object
$user = new User($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Set user property values
$user->username = $data->username;
$user->email = $data->email;
$user->password = $data->password;

// Create the user
if (
    !empty($user->username) &&
    !empty($user->email) &&
    !empty($user->password) &&
    $user->create()
) {
    // Set response code to 200 (OK)
    http_response_code(200);

    // Display message: user was created
    echo json_encode(array("message" => "User is created."));
}
// Message if unablte to create user
else {
    // Set response code to 400 (Bad request)
    http_response_code(400);

    // Displat message: user was not created
    echo json_encode(array("message" => "User is not created."));
}