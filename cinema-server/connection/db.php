<?php
// connection/db.php

$host     = 'localhost';
$username = 'root';
$password = '';
$database = 'smartcinema';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    // Stop execution on fatal error
    http_response_code(500);
    die(json_encode([
      'status'  => 'error',
      'message' => 'DB connection failed: '.$conn->connect_error
    ]));
}

