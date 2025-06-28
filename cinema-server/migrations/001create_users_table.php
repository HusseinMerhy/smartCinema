<?php
require_once __DIR__ . '/../connection/db.php';

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    mobile VARCHAR(20) NULL,
    favorite_genres VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' is ready.\n";
} else {
    echo "Error creating table 'users': " . $conn->error . "\n";
}
?>
