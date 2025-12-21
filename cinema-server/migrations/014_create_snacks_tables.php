<?php
// cinema-server/migrations/014_create_snacks_tables.php

$sql1 = "CREATE TABLE IF NOT EXISTS snacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) DEFAULT 'default_snack.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$sql2 = "CREATE TABLE IF NOT EXISTS booking_snacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    snack_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_booking DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (snack_id) REFERENCES snacks(id)
)";

if ($conn->query($sql1) === TRUE) {
    echo "Table 'snacks' created successfully.\n";
} else {
    echo "Error creating table 'snacks': " . $conn->error . "\n";
}

if ($conn->query($sql2) === TRUE) {
    echo "Table 'booking_snacks' created successfully.\n";
} else {
    echo "Error creating table 'booking_snacks': " . $conn->error . "\n";
}
?>