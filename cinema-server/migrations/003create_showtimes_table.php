<?php
require_once __DIR__ . '/../connection/connection.php';

$query = "CREATE TABLE IF NOT EXISTS showtimes (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    movie_id INT(11) UNSIGNED NOT NULL,
    show_time DATETIME NOT NULL,
    auditorium_number INT(3) NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
)";

$execute = $mysqli->prepare($query);

if ($execute->execute()) {
    echo "Table 'showtimes' created successfully or already exists.\n";
} else {
    echo "Error creating table 'showtimes': " . $mysqli->error . "\n";
}
?>