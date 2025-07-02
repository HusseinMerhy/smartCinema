<?php
// cinema-server/models/Model.php

abstract class Model {
    protected mysqli $db;
    public array $attributes = [];

    // The constructor now REQUIRES a database connection.
    // This removes the dependency on fragile global variables.
    public function __construct(mysqli $db_connection) {
        $this->db = $db_connection;
    }

    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function toArray(): array {
        return $this->attributes;
    }
}