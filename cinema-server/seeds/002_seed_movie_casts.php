<?php
function seed_movie_casts(mysqli $conn) {
    echo "Seeding movie casts...\n";
    $sql = "INSERT INTO movie_casts (movie_id, actor_name, character_name) VALUES
    (1, 'Cillian Murphy', 'J. Robert Oppenheimer'), (1, 'Emily Blunt', 'Kitty Oppenheimer'),
    (2, 'Joaquin Phoenix', 'Theodore'), (2, 'Scarlett Johansson', 'Samantha (voice)'),
    (3, 'Chadwick Boseman', 'T''Challa'), (3, 'Michael B. Jordan', 'Killmonger'),
    (4, 'Matthew McConaughey', 'Cooper'), (4, 'Anne Hathaway', 'Brand'),
    (5, 'Fionn Whitehead', 'Tommy'), (5, 'Tom Hardy', 'Farrier'),
    (6, 'Keanu Reeves', 'John Wick'), (6, 'Riccardo Scamarcio', 'Santino D''Antonio'),
    (7, 'Jason Momoa', 'Arthur Curry'), (7, 'Amber Heard', 'Mera'),
    (8, 'Chris Hemsworth', 'Thor'), (8, 'Tom Hiddleston', 'Loki'),
    (9, 'Hailee Steinfeld', 'Charlie Watson'), (9, 'John Cena', 'Jack Burns'),
    (10, 'Joaquin Phoenix', 'Arthur Fleck'), (10, 'Robert De Niro', 'Murray Franklin'),
    (11, 'Robert Pattinson', 'Bruce Wayne'), (11, 'Zoë Kravitz', 'Selina Kyle'),
    (12, 'Sam Worthington', 'Jake Sully'), (12, 'Zoe Saldaña', 'Neytiri'),
    (13, 'To be announced', 'Kang the Conqueror'),
    (15, 'Ben Affleck', 'Batman'), (15, 'Henry Cavill', 'Superman'),
    (16, 'Travis Fimmel', 'Anduin Lothar'), (16, 'Paula Patton', 'Garona Halforcen'),
    (17, 'Dwayne Johnson', 'Davis Okoye'), (17, 'Naomie Harris', 'Dr. Kate Caldwell'),
    (18, 'Scarlett Johansson', 'Natasha Romanoff'), (18, 'Florence Pugh', 'Yelena Belova'),
    (19, 'Benedict Cumberbatch', 'Dr. Stephen Strange'), (19, 'Chiwetel Ejiofor', 'Mordo'),
    (20, 'Shameik Moore', 'Miles Morales (voice)'), (20, 'Hailee Steinfeld', 'Gwen Stacy (voice)');";
    if ($conn->query($sql) === TRUE) {
        echo "Movie casts seeded successfully.\n";
    } else {
        echo "Error seeding movie casts: " . $conn->error . "\n";
    }
}
?>