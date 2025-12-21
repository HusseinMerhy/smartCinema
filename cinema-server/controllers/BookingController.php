<?php
// cinema-server/controllers/BookingController.php
error_reporting(E_ALL); 
ini_set('display_errors', 0); 

require_once __DIR__ . '/../models/Booking.php';

class BookingController {
    private $bookingModel;

    public function __construct(mysqli $db_connection) { 
        $this->bookingModel = new Booking($db_connection); 
    }

    public function handleGetRequest() {
        try {
            if (!isset($_GET['showtime_id'])) {
                throw new Exception('Showtime ID is required.');
            }

            $showtimeId = (int)$_GET['showtime_id'];
            
            // Fetch seats
            $bookedSeats = $this->bookingModel->getBookedSeatsByShowtime($showtimeId);
            
            // Respond
            $this->sendResponse($bookedSeats, 200);

        } catch (Exception $e) {
            $this->sendResponse(['message' => 'Error fetching seats: ' . $e->getMessage()], 500);
        }
    }

    public function handlePostRequest() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['userId']) || empty($data['showtimeId']) || !isset($data['seats']) || !isset($data['totalPrice'])) {
                $this->sendResponse(['message' => 'Invalid booking data provided.'], 400);
            }

            $userId = (int)$data['userId'];
            $showtimeId = (int)$data['showtimeId'];
            $seats = (array)$data['seats'];
            $totalPrice = (float)$data['totalPrice'];
            $snacks = isset($data['snacks']) ? (array)$data['snacks'] : [];

            $result = $this->bookingModel->createBooking($userId, $showtimeId, $seats, $snacks, $totalPrice);

            if ($result['success']) {
                $this->sendResponse($result, 201);
            } else {
                // PHP 7 Compatible check (strpos instead of str_contains)
                $isConflict = (strpos($result['message'], 'seat') !== false) || (strpos($result['message'], 'cap') !== false);
                $statusCode = $isConflict ? 409 : 500;
                $this->sendResponse($result, $statusCode);
            }
        } catch (Exception $e) {
            $this->sendResponse(['message' => 'Server Error: ' . $e->getMessage()], 500);
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
    
    private function sendResponse($payload, $statusCode = 200) { 
        if (ob_get_length()) ob_clean(); 
        http_response_code($statusCode); 
        header('Content-Type: application/json'); 
        echo json_encode($payload); 
        exit(); 
    }
}
?>