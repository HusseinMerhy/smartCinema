<?php
// cinema-server/database/seeds/005_seed_cinema_halls.php

function seed_cinema_halls(mysqli $conn) {
    echo "Seeding cinema halls...\n";

    // Truncate the table to prevent duplicate data on re-seeding
    $conn->query("TRUNCATE TABLE cinema_halls");

    $sql = "INSERT INTO cinema_halls (id, name, total_rows, seats_per_row) VALUES
        (1, 'Grand Theatre - Hall 1', 10, 12),
        (2, 'IMAX Experience - Hall 2', 8, 16),
        (3, 'Cozy Cinema - Hall 3', 6, 8);";

    if ($conn->query($sql) === TRUE) {
        echo "Cinema halls seeded successfully.\n";
    } else {
        echo "Error seeding cinema halls: " . $conn->error . "\n";
    }
}
?>