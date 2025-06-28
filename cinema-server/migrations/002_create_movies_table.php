<?php
require_once __DIR__ . '/../connection/db.php';

$sql = "CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    poster_url VARCHAR(255),
    trailer_url VARCHAR(255),
    genre VARCHAR(100),
    duration_minutes INT,
    age_rating VARCHAR(10),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

if ($conn->query($sql) === TRUE) {
    echo "Table 'movies' created successfully.\n";
} else {
    echo "Error creating table 'movies': " . $conn->error . "\n";
}
?>