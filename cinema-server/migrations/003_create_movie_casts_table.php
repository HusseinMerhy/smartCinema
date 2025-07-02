<?php
require_once __DIR__ . '/../connection/db.php';

$sql = "CREATE TABLE IF NOT EXISTS movie_casts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    actor_name VARCHAR(255) NOT NULL,
    character_name VARCHAR(255),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB;";

if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'movie_casts': " . $conn->error);
}

?>