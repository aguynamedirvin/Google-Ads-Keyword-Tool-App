<?php
// models/Login.php

class Login {
    private $db;
    private $errors = [];

    public function __construct($db = null) {
        if ($db === null) {
            // The Database class's getConnection() method returns a PDO connection
            $this->db = (new Database())->getConnection();
        } else {
            $this->db = $db;
        }
    }

    public function authenticate($username, $password) {
        // Check if any field is empty
        if (empty($username) || empty($password)) {
            $this->errors[] = 'All fields are required.';
            return ['success' => false, 'errors' => $this->errors];
            //throw new Exception('All fields are required.');
        }

        try {
            // Check user credentials and return user data on success
            // Allow login with either username or email
            $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $username);
            $stmt->execute();

            // Check if user exists
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if password matches
                if (password_verify($password, $user['password'])) {
                    return ['success' => true, 'user' => $user];
                } else {
                    $this->errors[] = 'Incorrect password.';
                    //throw new Exception('Incorrect password.');
                }
            } else {
                $this->errors[] = 'User not found.';
                //throw new Exception('User not found.');
            }
        } catch (PDOException $e) {
            error_log($e->getMessage()); // Log the actual PDO exception message for debugging
            $this->errors[] = 'An error occurred. Please try again later.';
            //throw new Exception('An error occurred. Please try again later.');
        }

        return $this->errors ? ['success' => false, 'errors' => $this->errors] : ['success' => true, 'user' => $user, 'user_id' => $user['user_id']];
    }
}
