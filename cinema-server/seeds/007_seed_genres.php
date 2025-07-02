<?php
// cinema-server/database/seeds/007_seed_genres.php

function seed_genres(mysqli $conn) {
    echo "Seeding genres...\n";
    
    $genres = ["Action", "Adventure", "Comedy", "Drama", "Sci-Fi", "Thriller", "Horror", "Romance", "Animation", "Biography", "War", "Fantasy", "Crime"];

    $sql = "INSERT INTO genres (name) VALUES (?) ON DUPLICATE KEY UPDATE name=name"; // ON DUPLICATE KEY prevents errors on re-run
    $stmt = $conn->prepare($sql);
    
    foreach ($genres as $genre) {
        $stmt->bind_param("s", $genre);
        $stmt->execute();
    }
    
    $stmt->close();
    echo "Genres seeded successfully.\n";
}
?>