<?php

// controllers/RegisterController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Register.php';

class RegisterController {
    public function index() {

        $user = new User();

        // Check if user is logged in
        if ($user->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $confirmPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);

            // Create a new Registration object
            $newRegistration = new Registration();

            try {
                // Register the user
                $registration = $newRegistration->register($username, $password, $confirmPassword, $email, $firstName, $lastName);

                if ($registration['success']) {
                    header ('Location: /login');
                    exit;
                } else {
                    // Handle errors
                    $_SESSION['registration_errors'] = $registration['errors'];
                }

            } catch (Exception $e) {
                // Handle unexpected errors
                $_SESSION['registration_errors'] = ['An unexpected error occurred. Please try again later.' . $e->getMessage()];

            }
        }
        
        include 'views/auth/register.php';

    }

    public function success() {
        include 'views/auth/register_success.php';
    }
}
