<?php
// From your project root: php cinema-server/seeds/run-seeds.php

require_once __DIR__ . '/../connection/db.php';

// Include all seeder files which contain the seed functions
require_once __DIR__ . '/001_seed_movies.php';
require_once __DIR__ . '/002_seed_movie_casts.php';
require_once __DIR__ . '/003_seed_movie_trailers.php';
require_once __DIR__ . '/004_seed_movie_ratings.php';
require_once __DIR__ . '/005_seed_cinema_halls.php';
require_once __DIR__ . '/006_seed_showtimes.php';
require_once __DIR__ . '/007_seed_genres.php';
require_once __DIR__ . '/008_seed_snacks.php'; // <--- Added this include

echo "--- Starting Database Seeding ---\n";

try {
    $conn->begin_transaction();
    $conn->query("SET FOREIGN_KEY_CHECKS=0");

    // --- TRUNCATE TABLES ---
    // Added snack tables here
    $conn->query("TRUNCATE TABLE booking_snacks");
    $conn->query("TRUNCATE TABLE snacks");
    
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
    
    // --- CALL SEED FUNCTIONS ---
    seed_movies($conn);
    seed_movie_casts($conn);
    seed_movie_trailers($conn);
    seed_movie_ratings($conn);
    seed_cinema_halls($conn); 
    seed_showtimes($conn);    
    seed_genres($conn);
    seed_snacks($conn); // <--- Added this function call

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