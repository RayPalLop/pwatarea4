<?php
session_start();
include_once '../models/TaskModel.php';
include_once '../models/UserModel.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit;
}

$taskModel = new TaskModel();
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $taskModel->addTask($title, $desc, $user['id']);
}

if (isset($_GET['delete'])) {
    $taskModel->deleteTask($_GET['delete']);
}

if (isset($_POST['update_status'])) {
    $taskModel->updateStatus($_POST['task_id'], $_POST['status']);
}

$tasks = $taskModel->getAllTasks($user['id'], $user['role']);
include "../views/{$user['role']}/dashboard.php";