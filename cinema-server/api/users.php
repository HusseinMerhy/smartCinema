<?php
// cinema-server/api/users.php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__.'/../connection/db.php';
require_once __DIR__.'/../controllers/AuthController.php';

// Create the controller and pass the connection to it
$ctrl = new AuthController($conn);

switch($_GET['action'] ?? '') {
  case 'login':
    $ctrl->login();
    break;
  case 'register':
    $ctrl->register();
    break;
  default:
    http_response_code(404);
    echo json_encode(['message'=>'Unknown user action']);
}