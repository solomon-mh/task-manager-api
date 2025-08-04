<?php

use Solomon\TaskManagerApiPhp\Database\Database;
use Solomon\TaskManagerApiPhp\Controllers\AuthController;


$db = Database::getInstance()->getConnection();
$controller = new AuthController($db);

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

if (isset($uri[0]) && $uri[0] === 'auth') {
    if ($method === 'POST' && isset($uri[1]) && $uri[1] === 'register') {
        $controller->register();
    } elseif ($method === 'POST' && isset($uri[1]) && $uri[1] === 'login') {
        $controller->login();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}
