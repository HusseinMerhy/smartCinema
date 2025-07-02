<?php
// cinema-server/controllers/AuthController.php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;

    public function __construct(mysqli $db_connection) {
        $this->db = $db_connection;
    }

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password']) || empty($data['phoneNumber'])) {
            $this->sendResponse(['message' => 'All fields are required.'], 400);
        }

        // Use the flexible finder to prevent duplicate emails OR phone numbers
        if (User::findByLoginIdentifier($this->db, $data['email'])) {
            $this->sendResponse(['message' => 'User with this email already exists.'], 409);
        }
        if (User::findByLoginIdentifier($this->db, $data['phoneNumber'])) {
            $this->sendResponse(['message' => 'User with this phone number already exists.'], 409);
        }

        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $user_data = [
            'email' => $data['email'],
            'phoneNumber' => $data['phoneNumber'],
            'password' => $hashed_password,
        ];

        $user = new User($this->db, $user_data);

        if ($user->save()) {
            $this->sendResponse(['message' => 'User registered successfully.'], 201);
        } else {
            $this->sendResponse(['message' => 'Failed to register user.'], 500);
        }
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['identifier']) || empty($data['password'])) {
            $this->sendResponse(['message' => 'Identifier and password are required.'], 400);
        }

        $user = User::findByLoginIdentifier($this->db, $data['identifier']);

        if ($user && $user->verifyPassword($data['password'])) {
            $this->sendResponse([
                'message' => 'Login successful',
                'user' => $user->toArray()
            ]);
        } else {
            $this->sendResponse(['message' => 'Invalid credentials.'], 401);
        }
    }

    private function sendResponse($payload, $statusCode = 200) {
        if (ob_get_length()) ob_clean();
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit();
    }
}
