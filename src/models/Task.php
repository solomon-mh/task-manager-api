<?php

namespace Solomon\TaskManagerApiPhp\models;

use PDO;

class Task
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($title, $description, $status = 'pending')
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, description, status) VALUES (:title, :description, :status)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':status' => $status
        ]);
        return $this->db->lastInsertId();
    }

    public function getAll($status = null)
    {
        if ($status) {
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE status = :status");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $this->db->query("SELECT * FROM tasks");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $description, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE tasks 
            SET title = :title, description = :description, status = :status, updated_at = CURRENT_TIMESTAMP 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':description' => $description,
            ':status' => $status
        ]);
    }
}
