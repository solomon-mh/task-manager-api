<?php

use Solomon\TaskManagerApiPhp\Database\Database;
use Solomon\TaskManagerApiPhp\Controllers\TaskController;

header('Content-Type: application/json');

$db = Database::getInstance()->getConnection();
$controller = new TaskController($db);

$requestUri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$method = $_SERVER['REQUEST_METHOD'];

if (isset($requestUri[0]) && $requestUri[0] === 'tasks') {
    $id = $requestUri[1] ?? null;
    $controller->handleRequest($method, $id);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
