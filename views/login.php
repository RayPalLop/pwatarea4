<?php
// views/login.php
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['user_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$error = '';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? 'password');
    
    if (!empty($username) && $password) {
      
        // Incluir la conexión a la BD
        require_once '../config/db.php';
        
        try {
            // Buscar usuario
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "<!-- Debug: Usuario encontrado = " . ($user ? 'Sí' : 'No') . " -->";

            
            if ($user) {
                echo "<!-- Debug: Verificando password -->";
                if (password_verify($password, $user['password'])) {
                    echo "<!-- Debug: Password correcta, iniciando sesión -->";
                    
                    // Login exitoso
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    echo "<!-- Debug: Sesión iniciada, redirigiendo... -->";
                    
                    // Redirigir al dashboard
                    header('Location: admin/dashboard.php');
                    exit;
                } else {
                    echo "<!-- Debug: Password incorrecta -->";
                    $error = 'Credenciales incorrectas';
                }
            } else {
                echo "<!-- Debug: Usuario no encontrado -->";
                $error = 'Usuario no encontrado';
            }
            
        } catch(PDOException $e) {
            echo "<!-- Debug: Error BD: " . $e->getMessage() . " -->";
            $error = 'Error de conexión a la base de datos';
        }
    } else {
        $error = 'Por favor, complete todos los campos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(45, 43, 120);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #333;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007cba;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #007cba;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #005a8b;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .debug-info {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Iniciar Sesión</h1>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password">
            </div>
            
            <button type="submit" class="btn">Ingresar</button>
        </form>
                
    </div>
</body>
</html>