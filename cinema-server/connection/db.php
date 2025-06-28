<?php 

$db_host = "localhost";
$db_name = "smart_cinema"; // Ensure this matches your database name
$db_user = "root"; 
$db_pass = null;

$conn= new mysqli($db_host, $db_user, $db_pass, $db_name);
if($conn) {
        echo "Database connection successful.";
}