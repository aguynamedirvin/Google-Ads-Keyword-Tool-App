<?php
// models/User.php

class User {
    private $db;
    private $userId;

    public function __construct() {
        $this->db = new Database();
        $this->userId = $_SESSION['user_id'] ?? null;
    }

    /**
     * Initialize the user session with given user data.
     * 
     * @param array $userData Associative array of user data.
     * @return void
     */
    public function initializeSession($userData) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        foreach ($userData as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public function setSession($userData) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Assuming $userData is an associative array of user data
        foreach ($userData as $key => $value) {
            $_SESSION[$key] = $value;
        }

        return true;
    }

    public function getSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION;
    }

    public function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user session is set (modify according to your application logic)
        if (isset($_SESSION['user_id'])) {
            // Set the user ID for use in other methods
            $this->userId = $_SESSION['user_id'];
            return true;
        } else {
            return false;
        }
        //return isset($_SESSION['user_id']);
    }

    /*public function register($username, $password, $email, $firstName, $lastName) {
        // Hash the password and prepare SQL for user registration
        // Return true on success or false on failure
        echo 'Registered';
        exit;
    }

    public function authenticate($username, $password) {
        // Check user credentials and return user data on success
        // Return false on failure
    }*/

    public function firstName() {
        if ($this->userId) {
            return $this->getUserDetail('first_name');
        }
        return null;
    }

    public function lastName() {
        if ($this->userId) {
            return $this->getUserDetail('last_name');
        }
        return null;
    }

    public function email() {
        if ($this->userId) {
            return $this->getUserDetail('email');
        }
        return null;
    }

    public function username() {
        if ($this->userId) {
            return $this->getUserDetail('username');
        }
        return null;
    }

    public function id() {
        if ($this->userId) {
            return $this->getUserDetail('user_id');
        }
        return null;
    }

    /**
     * Update the user's email.
     * 
     * @param string $newEmail The new email.
     * @return bool True on success or false on failure.
     */
    public function updateEmail($newEmail) {
        if (!$this->userId) {
            return false;
        }
        if (!$newEmail) {
            return false;
        }

        // Check if the email is unique
        if (!$this->isEmailUnique($newEmail)) {
            $this->errors[] = 'Email already registered.';
            return false;
        }

        try {
            // Update the email for the user
            $updateStmt = $this->db->getConnection()->prepare("UPDATE users SET email = ? WHERE user_id = ?");
            $updateStmt->bindValue(1, $newEmail, PDO::PARAM_STR);
            $updateStmt->bindValue(2, $this->userId, PDO::PARAM_INT);
            $updateStmt->execute();

            return true;
        } catch (PDOException $e) {
            // Log the error for internal review
            error_log('Error updating email:' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if the email is unique.
     * 
     * @param string $email The email to check.
     * @return bool True if the email is unique, false otherwise.
     */
    public function isEmailUnique($email) {
        try {
            $stmt = $this->db->getConnection()->prepare("SELECT user_id  WHERE email = ?");
            $stmt->bindValue(1, $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() === 0;
        } catch (PDOException $e) {
            // Log the error for internal review
            error_log('Error checking if email is unique:' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the user's photo URL.
     * 
     * @return string|null The user's photo URL or null if the user is not logged in.
     */
    public function avatar() {
        if ($this->userId) {
            return $this->getUserDetail('photo_url');
        }
        return null;
    }

    /**
     * Update the user's photo URL.
     * 
     * @param string $photoUrl The new photo URL.
     * @return bool True on success or false on failure.
     */
    public function updateAvatar($fileName) {
        if (!$this->userId) {
            return ['success' => false, 'message' => 'User not identified.'];
        }

        if (!$fileName) {
            return ['success' => false, 'message' => 'Photo URL is required.'];
        }

        try {
            // Update the photo_url for the user
            $updateStmt = $this->db->getConnection()->prepare("UPDATE users SET photo_url = ? WHERE user_id = ?");
            $updateStmt->bindValue(1, $fileName, PDO::PARAM_STR);
            $updateStmt->bindValue(2, $this->userId, PDO::PARAM_INT);
            $updateStmt->execute();

            return ['success' => true, 'message' => 'Photo URL updated successfully.'];

        } catch (PDOException $e) {
            // Log the error for internal review
            error_log('Error updating photo URL:' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update photo URL.'];
        }
    }

    // Update the user's password
    public function updatePassword($currentPassword, $password, $confirmPassword) {
        if (!$this->userId) {
            return ['success' => false, 'message' => 'User not identified.'];
        }

        if ($password != $confirmPassword) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        try {
            $smtm = $this->db->getConnection()->prepare("SELECT password FROM users WHERE user_id = ?");
            $stmt->bindValue(1, $this->userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if the current password is correct
            if ($result && password_verify($currentPassword, $result['password'])) {
                
                // Proceed with updating the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $this->db->getConnection()->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $stmt->bindValue(1, $hashedPassword, PDO::PARAM_STR);
                $stmt->bindValue(2, $this->userId, PDO::PARAM_INT);
                $stmt->execute();

                return ['success' => true, 'message' => 'Password updated successfully.'];
            } else {
                return ['success' => false, 'message' => 'Current password is incorrect.'];
            }

        } catch (PDOException $e) {
            // Log the error for internal review
            error_log('Error updating password:' . $e->getMessage());
            return ['success' => false, 'message' => 'Error updating password.'];
        }

        return false;
    }

    // Return the user's data for the given field
    private function getUserDetail($field) {
        $stmt = $this->db->getConnection()->prepare("SELECT $field FROM users WHERE user_id = ?");
        $stmt->bindValue(1, $this->userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result[$field];
        }
        return null;
    }

    // Log the user out
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all session variables related to the user
        $_SESSION = array();

        // Completely destroy the session, uncomment the following lines:
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        return true;
    }
}
