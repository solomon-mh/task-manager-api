<?php

namespace Solomon\TaskManagerApiPhp\Controllers;

use Solomon\TaskManagerApiPhp\Models\Task;
use Solomon\TaskManagerApiPhp\Controllers\AuthController; // Import AuthController

class TaskController
{
    private $task;
    private $auth;

    public function __construct($db)
    {
        $this->task = new Task($db);
        $this->auth = new AuthController($db); // Initialize AuthController
    }

    /**
     * Check JWT token from Authorization header
     */
    private function authorize()
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authorization header missing']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $decoded = $this->auth->verifyToken($token);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }

        return $decoded; // You can use decoded user data if needed
    }

    public function handleRequest($method, $id = null)
    {
        switch ($method) {
            case 'POST':
                // Require authentication for creating tasks
                $this->authorize();

                $data = json_decode(file_get_contents("php://input"), true);
                if (!isset($data['title']) || !isset($data['description'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Title and description are required']);
                    return;
                }
                $taskId = $this->task->create($data['title'], $data['description'], $data['status'] ?? 'pending');
                echo json_encode(['message' => 'Task created', 'id' => $taskId]);
                break;

            case 'GET':
                // GET can be public, no auth required
                if ($id) {
                    $task = $this->task->getById($id);
                    if ($task) {
                        echo json_encode($task);
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'Task not found']);
                    }
                } else {
                    $status = $_GET['status'] ?? null;
                    echo json_encode($this->task->getAll($status));
                }
                break;

            case 'PUT':
                // Require authentication for updating tasks
                $this->authorize();

                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Task ID required']);
                    return;
                }
                $data = json_decode(file_get_contents("php://input"), true);
                if (!isset($data['title']) || !isset($data['description']) || !isset($data['status'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Title, description and status are required']);
                    return;
                }
                $updated = $this->task->update($id, $data['title'], $data['description'], $data['status']);
                echo json_encode(['message' => $updated ? 'Task updated' : 'Task not found']);
                break;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    }
}
