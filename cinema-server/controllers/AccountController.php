<?php
// cinema-server/controllers/AccountController.php
error_reporting(E_ALL); 
ini_set('display_errors', 0); 
ini_set('log_errors', 1);

class AccountController {
    private $db;
    public function __construct(mysqli $db_connection) { $this->db = $db_connection; }

    public function getProfile(int $userId) {
        $stmt = $this->db->prepare("SELECT id, email, username, phoneNumber, role FROM users WHERE id = ?");
        if (!$stmt) { $this->sendResponse(['message' => 'Database query failed.'], 500); }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$user) { $this->sendResponse(['message' => 'User not found'], 404); }

        $stmt_genres = $this->db->prepare("SELECT g.name FROM users u LEFT JOIN user_favorite_genres ufg ON u.id = ufg.user_id LEFT JOIN genres g ON ufg.genre_id = g.id WHERE u.id = ?");
        if (!$stmt_genres) { $this->sendResponse(['message' => 'Database query for genres failed.'], 500); }
        $stmt_genres->bind_param("i", $userId);
        $stmt_genres->execute();
        $genres_result = $stmt_genres->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_genres->close();
        
        $user['favoriteGenres'] = array_filter(array_column($genres_result, 'name'));
        $this->sendResponse(['data' => $user]);
    }

    /**
     * Fetch all bookings for a user
     * FIXED: Changed 'booking_time' to 'booking_date' to match the database schema.
     */
    public function getBookings(int $userId) {
        // Updated 'b.booking_time' to 'b.booking_date'
        $sql = "SELECT 
                    b.id as booking_id,
                    b.booking_date, 
                    b.total_price,
                    m.title as movie_title,
                    m.poster_url,
                    s.show_time,
                    h.name as hall_name,
                    GROUP_CONCAT(bs.seat_identifier ORDER BY bs.seat_identifier SEPARATOR ', ') as seats
                FROM bookings b
                JOIN showtimes s ON b.showtime_id = s.id
                JOIN movies m ON s.movie_id = m.id
                JOIN cinema_halls h ON s.hall_id = h.id
                LEFT JOIN booked_seats bs ON b.id = bs.booking_id
                WHERE b.user_id = ?
                GROUP BY b.id
                ORDER BY b.booking_date DESC";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) { 
            // If it still fails, it might be a different column name mismatch
            $this->sendResponse(['message' => 'Database query failed: ' . $this->db->error], 500); 
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Convert the comma-separated 'seats' string into an array for each booking
        foreach ($bookings as $key => $booking) {
            if ($booking['seats']) {
                $bookings[$key]['seats'] = explode(', ', $booking['seats']);
            } else {
                $bookings[$key]['seats'] = [];
            }
        }

        $this->sendResponse(['data' => $bookings]);
    }

    public function updateProfile(int $userId) {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->db->begin_transaction();
        try {
            $this->updateUserFields($userId, $data);
            $this->updateUserGenres($userId, $data);
            $this->db->commit();
            $this->getProfile($userId);
        } catch (Exception $e) {
            $this->db->rollback();
            if ($this->db->errno === 1062) { $this->sendResponse(['message' => 'Update failed: Username or Email already taken.'], 409); }
            error_log("Update Profile Error: " . $e->getMessage());
            $this->sendResponse(['message' => 'Failed to update profile.'], 500);
        }
    }

    private function updateUserFields(int $userId, array $data) {
        $fields = []; $params = []; $types = '';
        if (isset($data['username'])) { $fields[] = 'username = ?'; $params[] = $data['username']; $types .= 's'; }
        if (isset($data['phoneNumber'])) { $fields[] = 'phoneNumber = ?'; $params[] = $data['phoneNumber']; $types .= 's'; }
        if (!empty($data['password'])) { $fields[] = 'password = ?'; $params[] = password_hash($data['password'], PASSWORD_DEFAULT); $types .= 's'; }
        if (!empty($fields)) {
            $params[] = $userId; $types .= 'i';
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) throw new Exception($this->db->error);
            $stmt->bind_param($types, ...$params);
            if (!$stmt->execute()) throw new Exception($stmt->error);
            $stmt->close();
        }
    }

    private function updateUserGenres(int $userId, array $data) {
        if (isset($data['favoriteGenres']) && is_array($data['favoriteGenres'])) {
            $stmt_delete = $this->db->prepare("DELETE FROM user_favorite_genres WHERE user_id = ?");
            $stmt_delete->bind_param("i", $userId); $stmt_delete->execute(); $stmt_delete->close();
            $genreNames = $data['favoriteGenres'];
            if (!empty($genreNames)) {
                $placeholders = implode(',', array_fill(0, count($genreNames), '?'));
                $stmt_get_ids = $this->db->prepare("SELECT id FROM genres WHERE name IN ($placeholders)");
                $stmt_get_ids->bind_param(str_repeat('s', count($genreNames)), ...$genreNames);
                $stmt_get_ids->execute();
                $genre_ids = array_column($stmt_get_ids->get_result()->fetch_all(MYSQLI_ASSOC), 'id');
                $stmt_get_ids->close();
                $stmt_insert = $this->db->prepare("INSERT INTO user_favorite_genres (user_id, genre_id) VALUES (?, ?)");
                foreach ($genre_ids as $genre_id) { $stmt_insert->bind_param("ii", $userId, $genre_id); $stmt_insert->execute(); }
                $stmt_insert->close();
            }
        }
    }

    private function sendResponse($payload, $statusCode = 200) { 
        if(ob_get_length()) ob_clean(); 
        http_response_code($statusCode); 
        header('Content-Type: application/json'); 
        echo json_encode($payload); 
        exit(); 
    }
}