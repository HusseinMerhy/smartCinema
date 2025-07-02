<?php
// cinema-server/database/migrations/012_create_genres_table.php

require_once __DIR__ . '/../connection/db.php';

$sql = "CREATE TABLE IF NOT EXISTS genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;";

if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'genres': " . $conn->error);
}
echo "Table 'genres' created successfully.\n";
?>