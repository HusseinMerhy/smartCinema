<?php
function seed_movie_trailers(mysqli $conn) {
    echo "Seeding movie trailers...\n";
    $sql = "INSERT INTO movie_trailers (movie_id, trailer_url) VALUES
    (1, 'https://www.youtube.com/watch?v=bK6ldnjE3Y0'), (2, 'https://www.youtube.com/watch?v=WzV6mXIOVl4'),
    (3, 'https://www.youtube.com/watch?v=xjDjI_7sslo'), (4, 'https://www.youtube.com/watch?v=zSWdZVtXT7E'),
    (5, 'https://www.youtube.com/watch?v=F-eMt3SrfFU'), (6, 'https://www.youtube.com/watch?v=XGk2EfbD_Ps'),
    (7, 'https://www.youtube.com/watch?v=WDkg3h8PCVU'), (8, 'https://www.youtube.com/watch?v=ue80QwXMRHg'),
    (9, 'https://www.youtube.com/watch?v=lcwmDAYt22k'), (10, 'https://www.youtube.com/watch?v=zAGVQLHvwOY'),
    (11, 'https://www.youtube.com/watch?v=mqqft2x_Aa4'), (12, 'https://www.youtube.com/watch?v=d9MyW72ELq0'),
    (13, 'https://www.youtube.com/watch?v=73_1biK-w2g'),
    (15, 'https://www.youtube.com/watch?v=3cxixDgHUYw'), (16, 'https://www.youtube.com/watch?v=2RxSzdQwzZg'),
    (17, 'https://www.youtube.com/watch?v=coOKvrsmQiI'), (18, 'https://www.youtube.com/watch?v=Fp9pNPdNwjI'),
    (19, 'https://www.youtube.com/watch?v=aWzlQ2N6qqg'), (20, 'https://www.youtube.com/watch?v=cqGjhVJWtEg');";
    if ($conn->query($sql) === TRUE) {
        echo "Movie trailers seeded successfully.\n";
    } else {
        echo "Error seeding movie trailers: " . $conn->error . "\n";
    }
}
?>