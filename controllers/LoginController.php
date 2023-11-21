<?php

// controllers/LoginController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Login.php';

class LoginController {
    public function index() {

        $user = new User();
        $login = new Login((new Database())->getConnection());

        // Check if user is logged in
        if ($user->isLoggedIn()) {
            header('Location: /dashboard');
        }

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            try {
                // Get user input from form...
                $response = $login->authenticate($username, $password);
                
                if (!$response['success']) {
                    $_SESSION['login_errors'] = $response['errors'];
                } else {
                    // Successful login
                    $user->setSession(['user_id' => $response['user']['user_id'], 'username' => $response['user']['username']]);
                    header('Location: /dashboard');
                    exit;
                }
            } catch (Exception $e) {
                // Handle exceptions that are critical errors
                $_SESSION['login_errors'] = 'An unexpected error occurred. Please try again later.';
                // Log the detailed error message for debugging
                error_log($e->getMessage());
            }
        }

        include 'views/auth/login.php';
        
    }
}
