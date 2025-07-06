<?php
include_once '../config/db.php';

class UserModel {
    private $conn;

    public function __construct() {
        global $conn; // Usa la conexión global definida en db.php
        $this->conn = $conn;
    }

    public function login($username, $password) {
    echo "Debug: UserModel::login() - Username = " . htmlspecialchars($username) . "<br>";
    echo "Debug: Password = " . htmlspecialchars($password) . "<br>";

    $stmt = $this->conn->prepare("SELECT id, username, role, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        echo "Debug: Usuario encontrado en la base de datos.<br>";
        echo "Debug: Hash de contraseña en la BD = " . htmlspecialchars($result['password']) . "<br>";

        // Verificar si hay espacios en blanco u otros caracteres extraños
        echo "Debug: Longitud de la contraseña ingresada = " . strlen($password) . "<br>";
        echo "Debug: Longitud del hash en la BD = " . strlen($result['password']) . "<br>";

        if (password_verify($password, $result['password'])) {
            echo "Debug: Contraseña válida.<br>";
            return $result;
        } else {
            echo "Debug: Contraseña incorrecta o no válida.<br>";
            return false;
        }
    } else {
        echo "Debug: Usuario no encontrado en la base de datos.<br>";
        return false;
    }
}
}
?>