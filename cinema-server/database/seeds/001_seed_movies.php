<?php
function seed_movies(mysqli $conn) {
    echo "Seeding movies...\n";
    $sql = "INSERT INTO movies (id, title, poster_url, genre, duration_minutes, age_rating, description, release_date, is_coming_soon) VALUES
    (1, 'Oppenheimer', 'assets/images/Oppenheimer.jpg', 'Biography, Drama', 180, 'R', 'The story of J. Robert Oppenheimer and his role in the development of the atomic bomb.', '2023-07-21', 0),
    (2, 'Her', 'assets/images/her-2013-10.jpg', 'Drama, Romance, Sci-Fi', 126, 'R', 'A lonely writer develops an unlikely relationship with an advanced operating system.', '2013-12-18', 0),
    (3, 'Black Panther', 'assets/images/black-panther.webp', 'Action, Adventure, Sci-Fi', 134, 'PG-13', 'T''Challa, heir to the hidden kingdom of Wakanda, must lead his people into a new future.', '2018-02-16', 0),
    (4, 'Interstellar', 'assets/images/Interstellar.jpg', 'Sci-Fi, Drama', 169, 'PG-13', 'A team of explorers travel through a wormhole in space to ensure humanity''s survival.', '2014-11-07', 0),
    (5, 'Dunkirk', 'assets/images/dunkirk.jpg', 'War, Drama', 106, 'PG-13', 'Allied soldiers from Belgium, the British Empire, and France are surrounded by the German Army and evacuated during a fierce battle in World War II.', '2017-07-21', 0),
    (6, 'John Wick 2', 'assets/images/john-wick-2.jpg', 'Action, Thriller', 122, 'R', 'After returning to the criminal underworld to repay a debt, John Wick discovers that a large bounty has been put on his life.', '2017-02-17', 0),
    (7, 'Aquaman', 'assets/images/aquaman.jpg', 'Action, Adventure, Fantasy', 143, 'PG-13', 'Arthur Curry, the human-born heir to the underwater kingdom of Atlantis, goes on a quest to prevent a war between the worlds of ocean and land.', '2018-12-21', 0),
    (8, 'Thor: Ragnarok', 'assets/images/thor-ragnarok.jpg', 'Action, Adventure, Comedy', 130, 'PG-13', 'Imprisoned on the planet Sakaar, Thor must race against time to return to Asgard and stop Ragnarök, the destruction of his world, at the hands of the powerful and ruthless Hela.', '2017-11-03', 0),
    (9, 'Bumblebee', 'assets/images/bumblebee.jpg', 'Action, Adventure, Sci-Fi', 114, 'PG-13', 'On the run in the year 1987, Bumblebee finds refuge in a junkyard in a small California beach town.', '2018-12-21', 0),
    (10, 'Joker', 'assets/images/Joker.jpeg', 'Crime, Drama, Thriller', 122, 'R', 'In Gotham City, mentally troubled comedian Arthur Fleck is disregarded and mistreated by society. He then embarks on a downward spiral of revolution and bloody crime.', '2019-10-04', 0),
    (11, 'The Batman', 'assets/images/batman.jpg', 'Action, Crime, Drama', 176, 'PG-13', 'Batman is forced to investigate the city''s hidden corruption when a sadistic killer leaves behind a trail of cryptic clues.', '2025-10-03', 1),
    (12, 'Avatar: The Way of Water', 'assets/images/avatar-2.jpg', 'Action, Adventure, Fantasy', 192, 'PG-13', 'Jake Sully and Ney''tiri must leave their home to explore the regions of Pandora.', '2025-12-16', 1),
    (13, 'Avengers: Doomsday', 'assets/images/doom.webp', 'Action, Sci-Fi', 160, 'PG-13', 'The Avengers are reassembled to face their most powerful foe yet, the tyrannical Doctor Doom.', '2026-05-01', 1),
    (15, 'Justice League', 'assets/images/justice-league.jpg', 'Action, Adventure, Fantasy', 120, 'PG-13', 'Fueled by his restored faith in humanity and inspired by Superman''s selfless act, Bruce Wayne enlists the help of his newfound ally, Diana Prince, to face an even greater enemy.', '2026-11-17', 1),
    (16, 'Warcraft 2', 'assets/images/warcraft.jpg', 'Action, Adventure, Fantasy', 130, 'PG-13', 'The peaceful realm of Azeroth stands on the brink of war as its civilization faces a fearsome race of invaders: orc warriors fleeing their dying home to colonize another.', '2026-06-12', 1),
    (17, 'Rampage 2', 'assets/images/rampage.jpg', 'Action, Adventure, Sci-Fi', 107, 'PG-13', 'Primatologist Davis Okoye shares an unshakable bond with George, the extraordinarily intelligent, silverback gorilla who has been in his care since birth. But a rogue genetic experiment gone awry mutates this gentle ape into a raging creature of enormous size.', '2027-04-16', 1),
    (18, 'Black Widow 2', 'assets/images/black-widow.jpg', 'Action, Adventure, Sci-Fi', 134, 'PG-13', 'Natasha Romanoff confronts the darker parts of her ledger when a dangerous conspiracy with ties to her past arises.', '2026-07-09', 1),
    (19, 'Doctor Strange 3', 'assets/images/doctor-strange.jpg', 'Action, Adventure, Fantasy', 148, 'PG-13', 'While on a journey of physical and spiritual healing, a brilliant neurosurgeon is drawn into the world of the mystic arts.', '2028-05-05', 1),
    (20, 'Spider-Man: Beyond the Spider-Verse', 'assets/images/spider.jpg', 'Animation, Action, Adventure', 140, 'PG', 'Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence.', '2028-12-22', 1);";
    if ($conn->query($sql) === TRUE) {
        echo "Movies seeded successfully.\n";
    } else {
        echo "Error seeding movies: " . $conn->error . "\n";
    }
}
?>