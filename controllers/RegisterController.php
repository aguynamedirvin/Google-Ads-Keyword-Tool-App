<?php

// controllers/RegisterController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Register.php';

class RegisterController {
    
    private $registerService;

    public function __construct() {
        $this->registerService = new Registration();
        $this->user = new User();
    }


    public function index() {

        $user = new User();
        
        // Check if user is logged in
        if ($user->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            
            $data = $this->getRequestData();

            $username = $data['username'];
            $password = $data['password'];
            $confirmPassword = $data['confirmPassword'];
            $email = $data['email'];
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];

            $response = $this->registerService->register($username, $password, $confirmPassword, $email, $firstName, $lastName);

            if ($response['success']) {
                $this->handleSuccessfulRegistration($response);
            } else {
                $this->handleFailedRegistration($response['errors']);
            }

            /**$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
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

            }**/
        } else {
            //echo json_encode(['success' => false, 'errors' => $errors]);
        }
        
        include 'views/auth/register.php';

    }

    private function getRequestData() {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $json = file_get_contents('php://input');
            return json_decode($json, true);
        } else {
            return [
                'username' => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING),
                'password' => filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING),
                'confirmPassword' => filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_STRING),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING),
                'firstName' => filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING),
                'lastName' => filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING)
            ];
        }
    }

    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    private function handleFailedRegistration($errors) {
        if ($this->isAjaxRequest()) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        } else {
            $_SESSION['registration_errors'] = $errors;
            header('Location: /register');
        }
    }

    private function handleSuccessfulRegistration($user) {
        // Set session variables
        //$_SESSION['user_id'] = $user;
        //$_SESSION['username'] = $user['username'];
    
        if ($this->isAjaxRequest()) {
            // Return JSON response for AJAX request
            echo json_encode(['success' => true, 'redirect' => '/login']);
        } else {
            // Redirect to the logind for normal requests
            header('Location: /login');
        }
        exit;
    }

    public function success() {
        include 'views/auth/register_success.php';
    }
}
