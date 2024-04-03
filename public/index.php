<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Messages.php';
require_once __DIR__ . '/../config/Constants.php';
require_once __DIR__ . '/../global/interceptor.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


// Create a new database connection
$dbConfig = [
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD']
];

$database = new Database($dbConfig['host'], $dbConfig['database'], $dbConfig['username'], $dbConfig['password']);
$database->connect();
$conn = $database->getConnection();

// Parse the request
$request = strtok($_SERVER['REQUEST_URI'], '?');

// Route the request
switch ($request) {
    case Constants::API_ROUTES['BASE']:
        interceptEcho(Messages::SERVER_STATUS_SUCCESS);
        break;

        // Handle user routes    
    case Constants::API_ROUTES['USER_LIST']:
    case Constants::API_ROUTES['USER']:
        require_once __DIR__ . '/../routes/user.php';
        break;

    default:
        interceptEcho(Messages::ROUTE_NOT_FOUND, 404, null, Messages::ROUTE_NOT_FOUND);
        break;
}
