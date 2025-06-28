<?php
// The "why": This line is essential. It loads your database connection file,
// allowing this script to communicate with your MySQL database.
require_once __DIR__ . '/../connection/db.php';

// The "why": This is the data we've carefully extracted from your index.html file.
// We've structured it as a PHP array, where each element is an associative array
// representing one movie. This organized structure makes it easy for our script
// to loop through and process each movie. I've added placeholder data for
// fields like 'trailer_url', 'duration_minutes', 'age_rating', and 'description'
// since this information wasn't in your HTML. You can change these values later!
$movies = [
    // Movies from "Opening This Week" section
    ['title' => 'Venom', 'poster_url' => 'assets/images/Joker.jpeg', 'genre' => 'Action'],
    ['title' => 'Dunkirk', 'poster_url' => 'assets/images/dunkirk.jpg', 'genre' => 'Adventure'],
    ['title' => 'Batman Vs Superman', 'poster_url' => 'assets/images/batman-vs-superman.jpg', 'genre' => 'Thriller'],
    ['title' => 'John Wick 2', 'poster_url' => 'assets/images/john-wick-2.jpg', 'genre' => 'Adventure'],
    ['title' => 'Aquaman', 'poster_url' => 'assets/images/aquaman.jpg', 'genre' => 'Action'],
    ['title' => 'Black Panther', 'poster_url' => 'assets/images/black-panther.jpg', 'genre' => 'Thriller'],
    ['title' => 'Thor', 'poster_url' => 'assets/images/thor.jpg', 'genre' => 'Adventure'],
    ['title' => 'Bumblebee', 'poster_url' => 'assets/images/bumblebee.jpg', 'genre' => 'Thriller'],

    // Movies from the "Coming Soon" section
    ['title' => 'Avengers', 'poster_url' => 'assets/images/doom.webp', 'genre' => 'Action'],
    ['title' => 'Inception', 'poster_url' => 'assets/images/inception.jpg', 'genre' => 'Sci-Fi'],
    ['title' => 'The Batman', 'poster_url' => 'assets/images/batman.jpg', 'genre' => 'Crime'],
    ['title' => 'Interstellar', 'poster_url' => 'assets/images/interstellar.jpg', 'genre' => 'Sci-Fi'],
    ['title' => 'Doctor Strange', 'poster_url' => 'assets/images/doctor-strange.jpg', 'genre' => 'Fantasy'],
    ['title' => 'Spider-Man: NWH', 'poster_url' => 'assets/images/spiderman.jpg', 'genre' => 'Action'],
    ['title' => 'Avatar 2', 'poster_url' => 'assets/images/avatar-2.jpg', 'genre' => 'Sci-Fi'],
    ['title' => 'John Wick 4', 'poster_url' => 'assets/images/john-wick-4.jpg', 'genre' => 'Action'],
    ['title' => 'Joker 2', 'poster_url' => 'assets/images/joker1.avif', 'genre' => 'Crime']
];

// The "why": Using a prepared statement is the most secure way to insert data.
// It separates the SQL command from the data itself, which prevents a type of
// attack called SQL injection. The question marks (?) are placeholders for the data.
// Notice the columns match your 'movies' table structure.
$sql = "INSERT INTO movies (title, poster_url, genre, trailer_url, duration_minutes, age_rating, description) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
// The "why": We need to execute the insert command for every movie in our array.
// A 'foreach' loop is the perfect tool for this. It goes through the $movies array
// one item at a time.
foreach ($movies as $movie) {
    // The "why": Here, we are defining some default values for the columns that
    // were not available in your HTML. This ensures that every row we insert into
    // the database has a value for these columns, preventing errors.
    $default_trailer = 'https://www.youtube.com/watch?v=U2Qp5pL3ovA';
    $default_duration = 120;
    $default_rating = 'PG-13';
    $default_description = 'Description not available.';

    // The "why": The 'bind_param' function securely attaches the actual data values
    // to the placeholders in the SQL statement. The first argument "ssssiss" tells
    // the database the data type for each placeholder, in order:
    // s = string, i = integer
    $stmt->bind_param(
        "ssssiss",
        $movie['title'],
        $movie['poster_url'],
        $movie['genre'],
        $default_trailer,
        $default_duration,
        $default_rating,
        $default_description
    );

    // This line executes the prepared statement, inserting the current movie into the database.
    $stmt->execute();
}

// The "why": This provides clear feedback. When you run the script, seeing this
// message confirms that the code ran without crashing.
echo "Successfully seeded " . count($movies) . " movies from your index.html file into the database!";

// The "why": It's good practice to close the statement and the database connection
// to free up resources on the server.
$stmt->close();
$conn->close();
?>