<?php
require_once __DIR__ . '/../connection/db.php';

$sql = "CREATE TABLE showtimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    show_time DATETIME NOT NULL,
    auditorium_number INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB;";

if ($conn->query($sql) === TRUE) {
    echo "Table 'showtimes' created successfully.\n";
} else {
    echo "Error creating table 'showtimes': " . $conn->error . "\n";
}
?>