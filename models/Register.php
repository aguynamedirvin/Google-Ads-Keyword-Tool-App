<?php
// models/Register.php

class Registration {
    private $db;
    private $errors = [];

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Register a new user
     */
    public function register($username, $password, $confirmPassword, $email, $firstName, $lastName) {

        // Validate user input
        $this->validateUsername($username);
        $this->validatePassword($password);
        $this->validatePasswordMatch($password, $confirmPassword);
        $this->validateEmail($email);
        $this->validateName($firstName, 'First Name');
        $this->validateName($lastName, 'Last Name');

        // If there are no errors, insert the user into the database
        if (empty($this->errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $this->db->getConnection()->prepare("INSERT INTO users (username, password, email, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $username);
                $stmt->bindValue(2, $hashedPassword);
                $stmt->bindValue(3, $email);
                $stmt->bindValue(4, $firstName);
                $stmt->bindValue(5, $lastName);
                $stmt->execute();

                // Check if the insertion was successful
                if ($stmt->rowCount() > 0) {
                    return ['success' => true, 'messages' => ['Registration successful']];
                } else {
                    $this->errors[] = 'Registration failed. Please try again.';
                    return ['success' => false, 'errors' => $this->errors];
                }
            } catch (PDOException $e) {
                // Handle any PDO exceptions that occur

                // Logging the error for internal use
                error_log('Database error in Registration: ' . $e->getMessage());

                // Throwing a generic exception for the controller to catch
                throw new Exception('An unexpected error occurred during registration.');
                //throw exception('Database error: ' . $e->getMessage());
            }
        } else {
            return ['success' => false, 'errors' => $this->errors];
        }
    }

    // Validate name
    private function validateName($name, $fieldName) {
        if (empty($name)) {
            $this->errors[] = "$fieldName is required.";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $this->errors[] = "Only letters spaces allowed for $fieldName.";
        }
    }

    // Validate username
    private function validateUsername($username) {
        if (empty($username)) {
            $this->errors[] = 'Username is required.';
        } elseif (strlen($username) < 5) {
            $this->errors[] = 'Username must be at least 5 characters long.';
        }

        // Check if username already exists
        if (!$this->isUsernameUnique($username)) {
            $this->errors[] = 'Username already taken.';
        }
    }

    // Validate password
    private function validatePassword($password) {
        if (empty($password)) {
            $this->errors[] = 'Password is required.';
        } /*elseif (strlen($password) < 8) {
            $this->errors[] = 'Password must be at least 8 characters long.';
        }*/
    }

    // Validate password match
    private function validatePasswordMatch($password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            $this->errors[] = 'Passwords do not match.';
        }
    }

    // Validate email
    private function validateEmail($email) {
        if (empty($email)) {
            $this->errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Invalid email format.';
        }

        // Check if email already exists
        if (!$this->isEmailUnique($email)) {
            $this->errors[] = 'Email already registered.';
        }
    }

    // Check if username already exists
    private function isUsernameUnique($username) {
        $stmt = $this->db->getConnection()->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bindValue(1, $username);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }

    // Check if email already exists
    private function isEmailUnique($email) {
        $stmt = $this->db->getConnection()->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
}