<?php
function connectDB() {
    $servername = "localhost"; 
    $username = "root";        
    $password = "";            
    $dbname = "smart_cinema"; 

    // Connect directly to the smart_cinema database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(" Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
