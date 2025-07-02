<?php
// cinema-server/models/User.php

require_once("Model.php");

class User extends Model {
    protected static string $table = "users";

    public function __construct(mysqli $db_connection, array $data = []) {
        parent::__construct($db_connection);
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }
    
    /**
     * Finds a user by either their email or phone number.
     */
    public static function findByLoginIdentifier(mysqli $db_connection, string $identifier) {
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phoneNumber';
        
        $sql = "SELECT * FROM " . static::$table . " WHERE $field = ?";
        $stmt = $db_connection->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        
        return $data ? new static($db_connection, $data) : null;
    }

   public function save(): bool {
        $sql  = "INSERT INTO users (email, username, phoneNumber, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $role = $this->role ?? 'customer'; // Default role
        $stmt->bind_param("sssss", $this->email, $this->username, $this->phoneNumber, $this->password, $role);
        return $stmt->execute();
    }

    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->password);
    }

    public function toArray(): array {
        $attributes = $this->attributes;
        unset($attributes['password']); // Never send the password hash to the client
        return $attributes;
    }
}
