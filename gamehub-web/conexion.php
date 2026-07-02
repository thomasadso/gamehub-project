<?php
// Datos de conexión (cuando despliegues en Render, reemplaza estos valores con tus credenciales de la BD)
$host = 'dpg-d939pv67r5hc73949tn0-a';
$dbname = 'gamehub_fo67';
$username = 'gamehub_fo67_user';
$password = 'nEvPVpAVt7Kb6KEqL7BP8cEFn4dWK61S';

try {
    // Creamos la conexión usando la clase PDO
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    // Configuramos para que muestre errores si algo sale mal
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error terrible en la conexión!: " . $e->getMessage());
}
?>