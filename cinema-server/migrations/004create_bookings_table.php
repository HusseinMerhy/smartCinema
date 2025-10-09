<?php
require "../connection/db.php";

$query = "CREATE TABLE IF NOT EXISTS bookings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    showtime_id INT(11) UNSIGNED NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL DEFAULT 'confirmed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE
)";

$execute = $conn->prepare($query);
if ($execute && $execute->execute()) {
    echo "Table 'bookings' created successfully.\n";
} else {
    echo "Error creating table 'bookings': " . $conn->error . "\n";
}
?>
