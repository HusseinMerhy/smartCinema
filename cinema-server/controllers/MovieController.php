<?php
// cinema-server/controllers/MovieController.php

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../models/Movie.php';

class MovieController {
    private $db;
    private $movieModel;

    public function __construct(mysqli $db_connection) {
        $this->db = $db_connection;
        $this->movieModel = new Movie($this->db); 
    }

    public function nowPlaying() {
        $this->sendResponse(['data' => $this->movieModel->get_movies(false)]);
    }

    public function comingSoon() {
        $this->sendResponse(['data' => $this->movieModel->get_movies(true)]);
    }

    public function getMovieById($id) {
        $movie = $this->movieModel->get_movie_by_id($id);
        if ($movie) {
            $this->sendResponse(['data' => $movie]);
        } else {
            $this->sendResponse(['message' => 'Movie not found.'], 404);
        }
    }

    private function sendResponse($payload, $statusCode = 200) {
        if (ob_get_length()) ob_clean();
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit();
    }
}