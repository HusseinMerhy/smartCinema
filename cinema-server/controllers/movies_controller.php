<?php
// FILE: cinema-server/controllers/MovieController.php
// PURPOSE: Handles API requests related to movies.

// --- Allow Cross-Origin Requests ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// --- Include Dependencies ---
require_once __DIR__ . "/../models/Movie.php";
require_once __DIR__ . "/../connection/connection.php"; // Use the new connection file

// --- Main Logic ---

// Get all movies
$moviesFromDB = Movie::all($mysqli); // This returns an array of Movie OBJECTS.

$response = [];
// We must loop through the objects and convert each one to an array.
// json_encode cannot read the private properties of the objects directly.
foreach ($moviesFromDB as $movieObject) {
    $response[] = $movieObject->toArray();
}

// --- Send Response ---
// Set the HTTP response code to 200 (OK)
http_response_code(200);

// Encode the final array of arrays into a JSON string and send it.
echo json_encode($response);
