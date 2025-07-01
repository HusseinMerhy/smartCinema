<?php
// Handles booking-related API requests (POST, DELETE) for the cinema server

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request for CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../connection/db.php'; // Database connection
require_once __DIR__ . '/../controllers/BookingController.php'; // Booking controller

$controller = new BookingController($conn);

// Route requests based on HTTP method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handlePostRequest();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $controller->handleDeleteRequest();
} else {
    http_response_code(405);
    echo json_encode(['message' => 'This endpoint only supports POST and DELETE requests.']);
}
