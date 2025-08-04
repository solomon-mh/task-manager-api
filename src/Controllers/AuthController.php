<?php

namespace Solomon\TaskManagerApiPhp\Controllers;




use Solomon\TaskManagerApiPhp\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController
{
    private $user;
    private $secret;

    public function __construct($db)
    {
        $this->user = new User($db);
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password are required']);
            return;
        }
        try {
            $this->user->create($data['username'], $data['password']);
            echo json_encode(['message' => 'User registered']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => 'Username already exists']);
        }
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password are required']);
            return;
        }
        $user = $this->user->findByUsername($data['username']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }
        $payload = [
            "iss" => "task-manager-api",
            "sub" => $user['id'],
            "username" => $user['username'],
            "iat" => time(),
            "exp" => time() + (60 * 60) // 1 hour expiration
        ];
        $jwt = JWT::encode($payload, $this->secret, 'HS256');
        echo json_encode(['token' => $jwt]);
    }

    public function verifyToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}
