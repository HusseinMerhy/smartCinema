<?php
require_once __DIR__ . '/../connection/db.php';

$sql = "CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    poster_url VARCHAR(255),
    genre VARCHAR(100),
    duration_minutes INT,
    age_rating VARCHAR(10),
    description TEXT,
    release_date DATE,
    is_coming_soon BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'movies': " . $conn->error);
}

?>