<?php
// cinema-server/controllers/ShowtimeController.php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../models/Showtime.php';

class ShowtimeController {
    private $showtimeModel;

    public function __construct(mysqli $db_connection) {
        $this->showtimeModel = new Showtime($db_connection);
    }

    public function handleGetRequest() {
        // --- THE FIX IS HERE ---
        // The logic is now more flexible.
        if (isset($_GET['movie_id'])) {
            $this->getForMovie((int)$_GET['movie_id']);
        } elseif (isset($_GET['showtime_id'])) {
            $this->getDetails((int)$_GET['showtime_id']);
        } else {
            // If no specific ID is provided, fetch all showtimes for the admin panel.
            $this->getAllShowtimes();
        }
    }
    
    private function getAllShowtimes() {
        $showtimes = $this->showtimeModel->getAllShowtimes();
        $this->sendResponse(['data' => $showtimes]);
    }

    private function getForMovie(int $movieId) {
        $showtimes = $this->showtimeModel->getShowtimesByMovieId($movieId);
        $this->sendResponse(['data' => $showtimes]);
    }

    private function getDetails(int $showtimeId) {
        $details = $this->showtimeModel->getShowtimeDetails($showtimeId);
        $this->sendResponse(['data' => $details]);
    }
    
    private function sendResponse($payload, $statusCode = 200) {
        if (ob_get_length()) ob_clean();
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit();
    }
}
