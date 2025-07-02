<?php
// cinema-server/database/seeds/006_seed_showtimes.php

function seed_showtimes(mysqli $conn) {
    echo "Seeding showtimes for all opening movies...\n";
    
    // Truncate the table for a clean slate on every run
    $conn->query("TRUNCATE TABLE showtimes");

    // Generate a wider variety of dynamic dates and times
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    // Note: Movie IDs are from your 001_seed_movies.php file (where is_coming_soon = 0)
    // Hall IDs (1, 2, 3) are from the 005_seed_cinema_halls.php file
    $sql = "INSERT INTO showtimes (movie_id, hall_id, show_time, format, base_price) VALUES
        -- Oppenheimer (ID 1) in the IMAX Hall
        (1, 2, '$today 20:00:00', 'IMAX', 15.00),
        (1, 2, '$tomorrow 19:30:00', 'IMAX', 15.00),
        
        -- Her (ID 2) in the Cozy Hall
        (2, 3, '$today 18:00:00', '2D', 9.50),
        (2, 3, '$tomorrow 21:00:00', '2D', 9.50),

        -- Black Panther (ID 3) in the Grand Theatre
        (3, 1, '$today 17:00:00', '3D', 12.75),
        (3, 1, '$tomorrow 20:30:00', '3D', 12.75),

        -- Interstellar (ID 4) in the IMAX Hall
        (4, 2, '$today 16:00:00', 'IMAX', 15.00),
        (4, 2, '$tomorrow 22:00:00', 'IMAX', 15.00),
        
        -- Dunkirk (ID 5) in the Grand Theatre
        (5, 1, '$today 19:00:00', '2D', 10.50),
        (5, 1, '$tomorrow 15:00:00', '2D', 10.50),
        
        -- John Wick 2 (ID 6) in the Grand Theatre
        (6, 1, '$today 21:30:00', '2D', 10.50),
        (6, 3, '$tomorrow 18:30:00', '2D', 9.50),
        
        -- Aquaman (ID 7) in the Grand Theatre with 3D
        (7, 1, '$today 14:00:00', '3D', 12.75),
        (7, 1, '$tomorrow 17:30:00', '3D', 12.75),
        
        -- Thor: Ragnarok (ID 8) in the Cozy Hall
        (8, 3, '$today 20:45:00', '2D', 9.50),
        (8, 3, '$tomorrow 16:00:00', '2D', 9.50),
        
        -- Bumblebee (ID 9) in the Cozy Hall
        (9, 3, '$today 15:30:00', '2D', 9.50),
        (9, 1, '$tomorrow 12:00:00', '2D', 8.50),
        
        -- Joker (ID 10) in the Grand Theatre
        (10, 1, '$today 23:00:00', '2D', 10.50),
        (10, 2, '$tomorrow 14:00:00', '2D', 11.00);";

    if ($conn->query($sql) === TRUE) {
        echo "Showtimes seeded successfully.\n";
    } else {
        echo "Error seeding showtimes: " . $conn->error . "\n";
    }
}
?>  