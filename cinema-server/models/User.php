<?php
// FILE: cinema-server/models/User.php
// PURPOSE: Handles all database operations related to users.

class User {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    public function findByEmailOrMobile($email, $mobile) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? OR mobile = ?");
        $stmt->bind_param("ss", $email, $mobile);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Returns user data or null
    }

    public function create($name, $email, $mobile, $password) {
        // Securely hash the password before storing it.
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, mobile, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $mobile, $hashedPassword);

        if ($stmt->execute()) {
            return true;
        }
        // Return false if the execution fails for any reason.
        return false;
    }
}
?>