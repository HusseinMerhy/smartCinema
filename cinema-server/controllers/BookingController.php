<?php
// cinema-server/controllers/BookingController.php
error_reporting(E_ALL); ini_set('display_errors', 0); ini_set('log_errors', 1);
require_once __DIR__ . '/../models/Booking.php';

class BookingController {
    private $bookingModel;
    public function __construct(mysqli $db_connection) { $this->bookingModel = new Booking($db_connection); }

    public function handlePostRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['userId']) || empty($data['showtimeId']) || !isset($data['seats']) || !isset($data['totalPrice'])) {
            $this->sendResponse(['message' => 'Invalid booking data provided.'], 400);
        }
        $result = $this->bookingModel->createBooking((int)$data['userId'], (int)$data['showtimeId'], (array)$data['seats'], (float)$data['totalPrice']);
        if ($result['success']) {
            $this->sendResponse($result, 201);
        } else {
            $statusCode = str_contains($result['message'], 'seat') || str_contains($result['message'], 'cap') ? 409 : 500;
            $this->sendResponse($result, $statusCode);
        }
    }

    public function handleDeleteRequest() {
        if (!isset($_GET['booking_id']) || !isset($_GET['user_id'])) {
            $this->sendResponse(['message' => 'Booking ID and User ID are required.'], 400);
        }
        $result = $this->bookingModel->deleteBooking((int)$_GET['booking_id'], (int)$_GET['user_id']);
        if ($result['success']) {
            $this->sendResponse($result, 200);
        } else {
            $this->sendResponse($result, 403);
        }
    }
    
    private function sendResponse($payload, $statusCode = 200) { if (ob_get_length()) ob_clean(); http_response_code($statusCode); header('Content-Type: application/json'); echo json_encode($payload); exit(); }
}
