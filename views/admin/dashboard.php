<?php
// views/admin/dashboard.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Obtener información del usuario
$username = $_SESSION['username'] ?? 'Usuario';
$role = $_SESSION['role'] ?? 'user';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #007cba;
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .welcome-card h1 {
            color: #333;
            margin: 0 0 1rem 0;
        }
        .stats-grid {

            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            color: #007cba;
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
        }
        .stat-card p {
            color: #666;
            margin: 0;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .action-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .action-card h3 {
            color: #333;
            margin: 0 0 1rem 0;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #007cba;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .btn:hover {
            background-color: #005a8b;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">Sistema de Tareas</div>
            <div class="user-info">
                <span>Bienvenido, <?php echo htmlspecialchars($username); ?></span>
                <span class="badge"><?php echo htmlspecialchars($role); ?></span>
                <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="welcome-card">
            <h1>¡Bienvenido al Dashboard!</h1>
            <p>Has iniciado sesión exitosamente como <strong><?php echo htmlspecialchars($username); ?></strong></p>
            <p>Tu rol actual es: <strong><?php echo htmlspecialchars($role); ?></strong></p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>5</h3>
                <p>Tareas Pendientes</p>
            </div>
            <div class="stat-card">
                <h3>12</h3>
                <p>Tareas Completadas</p>
            </div>
            <div class="stat-card">
                <h3>3</h3>
                <p>Tareas En Progreso</p>
            </div>
            <div class="stat-card">
                <h3>20</h3>
                <p>Total de Tareas</p>
            </div>
        </div>

        <div class="actions-grid">
            <div class="action-card">
                <h3>Gestión de Tareas</h3>
                <p>Administra las tareas del sistema</p>
                <a href="../tareas/dashboard.php" class="btn btn-success">Ver Tareas</a>
                <a href="#" class="btn">Nueva Tarea</a>
            </div>
            
            <div class="action-card">
                <h3>Usuarios</h3>
                <p>Gestiona los usuarios del sistema</p>
                <a href="#" class="btn">Ver Usuarios</a>
                <a href="#" class="btn btn-warning">Nuevo Usuario</a>
            </div>
            
            <div class="action-card">
                <h3>Reportes</h3>
                <p>Visualiza estadísticas y reportes</p>
                <a href="#" class="btn">Ver Reportes</a>
                <a href="#" class="btn">Exportar Datos</a>
            </div>
        </div>
    </div>
</body>
</html>