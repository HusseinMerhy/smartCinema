<?php
require_once __DIR__ . '/../connection/db.php';
echo "--- Starting Database Migrations ---\n\n";

$migration_files = [
    '001_create_users_table.php',
    '002_create_movies_table.php',
    '003_create_movie_casts_table.php',
    '004_create_movie_trailers_table.php',
    '005_create_movie_ratings_table.php',
    '006_create_cinema_halls_table.php',
    '007_create_showtimes_table.php',
    '008_create_bookings_table.php',
    '009_create_booked_seats_table.php',
    '010_add_role_to_users_table.php',
    '011_create_genres_table.php',
    '012_create_user_favorite_genres_table.php',
    '013_add_username_to_users_table.php',
    '014_create_snacks_tables.php' // <--- Added this line
];

try {
    foreach ($migration_files as $file) {
        if (file_exists(__DIR__ . '/' . $file)) {
            echo "Running: $file\n";
            require __DIR__ . '/' . $file;
        } else {
            die("\nFATAL ERROR: Migration file not found at " . __DIR__ . '/' . $file . "\n");
        }
    }
    echo "\n--- All migrations completed successfully! ---\n";
} catch (Exception $e) {
    echo "\nAN ERROR OCCURRED: " . $e->getMessage() . "\n";
}
?>