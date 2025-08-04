<?php

namespace Solomon\TaskManagerApiPhp\Models;

use PDO;

class User
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($username, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([':username' => $username, ':password' => $hash]);
        return $this->db->lastInsertId();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
