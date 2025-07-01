<?php
require_once __DIR__ . '/../../connection/db.php';
$sql = "CREATE TABLE IF NOT EXISTS cinema_halls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    total_rows INT NOT NULL,
    seats_per_row INT NOT NULL
) ENGINE=InnoDB;";
if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'cinema_halls': " . $conn->error);
}
?>