<?php
// cinema-server/api/showtimes.php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../connection/db.php';
require_once __DIR__ . '/../controllers/ShowtimeController.php';

$controller = new ShowtimeController($conn);

// We only expect GET requests for this endpoint for now
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->handleGetRequest();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'This endpoint only supports GET requests.']);
}