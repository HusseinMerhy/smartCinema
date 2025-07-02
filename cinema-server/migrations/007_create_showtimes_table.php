<?php
require_once __DIR__ . '/../../connection/db.php';
$sql = "CREATE TABLE IF NOT EXISTS showtimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    hall_id INT NOT NULL,
    show_time DATETIME NOT NULL,
    format VARCHAR(20) DEFAULT '2D',
    base_price DECIMAL(5, 2) NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (hall_id) REFERENCES cinema_halls(id) ON DELETE CASCADE
) ENGINE=InnoDB;";
if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'showtimes': " . $conn->error);
}
?>