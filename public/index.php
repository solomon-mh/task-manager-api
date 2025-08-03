<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Solomon\TaskManagerApiPhp\Database\Database;
use Solomon\TaskManagerApiPhp\Controllers\TaskController;

// Set headers for JSON API
header('Content-Type: application/json');

// Get database connection
$db = Database::getInstance()->getConnection();

// Route request
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$method = $_SERVER['REQUEST_METHOD'];
$id = $uri[1] ?? null;

$taskController = new TaskController($db);
$taskController->handleRequest($method, $id);
