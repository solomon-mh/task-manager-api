<?php

namespace Solomon\TaskManagerApiPhp\controllers;

use Solomon\TaskManagerApiPhp\models\Task;

class TaskController
{
    private $task;

    public function __construct($db)
    {
        $this->task = new Task($db);
    }

    public function handleRequest($method, $id = null)
    {
        switch ($method) {
            case 'POST':
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
