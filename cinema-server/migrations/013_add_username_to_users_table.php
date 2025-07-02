<?php
// cinema-server/database/migrations/014_add_username_to_users_table.php
require_once __DIR__ . '/../connection/db.php';

$tableName = 'users';
$columnName = 'username';

// Check if the column already exists to prevent errors
$result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
if ($result->num_rows == 0) {
    // This SQL is clean and will add the unique username column
    $sql = "ALTER TABLE `users` ADD COLUMN `username` VARCHAR(100) NULL UNIQUE AFTER `email`";
    if ($conn->query($sql) !== TRUE) {
        die("FATAL ERROR: Could not add column '$columnName'. MYSQL Error: " . $conn->error);
    }
    echo "Migration 014: Column '$columnName' added to table '$tableName' successfully.\n";
} else {
    echo "Migration 014: Column '$columnName' already exists in table '$tableName'. Skipping.\n";
}
?>
