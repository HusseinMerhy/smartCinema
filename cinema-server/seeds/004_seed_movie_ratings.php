<?php
function seed_movie_ratings(mysqli $conn) {
    echo "Seeding movie ratings...\n";
    $sql = "INSERT INTO movie_ratings (movie_id, rating) VALUES
    (1, 9.2), (1, 8.8), (1, 9.5),
    (2, 8.0), (2, 8.5),
    (3, 7.3), (3, 7.0), (3, 8.0),
    (4, 9.8), (4, 9.5),
    (5, 7.9), (5, 8.2),
    (6, 7.5), (6, 7.8),
    (7, 6.9), (7, 7.2),
    (8, 7.9), (8, 8.1),
    (9, 6.7), (9, 7.1),
    (10, 8.4), (10, 8.6),
    (11, 8.9), (12, 9.1), (13, 8.5), (14, 9.3), (15, 6.5),
    (16, 7.0), (17, 6.2), (18, 7.4), (19, 8.3), (20, 9.0);";
    if ($conn->query($sql) === TRUE) {
        echo "Movie ratings seeded successfully.\n";
    } else {
        echo "Error seeding movie ratings: " . $conn->error . "\n";
    }
}
?>