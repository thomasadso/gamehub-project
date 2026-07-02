<?php
require 'conexion.php';

$mensaje = "";

// Traemos los videojuegos existentes para mostrarlos en una lista desplegable
$stmt_juegos = $pdo->query("SELECT id, titulo FROM videojuegos");
$videojuegos = $stmt_juegos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $videojuego_id = $_POST['videojuego_id'];
    $usuario = trim($_POST['usuario']);
    $comentario = trim($_POST['comentario']);
    $calificacion = intval($_POST['calificacion']);

    if (empty($videojuego_id) || empty($usuario) || empty($comentario) || empty($calificacion)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // 1. Guardar en PostgreSQL [cite: 46]
        $sql = "INSERT INTO resenas (videojuego_id, usuario, comentario, calificacion) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$videojuego_id, $usuario, $comentario, $calificacion])) {
            
            // Traer el nombre del juego para mandárselo a la analítica de Python
            $stmt_juego_info = $pdo->prepare("SELECT titulo FROM videojuegos WHERE id = ?");
            $stmt_juego_info->execute([$videojuego_id]);
            $juego_nombre = $stmt_juego_info->fetchColumn();

            // 2. Enviar datos a la API de Python Flask usando cURL [cite: 47]
            // Cambia esta URL por la URL pública de tu Render cuando la despliegues 
            $url_api_python = "http://localhost:5000/api/videojuegos"; 
            
            $datos_para_python = [
                "videojuego_id" => $videojuego_id,
                "titulo" => $juego_nombre,
                "calificacion" => $calificacion
            ];

            $ch = curl_init($url_api_python);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos_para_python));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            
            // Ejecutamos el envío
            $respuesta_api = curl_exec($ch);
            curl_close($ch);

            $mensaje = "Reseña guardada y enviada al módulo de analítica correctamente.";
        } else {
            $mensaje = "Error al intentar guardar la reseña.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dejar Reseña - GameHub</title>
</head>
<body>
    <h2>Escribir una Reseña</h2>
    <?php if(!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

    <form action="registrar_resena.php" method="POST">
        <label>Selecciona el Videojuego:</label><br>
        <select name="videojuego_id" required>
            <option value="">-- Elige un juego --</option>
            <?php foreach($videojuegos as $juego): ?>
                <option value="<?php echo $juego['id']; ?>"><?php echo htmlspecialchars($juego['titulo']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Tu Nombre / Usuario:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Calificación (1 a 5 estrellas):</label><br>
        <input type="number" name="calificacion" min="1" max="5" required><br><br>

        <label>Comentario:</label><br>
        <textarea name="comentario" rows="5" cols="40" required></textarea><br><br>

        <button type="submit">Publicar Reseña</button>
    </form>
    <br>
    <a href="index.php">Volver al Catálogo</a>
</body>
</html>