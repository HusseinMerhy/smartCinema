<?php
// This script is executed by run_migrations.php
require_once __DIR__ . '/../../connection/db.php';

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    phoneNumber VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    favoriteGenres VARCHAR(255),
    paymentMethod VARCHAR(255),
    communicationPrefs VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";

if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'users': " . $conn->error);
}
?>