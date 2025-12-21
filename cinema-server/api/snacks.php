<?php
// cinema-server/api/snacks.php

// Handle CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database connection
// This file should create a variable like $conn or $db_connection
require_once '../connection/db.php';

// Include the controller
require_once '../controllers/SnackController.php';

// Ensure the database connection variable is available
// (Assuming your db.php names it $conn)
if (!isset($conn)) {
    // If your db.php uses a different name (like $mysqli or $db_connection), map it here:
    if (isset($mysqli)) $conn = $mysqli;
    elseif (isset($db_connection)) $conn = $db_connection;
    else {
        http_response_code(500);
        echo json_encode(["message" => "Database connection variable not found."]);
        exit();
    }
}

// Route the request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new SnackController($conn);
    $controller->handleGetRequest();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["message" => "Method not allowed"]);
}
?>