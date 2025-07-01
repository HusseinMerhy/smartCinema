<?php
// cinema-server/controllers/AdminController.php
error_reporting(E_ALL); ini_set('display_errors', 0); ini_set('log_errors', 1);

class AdminController {
    private $db;
    public function __construct(mysqli $db_connection) { $this->db = $db_connection; }
    
    public function addMovie() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['title']) || !isset($data['is_coming_soon'])) { $this->sendResponse(['message' => 'Title and coming soon status are required.'], 400); }
        $sql = "INSERT INTO movies (title, poster_url, genre, duration_minutes, age_rating, description, release_date, is_coming_soon) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $is_coming_soon = (int)$data['is_coming_soon'];
        $stmt->bind_param("sssisssi", $data['title'], $data['poster_url'], $data['genre'], $data['duration_minutes'], $data['age_rating'], $data['description'], $data['release_date'], $is_coming_soon);
        if ($stmt->execute()) { $this->sendResponse(['message' => 'Movie added successfully.', 'id' => $this->db->insert_id], 201); } 
        else { $this->sendResponse(['message' => 'Failed to add movie: ' . $stmt->error], 500); }
        $stmt->close();
    }

    public function updateMovie() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['movie_id']) || empty($data['title']) || !isset($data['is_coming_soon'])) { $this->sendResponse(['message' => 'Movie ID, Title, and coming soon status are required.'], 400); }
        $sql = "UPDATE movies SET title = ?, poster_url = ?, genre = ?, duration_minutes = ?, age_rating = ?, description = ?, release_date = ?, is_coming_soon = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $is_coming_soon = (int)$data['is_coming_soon'];
        $stmt->bind_param("sssisssii", $data['title'], $data['poster_url'], $data['genre'], $data['duration_minutes'], $data['age_rating'], $data['description'], $data['release_date'], $is_coming_soon, $data['movie_id']);
        if ($stmt->execute()) { $this->sendResponse(['message' => 'Movie updated successfully.']); }
        else { $this->sendResponse(['message' => 'Failed to update movie.'], 500); }
        $stmt->close();
    }

    public function deleteMovie(int $movieId) {
        $sql = "DELETE FROM movies WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $movieId);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) { $this->sendResponse(['message' => 'Movie deleted successfully.']); } 
            else { $this->sendResponse(['message' => 'Movie not found.'], 404); }
        } else { $this->sendResponse(['message' => 'Failed to delete movie: ' . $stmt->error], 500); }
        $stmt->close();
    }

    public function addShowtime() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['movie_id']) || empty($data['hall_id']) || empty($data['show_time']) || !isset($data['base_price'])) { $this->sendResponse(['message' => 'All showtime fields are required.'], 400); }
        $sql = "INSERT INTO showtimes (movie_id, hall_id, show_time, format, base_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iisid", $data['movie_id'], $data['hall_id'], $data['show_time'], $data['format'], $data['base_price']);
        if ($stmt->execute()) { $this->sendResponse(['message' => 'Showtime added.'], 201); } 
        else { $this->sendResponse(['message' => 'Failed to add showtime.'], 500); }
        $stmt->close();
    }

    public function updateShowtime() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['showtime_id']) || empty($data['movie_id']) || empty($data['hall_id']) || empty($data['show_time']) || !isset($data['base_price'])) { $this->sendResponse(['message' => 'All showtime fields are required for update.'], 400); }
        $sql = "UPDATE showtimes SET movie_id = ?, hall_id = ?, show_time = ?, format = ?, base_price = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iisidi", $data['movie_id'], $data['hall_id'], $data['show_time'], $data['format'], $data['base_price'], $data['showtime_id']);
        if ($stmt->execute()) { $this->sendResponse(['message' => 'Showtime updated successfully.']); }
        else { $this->sendResponse(['message' => 'Failed to update showtime.'], 500); }
        $stmt->close();
    }

    public function deleteShowtime(int $showtimeId) {
        $sql = "DELETE FROM showtimes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $showtimeId);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) { $this->sendResponse(['message' => 'Showtime deleted successfully.']); }
            else { $this->sendResponse(['message' => 'Showtime not found.'], 404); }
        } else { $this->sendResponse(['message' => 'Failed to delete showtime: ' . $stmt->error], 500); }
        $stmt->close();
    }
    
    public function getHalls() {
        $result = $this->db->query("SELECT id, name FROM cinema_halls ORDER BY name");
        $this->sendResponse(['data' => $result->fetch_all(MYSQLI_ASSOC)]);
    }
    
    private function sendResponse($payload, $statusCode = 200) { if(ob_get_length()) ob_clean(); http_response_code($statusCode); header('Content-Type: application/json'); echo json_encode($payload); exit(); }
}
