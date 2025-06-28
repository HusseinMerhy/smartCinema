<?php
// FILE: cinema-server/models/Movie.php
// PURPOSE: Represents a single movie record from the database.

require_once("Model.php");

class Movie extends Model {

    // --- Class Properties ---
    private int $id;
    private string $title;
    private string $poster_url;
    private ?string $genre; // Can be null

    // Set the table name for the Movie model
    protected static string $table = "movies";

    /**
     * The constructor is called when a new Movie object is created.
     * It populates the object's properties from the data array.
     * @param array $data An associative array of data from the database.
     */
    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->poster_url = $data['poster_url'];
        $this->genre = $data['genre'] ?? null;
    }

    /**
     * Converts the Movie object's properties into an associative array.
     * This is essential for converting the object to JSON.
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'poster_url' => $this->poster_url,
            'genre' => $this->genre,
        ];
    }
}
