<?php
require("../connection/db.php");

$query = "CREATE TABLE IF NOT EXISTS showtimes (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    movie_id INT(11) UNSIGNED NOT NULL,
    show_time DATETIME NOT NULL,
    auditorium_number INT(3) NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
)";

    $execute = $conn->prepare($query);
    if ($execute && $execute->execute()) {    
        // FIX: Corrected the table name in the success message
        echo "Table 'showtimes' created successfully or already exists.\n";
    } else {
        // FIX: Corrected the table name in the error message
        echo "Error creating table 'showtimes': " . $conn->error . "\n";
    }
?>