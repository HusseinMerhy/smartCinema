<?php
// cinema-server/models/Movie.php

require_once __DIR__ . '/Model.php';

class Movie extends Model {
    protected static string $table = 'movies';
    // The constructor now passes the DB connection up to the parent Model.
    public function __construct(mysqli $db_connection, array $data = []) {
        parent::__construct($db_connection);
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function get_movies($is_coming_soon) {
        $sql = "SELECT * FROM " . self::$table . " WHERE is_coming_soon = ?";
        $stmt = $this->db->prepare($sql);
        $is_coming_soon_int = (int)$is_coming_soon;
        $stmt->bind_param("i", $is_coming_soon_int);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_movie_by_id($id) {
        $sql = "SELECT m.*, AVG(r.rating) as average_rating FROM " . self::$table . " m LEFT JOIN movie_ratings r ON m.id = r.movie_id WHERE m.id = ? GROUP BY m.id";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $movie = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($movie) {
            $cast_stmt = $this->db->prepare("SELECT actor_name, character_name FROM movie_casts WHERE movie_id = ?");
            $cast_stmt->bind_param("i", $id);
            $cast_stmt->execute();
            $movie['cast'] = $cast_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $cast_stmt->close();

            $trailer_stmt = $this->db->prepare("SELECT trailer_url FROM movie_trailers WHERE movie_id = ?");
            $trailer_stmt->bind_param("i", $id);
            $trailer_stmt->execute();
            $movie['trailers'] = $trailer_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $trailer_stmt->close();
        }
        return $movie;
    }
}