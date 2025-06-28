<?php
// FILE: cinema-server/models/Model.php
// PURPOSE: The abstract base Model that all other models will extend.

abstract class Model {

    // The database table name. This will be defined in child classes (e.g., 'users', 'movies').
    protected static string $table;

    // The primary key column name for the table.
    protected static string $primary_key = "id";

    /**
     * Finds a single record by its primary key.
     * @param mysqli $mysqli The database connection object.
     * @param int $id The ID of the record to find.
     * @return static|null An instance of the calling class (e.g., User, Movie) or null if not found.
     */
    public static function find(mysqli $mysqli, int $id) {
        $sql = sprintf("SELECT * FROM %s WHERE %s = ?", static::$table, static::$primary_key);
        
        $query = $mysqli->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();

        $data = $query->get_result()->fetch_assoc();

        // If data is found, create a new instance of the specific model (e.g., new Movie($data))
        return $data ? new static($data) : null;
    }

    /**
     * Retrieves all records from the model's table.
     * @param mysqli $mysqli The database connection object.
     * @return array An array of model objects (e.g., an array of Movie objects).
     */
    public static function all(mysqli $mysqli): array {
        $sql = sprintf("SELECT * FROM %s", static::$table);
        
        $query = $mysqli->prepare($sql);
        $query->execute();

        $result = $query->get_result();

        $objects = [];
        // Loop through each row from the database result
        while ($row = $result->fetch_assoc()) {
            // Create a new object of the specific model type and add it to the array
            $objects[] = new static($row);
        }

        return $objects;
    }

    /**
     * Converts the object's properties to an array.
     * This method must be implemented by each child class.
     */
    abstract public function toArray(): array;
}
