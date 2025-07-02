<?php
// cinema-server/api/movies.php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../connection/db.php'; 
require_once __DIR__ . '/../controllers/MovieController.php';

$controller = new MovieController($conn);

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

if ($id !== null) {
    $controller->getMovieById((int)$id);
} else {
    switch ($action) {
        case 'now-playing':
            $controller->nowPlaying();
            break;
        case 'coming-soon':
            $controller->comingSoon();
            break;
        default:
            http_response_code(400);
            echo json_encode(['message' => 'Invalid movie action.']);
            exit();
    }
}