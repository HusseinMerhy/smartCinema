<?php
// This is the SECOND script you run.
// From your project root: php cinema-server/database/run-seeds.php

require_once __DIR__ . '/../connection/db.php';

// Include all seeder files which contain the seed functions
require_once __DIR__ . '/seeds/001_seed_movies.php';
require_once __DIR__ . '/seeds/002_seed_movie_casts.php';
require_once __DIR__ . '/seeds/003_seed_movie_trailers.php';
require_once __DIR__ . '/seeds/004_seed_movie_ratings.php';
require_once __DIR__ . '/seeds/005_seed_cinema_halls.php';
require_once __DIR__ . '/seeds/006_seed_showtimes.php';
require_once __DIR__ . '/seeds/007_seed_genres.php';

echo "--- Starting Database Seeding ---\n";

try {
    $conn->begin_transaction();
    $conn->query("SET FOREIGN_KEY_CHECKS=0");

    // --- THE FIX IS HERE ---
    // Truncate tables in reverse order of creation.
    // The new tables (user_favorite_genres and genres) are now included.
    $conn->query("TRUNCATE TABLE user_favorite_genres");
    $conn->query("TRUNCATE TABLE genres");
    $conn->query("TRUNCATE TABLE booked_seats");
    $conn->query("TRUNCATE TABLE bookings");
    $conn->query("TRUNCATE TABLE showtimes");
    $conn->query("TRUNCATE TABLE cinema_halls");
    $conn->query("TRUNCATE TABLE movie_ratings");
    $conn->query("TRUNCATE TABLE movie_trailers");
    $conn->query("TRUNCATE TABLE movie_casts");
    $conn->query("TRUNCATE TABLE movies");
    // We don't truncate users to avoid deleting login accounts during development
    
    // Call each seed function, passing the connection object
    // The order of seeding matters
    seed_movies($conn);
    seed_movie_casts($conn);
    seed_movie_trailers($conn);
    seed_movie_ratings($conn);
    seed_cinema_halls($conn); 
    seed_showtimes($conn);    
    
    // --- THE FIX IS HERE ---
    // A semicolon was missing from this line, which caused a syntax error.
    seed_genres($conn);

    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    $conn->commit();
    echo "\n--- All data seeded successfully! ---\n";

} catch (Exception $e) {
    $conn->rollback();
    echo "\nAN ERROR OCCURRED: " . $e->getMessage() . "\n";
} finally {
    $conn->close();
}
?>