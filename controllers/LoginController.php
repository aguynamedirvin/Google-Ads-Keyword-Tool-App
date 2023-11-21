<?php

// controllers/LoginController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Login.php';


class LoginController {
    private $authService;

    public function __construct() {
        $this->authService = new Login();
        $this->user = new User();
    }

    public function index() {

        $user = new User();

        // Check if user is logged in
        if ($user->isLoggedIn()) {
            header('Location: /dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $this->getRequestData();
            $response = $this->authService->authenticate($data['username'], $data['password']);

            if ($response['success']) {
                $user->setSession(['user_id' => $response['user']['user_id'], 'username' => $response['user']['username']]);
                $test = $this->handleSuccessfulLogin($response);
            } else {
                $this->handleFailedLogin($response['errors']);
            }
        } else {
            //echo json_encode(['success' => false, 'errors' => $errors]);
            
        }

        include 'views/auth/login.php';


        /*
        
        $login = new Login();
        
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
                    //header('Location: /dashboard');
                    //exit;
                }
            } catch (Exception $e) {
                // Handle exceptions that are critical errors
                $_SESSION['login_errors'] = 'An unexpected error occurred. Please try again later.';
                // Log the detailed error message for debugging
                error_log($e->getMessage());
            }
        }*/
        
    }

    private function getRequestData() {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $inputJSON = file_get_contents('php://input');
            echo json_decode($inputJSON, true);
        } else {
            return [
                'username' => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING),
                'password' => filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)
            ];
        }
    }

    private function handleSuccessfulLogin($user) {
        // Set session variables
        //$_SESSION['user_id'] = $user;
        //$_SESSION['username'] = $user['username'];
    
        if ($this->isAjaxRequest()) {
            // Return JSON response for AJAX request
            echo json_encode(['success' => true, 'redirect' => '/dashboard']);
        } else {
            // Redirect to the dashboard for normal requests
            header('Location: /dashboard');
        }
        exit;
    }
    
    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    private function handleFailedLogin($errors) {
        if ($this->isAjaxRequest()) {
            // Return JSON response for AJAX request
            echo json_encode(['success' => false, 'errors' => $errors]);
        } else {
            // Set session errors for normal requests
            $_SESSION['login_errors'] = $errors;
            header('Location: /login'); // Redirect back to the login page
        }
        exit;
    }


}

?>
