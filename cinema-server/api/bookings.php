<?php
// cinema-server/api/bookings.php

// 1. ENABLE DEBUGGING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. HEADERS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    // 3. INCLUDE FILES
    if (!file_exists('../connection/db.php')) throw new Exception("db.php not found");
    require_once '../connection/db.php';
    
    if (!file_exists('../controllers/BookingController.php')) throw new Exception("BookingController.php not found");
    require_once '../controllers/BookingController.php';

    // 4. FIX CONNECTION VARIABLE
    // Ensure $conn exists and is valid
    if (isset($mysqli) && !isset($conn)) $conn = $mysqli;
    if (isset($db_connection) && !isset($conn)) $conn = $db_connection;

    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception("Database connection failed: \$conn is not a valid MySQLi object.");
    }

    // 5. INITIALIZE CONTROLLER
    $controller = new BookingController($conn);
    $method = $_SERVER['REQUEST_METHOD'];

    // 6. ROUTE REQUEST
    if ($method === 'GET') {
        $controller->handleGetRequest();
    } elseif ($method === 'POST') {
        $controller->handlePostRequest();
    } elseif ($method === 'DELETE') {
        $controller->handleDeleteRequest();
    } elseif ($method === 'OPTIONS') {
        http_response_code(200);
        exit();
    } else {
        throw new Exception("Method $method not allowed");
    }

} catch (Throwable $e) {
    // 7. CATCH & RETURN CRASH DETAILS
    http_response_code(500);
    echo json_encode([
        "message" => "Critical Server Error",
        "error" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ]);
}
?>