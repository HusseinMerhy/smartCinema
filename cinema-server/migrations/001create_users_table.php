<?php
require "../connection/db.php"; // Using your specified path

$query = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- <<< THE FIX IS HERE
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mobile_number VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$execute = $conn->prepare($query);
if ($execute && $execute->execute()) { // Added check for $execute itself
    echo "Table 'users' created successfully or already exists.\n";
} else {
    echo "Error creating table 'users': " . $conn->error . "\n";
}
?>