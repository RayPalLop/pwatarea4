<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Obtener información del usuario
$username = $_SESSION['username'] ?? 'Usuario';

require_once '../../config/db.php';

$tasks = [];

try {
    // Consultar todas las tareas del usuario actual
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar las tareas: " . $e->getMessage());
}

// Si se envía el formulario de nueva tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (!empty($title)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status, user_id) VALUES (?, ?, 'pendiente', ?)");
            $stmt->execute([$title, $description, $_SESSION['user_id']]);
            
            // Recargar las tareas después de insertar
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al agregar la tarea: " . $e->getMessage());
        }
    }
}

?>

<h2>Bienvenido, <?= $username ?></h2>

<form method="POST">
  <input type="text" name="title" placeholder="Título" required>
  <textarea name="description" placeholder="Descripción"></textarea>
  <button type="submit" name="add_task">Agregar Tarea</button>
</form>

<ul class="task-list">
  <?php foreach ($tasks as $task): ?>
    <li>
      <?= htmlspecialchars($task['title']) ?> 
      <span><?= ucfirst($task['status']) ?></span>
    </li>
  <?php endforeach; ?>
</ul>