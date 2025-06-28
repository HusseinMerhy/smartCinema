<?php
// FILE: cinema-server/controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    public function __construct($db_connection) {
        $this->userModel = new User($db_connection);
    }
    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    public function register() {
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->name) || empty($data->email) || empty($data->phone) || empty($data->password)) {
            $this->sendResponse(400, ['error' => 'All fields are required.']);
        }
        if ($this->userModel->findByEmailOrMobile($data->email, $data->phone)) {
            $this->sendResponse(409, ['error' => 'User with this email or phone already exists.']);
        }
        if ($this->userModel->create($data->name, $data->email, $data->phone, $data->password)) {
            $this->sendResponse(201, ['success' => 'User registered successfully!']);
        } else {
            $this->sendResponse(500, ['error' => 'An unexpected server error occurred.']);
        }
    }
    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->credential) || empty($data->password)) {
            $this->sendResponse(400, ['error' => 'Please enter email/phone and password.']);
        }
        $user = $this->userModel->findByEmailOrMobile($data->credential, $data->credential);
        if ($user && password_verify($data->password, $user['password'])) {
            unset($user['password']);
            $this->sendResponse(200, ['success' => 'Login successful!', 'user' => $user]);
        } else {
            $this->sendResponse(401, ['error' => 'Invalid credentials. Please try again.']);
        }
    }
}
?>