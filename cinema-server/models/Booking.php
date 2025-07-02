<?php
// cinema-server/models/Booking.php

class Booking {
    private $db;
    private const MAX_TICKETS_PER_MOVIE = 4;

    public function __construct(mysqli $db_connection) {
        $this->db = $db_connection;
    }

    public function createBooking(int $userId, int $showtimeId, array $seats, float $totalPrice): array {
        $this->db->begin_transaction();
        try {
            // Step 1: Get movie_id
            $stmt_movie = $this->db->prepare("SELECT movie_id FROM showtimes WHERE id = ?");
            if (!$stmt_movie) throw new Exception("DB error (get movie_id)");
            $stmt_movie->bind_param("i", $showtimeId);
            $stmt_movie->execute();
            $result_movie = $stmt_movie->get_result();
            if ($result_movie->num_rows === 0) throw new Exception("Invalid showtime ID.");
            $movie_id = $result_movie->fetch_assoc()['movie_id'];
            $stmt_movie->close();

            // Step 2: Count existing tickets for this movie
            $stmt_count = $this->db->prepare("SELECT COUNT(bs.id) as ticket_count FROM bookings b JOIN booked_seats bs ON b.id = bs.booking_id JOIN showtimes s ON b.showtime_id = s.id WHERE b.user_id = ? AND s.movie_id = ?");
            if (!$stmt_count) throw new Exception("DB error (count tickets)");
            $stmt_count->bind_param("ii", $userId, $movie_id);
            $stmt_count->execute();
            $current_tickets = (int)$stmt_count->get_result()->fetch_assoc()['ticket_count'];
            $stmt_count->close();
            
            // Step 3: Enforce the cap
            if (($current_tickets + count($seats)) > self::MAX_TICKETS_PER_MOVIE) {
                $this->db->rollback();
                return ['success' => false, 'message' => "Booking failed: You cannot have more than " . self::MAX_TICKETS_PER_MOVIE . " tickets for this movie."];
            }
            
            // Step 4: Check if seats are available
            if (!empty($seats)) {
                $placeholders = implode(',', array_fill(0, count($seats), '?'));
                $stmt_check = $this->db->prepare("SELECT seat_identifier FROM booked_seats WHERE showtime_id = ? AND seat_identifier IN ($placeholders) FOR UPDATE");
                $stmt_check->bind_param('i' . str_repeat('s', count($seats)), $showtimeId, ...$seats);
                $stmt_check->execute();
                $result = $stmt_check->get_result();
                if ($result->num_rows > 0) {
                    $this->db->rollback();
                    return ['success' => false, 'message' => "Sorry, seat " . $result->fetch_assoc()['seat_identifier'] . " was just booked."];
                }
                $stmt_check->close();
            }

            // Step 5: Create booking record
            $stmt_booking = $this->db->prepare("INSERT INTO bookings (user_id, showtime_id, total_price) VALUES (?, ?, ?)");
            $stmt_booking->bind_param("iid", $userId, $showtimeId, $totalPrice);
            $stmt_booking->execute();
            $bookingId = $this->db->insert_id;
            $stmt_booking->close();

            // Step 6: Insert booked seats
            if (!empty($seats)) {
                $stmt_seats = $this->db->prepare("INSERT INTO booked_seats (booking_id, showtime_id, seat_identifier) VALUES (?, ?, ?)");
                foreach ($seats as $seat) { $stmt_seats->bind_param("iis", $bookingId, $showtimeId, $seat); $stmt_seats->execute(); }
                $stmt_seats->close();
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Booking successful!'];
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Booking Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'A server error occurred.'];
        }
    }

    public function deleteBooking(int $bookingId, int $userId): array {
        $this->db->begin_transaction();
        try {
            $stmt_verify = $this->db->prepare("SELECT s.show_time FROM bookings b JOIN showtimes s ON b.showtime_id = s.id WHERE b.id = ? AND b.user_id = ?");
            $stmt_verify->bind_param("ii", $bookingId, $userId);
            $stmt_verify->execute();
            $result = $stmt_verify->get_result();
            if ($result->num_rows === 0) {
                $this->db->rollback();
                return ['success' => false, 'message' => 'Booking not found or you do not have permission to cancel it.'];
            }
            $show_time = new DateTime($result->fetch_assoc()['show_time']);
            if ($show_time < new DateTime()) {
                $this->db->rollback();
                return ['success' => false, 'message' => 'Cannot cancel a booking for a show that has already passed.'];
            }
            $stmt_verify->close();

            $stmt_delete = $this->db->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt_delete->bind_param("i", $bookingId);
            $stmt_delete->execute();
            
            if ($stmt_delete->affected_rows > 0) {
                $this->db->commit();
                return ['success' => true, 'message' => 'Booking cancelled successfully.'];
            } else {
                throw new Exception("Deletion failed, no rows affected.");
            }
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Delete Booking Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'A server error occurred. Could not cancel the booking.'];
        }
    }
}
