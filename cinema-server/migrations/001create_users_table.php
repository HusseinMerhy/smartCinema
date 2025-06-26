<?php
require_once __DIR__ . '/../connection/connection.php';

$query = "CREATE TABLE IF NOT EXISTS movies (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    poster_url VARCHAR(255),
    trailer_url VARCHAR(255),
    cast TEXT,
    genre VARCHAR(100),
    age_rating VARCHAR(10) NOT NULL,
    release_date DATE,
    duration_minutes INT(5)
)";

$execute = $mysqli->prepare($query);

if ($execute->execute()) {
    echo "Table 'movies' created successfully or already exists.\n";
} else {
    echo "Error creating table 'movies': " . $mysqli->error . "\n";
}
?>