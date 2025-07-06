<?php
include_once '../config/db.php';

class TaskModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "pwatarea4");
    }

    public function getAllTasks($user_id, $role) {
        if ($role === 'admin') {
            $stmt = $this->conn->prepare("SELECT * FROM tasks");
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addTask($title, $description, $user_id) {
        $stmt = $this->conn->prepare("INSERT INTO tasks (title, description, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $description, $user_id);
        return $stmt->execute();
    }

    public function deleteTask($id) {
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
?>