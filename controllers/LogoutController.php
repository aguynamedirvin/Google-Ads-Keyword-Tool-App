<?php
// controllers/LogoutController.php

require_once __DIR__ . '/../models/User.php';

class LogoutController {
    public function index() {
        // Log the user out
        $user = new User();
        $user->logout();

        // Redirect to login page
        header('Location: /login');
        exit();
    }
}

