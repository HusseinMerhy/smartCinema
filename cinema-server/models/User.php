<?php
require_once 'Model.php';

class User extends Model {
    protected static string $table = "users";

    public int $id;
    public string $name;
    public string $email;
  
    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
    }

    public static function create(mysqli $mysqli, string $name, string $email, string $password) {
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bind_param("sss", $name, $email, $hashed_password);
        
        return $stmt->execute();
    }
}
?>