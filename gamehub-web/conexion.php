<?php
// Datos de conexión (cuando despliegues en Render, reemplaza estos valores con tus credenciales de la BD)
$host = 'localhost';
$dbname = 'gamehub_db';
$username = 'postgres';
$password = 'tu_contraseña';

try {
    // Creamos la conexión usando la clase PDO
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    // Configuramos para que muestre errores si algo sale mal
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error terrible en la conexión!: " . $e->getMessage());
}
?>