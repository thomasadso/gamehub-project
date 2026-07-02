<?php
require 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiamos los datos para evitar textos raros o dañinos
    $titulo = trim($_POST['titulo']);
    $genero = trim($_POST['genero']);
    $plataforma = trim($_POST['plataforma']);
    $fecha = trim($_POST['fecha_lanzamiento']);

    // Validación: que ningún campo obligatorio esté vacío 
    if (empty($titulo) || empty($genero) || empty($plataforma)) {
        $mensaje = "Por favor, llene todos los campos obligatorios.";
    } else {
        // Insertamos los datos de manera segura con sentencias preparadas
        $sql = "INSERT INTO videojuegos (titulo, genero, plataforma, fecha_lanzamiento) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$titulo, $genero, $plataforma, $fecha])) {
            $mensaje = "Videojuego registrado con éxito.";
        } else {
            $mensaje = "Hubo un error al guardar el videojuego.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Videojuego - GameHub</title>
</head>
<body>
    <h2>Registrar Nuevo Videojuego</h2>
    <?php if(!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>
    
    <form action="registrar_videojuego.php" method="POST">
        <label>Título del Videojuego *:</label><br>
        <input type="text" name="titulo" required><br><br>

        <label>Género *:</label><br>
        <input type="text" name="genero" required><br><br>

        <label>Plataforma *:</label><br>
        <input type="text" name="plataforma" placeholder="Ej: PC, PS5, Xbox" required><br><br>

        <label>Fecha de Lanzamiento:</label><br>
        <input type="date" name="fecha_lanzamiento"><br><br>

        <button type="submit">Guardar Videojuego</button>
    </form>
    <br>
    <a href="index.php">Volver al Catálogo</a>
</body>
</html>