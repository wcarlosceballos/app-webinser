<?php
require 'db_connection.php';

$username = 'admin';
$password = password_hash('admin', PASSWORD_DEFAULT); // Encriptar contraseña
$role = 'admin';

// Insertar usuario administrador
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':role', $role);

if ($stmt->execute()) {
    echo "Usuario administrador insertado correctamente.";
} else {
    echo "Error al insertar usuario administrador.";
}
?>
