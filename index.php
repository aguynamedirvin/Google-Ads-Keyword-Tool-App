<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// index.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/File.php';
require_once __DIR__ . '/models/Keywords.php';

session_start();

// Define routes
$routes = [
    // Home route
    '/' => 'HomeController@index',
    
    // Public routes
    '/login'    => 'LoginController@index',
    
    '/register' => 'RegisterController@index',
    '/register/success' => 'RegisterController@success',

    '/logout'   => 'LogoutController@index',

    // Dashboard routes
    '/dashboard'            => 'DashboardController@index',
    '/dashboard/profile'   => 'ProfileController@index',
    '/dashboard/keywords'       => 'AppKeywordsController@indexView',
    '/dashboard/keywords/upload'   => 'AppKeywordsController@uploadView',
];

// Get the current path from the URL
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Basic routing
if (array_key_exists($path, $routes)) {
    $parts = explode('@', $routes[$path]);
    $controllerName = $parts[0];
    $methodName = $parts[1];

    include "controllers/{$controllerName}.php";
    $controller = new $controllerName();
    $controller->$methodName();

} else {
    // Check if the path starts with '/dashboard'
    if (strpos($path, '/dashboard') === 0) {
        // Redirect to '/dashboard'
        header('Location: /dashboard');
        exit;
    }

    // Handle 404 Not Found
    header("HTTP/1.0 404 Not Found");
    include 'views/public/404.php';
}