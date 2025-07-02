<?php
// cinema-server/models/Showtime.php

class Showtime {
    private $db;
    public function __construct(mysqli $db_connection) { $this->db = $db_connection; }

    /**
     * --- THE NEW METHOD ---
     * Gets all showtimes with their related movie and hall names for the admin panel.
     */
    public function getAllShowtimes() {
        $sql = "SELECT 
                    s.id, s.show_time, s.format, s.base_price,
                    s.movie_id, s.hall_id,
                    m.title as movie_title,
                    h.name as hall_name
                FROM showtimes s
                LEFT JOIN movies m ON s.movie_id = m.id
                LEFT JOIN cinema_halls h ON s.hall_id = h.id
                ORDER BY s.show_time DESC";
        
        $result = $this->db->query($sql);
        if (!$result) {
            error_log("getAllShowtimes query failed: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getShowtimesByMovieId(int $movieId) {
        $sql = "SELECT s.id, s.show_time, s.format, s.base_price, h.name as hall_name FROM showtimes s JOIN cinema_halls h ON s.hall_id = h.id WHERE s.movie_id = ? AND s.show_time >= NOW() ORDER BY s.show_time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $groupedShowtimes = [];
        foreach ($results as $showtime) {
            $date = date('Y-m-d', strtotime($showtime['show_time']));
            if (!isset($groupedShowtimes[$date])) { $groupedShowtimes[$date] = []; }
            $groupedShowtimes[$date][] = $showtime;
        }
        return $groupedShowtimes;
    }

    public function getShowtimeDetails(int $showtimeId) {
        $details = [];
        $sqlHall = "SELECT h.total_rows, h.seats_per_row FROM showtimes s JOIN cinema_halls h ON s.hall_id = h.id WHERE s.id = ?";
        $stmtHall = $this->db->prepare($sqlHall);
        $stmtHall->bind_param("i", $showtimeId);
        $stmtHall->execute();
        $details['layout'] = $stmtHall->get_result()->fetch_assoc();
        $stmtHall->close();
        
        $sqlSeats = "SELECT seat_identifier FROM booked_seats WHERE showtime_id = ?";
        $stmtSeats = $this->db->prepare($sqlSeats);
        $stmtSeats->bind_param("i", $showtimeId);
        $stmtSeats->execute();
        $results = $stmtSeats->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtSeats->close();
        $details['booked_seats'] = array_column($results, 'seat_identifier');
        return $details;
    }
}
