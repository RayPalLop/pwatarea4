<?php
// public/reset_password.php
require_once '../config/db.php';

// Nueva contraseña
$nueva_contraseña = 'password';
$hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

try {
    // Actualizar contraseña del usuario admin
    $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hash]);
    
    echo "Contraseña actualizada exitosamente para el usuario 'admin'<br>";
    echo "Nueva contraseña: " . $nueva_contraseña . "<br>";
    echo "Hash generado: " . $hash . "<br>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>