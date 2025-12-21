<?php
// cinema-server/models/Booking.php

require_once 'Model.php';

class Booking extends Model {

    /**
     * Fetch all booked seats for a specific showtime
     */
    public function getBookedSeatsByShowtime($showtimeId) {
        $sql = "SELECT seat_identifier FROM booked_seats WHERE showtime_id = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Database Prepare Error: " . $this->db->error);
        }

        $stmt->bind_param("i", $showtimeId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $seats = [];
        while ($row = $result->fetch_assoc()) {
            $seats[] = ['seat_number' => $row['seat_identifier']];
        }
        return $seats;
    }

    /**
     * Create a new booking with seats and snacks in a single transaction
     */
    public function createBooking($userId, $showtimeId, $seats, $snacks, $totalPrice) {
        $this->db->begin_transaction();

        try {
            // 1. Check if seats are already taken
            if (!empty($seats)) {
                $placeholders = implode(',', array_fill(0, count($seats), '?'));
                $checkSql = "SELECT id FROM booked_seats WHERE showtime_id = ? AND seat_identifier IN ($placeholders)";
                
                $stmt = $this->db->prepare($checkSql);
                $params = array_merge([$showtimeId], $seats);
                $types = 'i' . str_repeat('s', count($seats));
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    throw new Exception("One or more selected seats are already booked.");
                }
            }

            // 2. Insert into bookings table
            $stmt = $this->db->prepare("INSERT INTO bookings (user_id, showtime_id, total_price, booking_date) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iid", $userId, $showtimeId, $totalPrice);
            if (!$stmt->execute()) throw new Exception("Booking insert failed: " . $stmt->error);
            $bookingId = $this->db->insert_id;

            // 3. Insert into booked_seats table
            $stmt = $this->db->prepare("INSERT INTO booked_seats (booking_id, showtime_id, seat_identifier, price) VALUES (?, ?, ?, ?)");
            $seatPrice = 10.00; // Default price
            
            foreach ($seats as $seat) {
                $stmt->bind_param("iisd", $bookingId, $showtimeId, $seat, $seatPrice);
                if (!$stmt->execute()) throw new Exception("Failed to save seat: " . $stmt->error);
            }

            // 4. Insert into booking_snacks table
            if (!empty($snacks)) {
                $stmt = $this->db->prepare("INSERT INTO booking_snacks (booking_id, snack_id, quantity, price_at_booking) VALUES (?, ?, ?, ?)");
                foreach ($snacks as $s) {
                    $stmt->bind_param("iiid", $bookingId, $s['id'], $s['quantity'], $s['price']);
                    $stmt->execute();
                }
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Booking successful', 'booking_id' => $bookingId];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Delete a booking (Fixes PHP0418 error)
     */
    public function deleteBooking($bookingId, $userId) {
        // We check for user_id to ensure users can only delete their own bookings
        $stmt = $this->db->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
        
        if (!$stmt) {
            return ['success' => false, 'message' => "Prepare failed: " . $this->db->error];
        }

        $stmt->bind_param("ii", $bookingId, $userId);
        
        if ($stmt->execute()) {
             return [
                'success' => $stmt->affected_rows > 0,
                'message' => $stmt->affected_rows > 0 ? "Booking deleted" : "Booking not found or unauthorized"
             ];
        }
        
        return ['success' => false, 'message' => $stmt->error];
    }
}