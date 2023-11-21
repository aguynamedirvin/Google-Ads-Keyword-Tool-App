<?php
// db.php

//include_once 'config.php';

class Database {
    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME, Config::DB_USER, Config::DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Method to get the database connection
    public function getConnection() {
        return $this->connection;
    }

    // Other database interaction methods...
}
