<?php
// cinema-server/database/migrations/011_add_role_to_users_table.php

require_once __DIR__ . '/../../connection/db.php';

$tableName = 'users';
$columnName = 'role';

// Check if the column already exists to prevent errors on re-running
$result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
if ($result->num_rows == 0) {
    // Add the role column with a default value of 'customer'
    $sql = "ALTER TABLE `$tableName` ADD COLUMN `$columnName` VARCHAR(50) NOT NULL DEFAULT 'customer' AFTER `password`";
    if ($conn->query($sql) !== TRUE) {
        die("Error adding column '$columnName' to table '$tableName': " . $conn->error);
    }
    echo "Column '$columnName' added to table '$tableName' successfully.\n";
} else {
    echo "Column '$columnName' already exists in table '$tableName'.\n";
}
?>