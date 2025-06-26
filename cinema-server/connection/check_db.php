<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

$conn = connectDB();

if ($conn) {
    echo " Connected to the smart_cinema database!";
}
?>
