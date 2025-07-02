<?php
require_once __DIR__ . '/../../connection/db.php';
$sql = "CREATE TABLE IF NOT EXISTS booked_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    showtime_id INT NOT NULL,
    seat_identifier VARCHAR(10) NOT NULL, -- e.g., 'A1', 'C12'
    UNIQUE KEY unique_seat_per_show (showtime_id, seat_identifier),
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE
) ENGINE=InnoDB;";
if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'booked_seats': " . $conn->error);
}
?>