<?php
// cinema-server/controllers/SnackController.php

// Ensure we point to the correct model path
require_once __DIR__ . '/../models/Snack.php';

class SnackController {
    private $snackModel;

    // Constructor receives the database connection (MySQLi)
    public function __construct(mysqli $db_connection) {
        $this->snackModel = new Snack($db_connection);
    }

    // Handle GET requests to fetch all snacks
    public function handleGetRequest() {
        $snacks = $this->snackModel->getAllSnacks();
        
        // Set headers and output JSON
        header('Content-Type: application/json');
        echo json_encode($snacks);
        exit();
    }
}
?>