<?php
// cinema-server/seeds/008_seed_snacks.php

function seed_snacks($conn) {
    $snacks = [
        ['Large Popcorn', 'Freshly popped buttery popcorn', 8.50, 'popcorn_large.jpg'],
        ['Medium Popcorn', 'Salted popcorn perfect for one', 6.50, 'popcorn_medium.jpg'],
        ['Cola (Large)', 'Ice cold cola', 4.00, 'cola_large.jpg'],
        ['Nachos', 'Crispy nachos with cheese dip', 7.50, 'nachos.jpg'],
        ['Chocolate Bar', 'Milk chocolate bar', 3.00, 'chocolate.jpg'],
        ['Water', 'Mineral water', 2.00, 'water.jpg']
    ];

    echo "Seeding Snacks...\n";

    $stmt = $conn->prepare("INSERT INTO snacks (name, description, price, image_url) VALUES (?, ?, ?, ?)");

    foreach ($snacks as $snack) {
        $stmt->bind_param("ssds", $snack[0], $snack[1], $snack[2], $snack[3]);
        if (!$stmt->execute()) {
            echo "Error inserting " . $snack[0] . ": " . $stmt->error . "\n";
        }
    }

    echo "Snacks seeded successfully.\n";
}
?>