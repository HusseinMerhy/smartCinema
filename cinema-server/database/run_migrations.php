<?php
require_once __DIR__ . '/../connection/db.php';
echo "--- Starting Database Migrations ---\n\n";

$migration_files = [
    'migrations/001_create_users_table.php',
    'migrations/002_create_movies_table.php',
    'migrations/003_create_movie_casts_table.php',
    'migrations/004_create_movie_trailers_table.php',
    'migrations/005_create_movie_ratings_table.php',
    'migrations/006_create_cinema_halls_table.php',
    'migrations/007_create_showtimes_table.php',
    'migrations/008_create_bookings_table.php',
    'migrations/009_create_booked_seats_table.php',
    'migrations/010_add_role_to_users_table.php',
    'migrations/011_create_genres_table.php',
    'migrations/012_create_user_favorite_genres_table.php',
    'migrations/013_add_username_to_users_table.php' // The new file
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
