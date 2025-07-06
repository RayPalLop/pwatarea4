<?php
// public/debug.php
echo "<h2>Diagnóstico del Sistema</h2>";

// 1. Verificar conexión a la base de datos
echo "<h3>1. Verificando conexión a la base de datos...</h3>";
try {
    require_once '../config/db.php';
    echo "✅ Conexión a BD exitosa<br>";
    
    // 2. Verificar si existe la tabla users
    echo "<h3>2. Verificando tabla users...</h3>";
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabla 'users' existe<br>";
        
        // 3. Mostrar estructura de la tabla
        echo "<h3>3. Estructura de la tabla users:</h3>";
        $stmt = $pdo->query("DESCRIBE users");
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // 4. Mostrar usuarios existentes
        echo "<h3>4. Usuarios en la base de datos:</h3>";
        $stmt = $pdo->query("SELECT id, username, password, role FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($users) > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Username</th><th>Password Hash</th><th>Role</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td>" . substr($user['password'], 0, 30) . "...</td>";
                echo "<td>" . $user['role'] . "</td>";
                echo "</tr>";
            }
            echo "</table><br>";
        } else {
            echo "❌ No hay usuarios en la tabla<br>";
        }
        
    } else {
        echo "❌ Tabla 'users' no existe<br>";
    }
    
} catch(PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}

// 5. Botón para crear usuario admin
echo "<h3>5. Crear usuario admin:</h3>";
if (isset($_POST['crear_admin'])) {
    try {
        $username = 'admin';
        $password = 'password';
        $role = 'admin';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            // Actualizar usuario existente
            $stmt = $pdo->prepare("UPDATE users SET password = ?, role = ? WHERE username = ?");
            $stmt->execute([$hash, $role, $username]);
            echo "✅ Usuario admin actualizado exitosamente<br>";
        } else {
            // Crear nuevo usuario
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $role]);
            echo "✅ Usuario admin creado exitosamente<br>";
        }
        
        echo "<strong>Credenciales:</strong><br>";
        echo "Username: <strong>admin</strong><br>";
        echo "Password: <strong>password</strong><br>";
        echo "Role: <strong>admin</strong><br>";
        echo "Hash generado: <code>" . $hash . "</code><br>";
        
    } catch(PDOException $e) {
        echo "❌ Error al crear usuario: " . $e->getMessage() . "<br>";
    }
}

// 6. Botón para probar login
echo "<h3>6. Probar login:</h3>";
if (isset($_POST['probar_login'])) {
    $test_username = $_POST['test_username'] ?? '';
    $test_password = $_POST['test_password'] ?? '';
    
    if (!empty($test_username) && !empty($test_password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$test_username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                echo "✅ Usuario encontrado<br>";
                echo "Username: " . $user['username'] . "<br>";
                echo "Role: " . $user['role'] . "<br>";
                echo "Hash en BD: " . $user['password'] . "<br>";
                echo "Password ingresada: " . $test_password . "<br>";
                
                if (password_verify($test_password, $user['password'])) {
                    echo "✅ <strong>LOGIN EXITOSO</strong><br>";
                } else {
                    echo "❌ <strong>PASSWORD INCORRECTA</strong><br>";
                }
            } else {
                echo "❌ Usuario no encontrado<br>";
            }
            
        } catch(PDOException $e) {
            echo "❌ Error: " . $e->getMessage() . "<br>";
        }
    }
}

if (isset($pdo)) {
    echo "<form method='POST' style='margin: 10px 0;'>";
    echo "<input type='submit' name='crear_admin' value='Crear/Actualizar Usuario Admin' style='padding: 8px 16px; background: #007cba; color: white; border: none; border-radius: 4px;'>";
    echo "</form>";
    
    echo "<form method='POST' style='margin: 10px 0;'>";
    echo "Username: <input type='text' name='test_username' value='admin' style='padding: 4px; margin: 0 5px;'><br><br>";
    echo "Password: <input type='text' name='test_password' value='password' style='padding: 4px; margin: 0 5px;'><br><br>";
    echo "<input type='submit' name='probar_login' value='Probar Login' style='padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px;'>";
    echo "</form>";
}
?>