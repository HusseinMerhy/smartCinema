<?php
require_once __DIR__ . '/../../connection/db.php';

$sql = "CREATE TABLE IF NOT EXISTS movie_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    user_id INT,
    rating DECIMAL(3, 1) NOT NULL,
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB;";

if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'movie_ratings': " . $conn->error);
}

?>