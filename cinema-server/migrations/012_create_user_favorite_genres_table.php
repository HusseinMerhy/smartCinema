<?php
// cinema-server/database/migrations/013_create_user_favorite_genres_table.php

require_once __DIR__ . '/../connection/db.php';

$sql = "CREATE TABLE IF NOT EXISTS user_favorite_genres (
    user_id INT NOT NULL,
    genre_id INT NOT NULL,
    PRIMARY KEY (user_id, genre_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
) ENGINE=InnoDB;";

if ($conn->query($sql) !== TRUE) {
    die("Error creating table 'user_favorite_genres': " . $conn->error);
}
echo "Table 'user_favorite_genres' created successfully.\n";
?>