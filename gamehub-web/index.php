<?php
require 'conexion.php';

// Consulta avanzada para traer los juegos
$sql = "SELECT * FROM videojuegos ORDER BY id DESC";
$query = $pdo->query($sql);
$videojuegos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GameHub - Catálogo de Videojuegos</title>
</head>
<body>
    <h1>Portal GameHub</h1>
    <nav>
        <a href="registrar_videojuego.php">Registrar Videojuego</a> | 
        <a href="registrar_resena.php">Escribir Reseña</a>
    </nav>
    
    <h2>Catálogo Disponible</h2>
    <?php if(count($videojuegos) == 0): ?>
        <p>No hay videojuegos registrados aún.</p>
    <?php else: ?>
        <?php foreach($videojuegos as $juego): ?>
            <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
                <h3><?php echo htmlspecialchars($juego['titulo']); ?></h3>
                <p><strong>Género:</strong> <?php echo htmlspecialchars($juego['genero']); ?> | <strong>Plataforma:</strong> <?php echo htmlspecialchars($juego['plataforma']); ?></p>
                
                <h4>Reseñas de la comunidad:</h4>
                <?php
                // Buscamos las reseñas específicas de este juego
                $stmt_res = $pdo->prepare("SELECT * FROM resenas WHERE videojuego_id = ? ORDER BY id DESC");
                $stmt_res->execute([$juego['id']]);
                $resenas = $stmt_res->fetchAll(PDO::FETCH_ASSOC);
                
                if(count($resenas) == 0): ?>
                    <p style="color: #666;">Nadie ha dejado una opinión todavía. ¡Sé el primero!</p>
                <?php else: ?>
                    <ul>
                        <?php foreach($resenas as $res): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($res['usuario']); ?></strong> - Calificación: <?php echo $res['calificacion']; ?>/5
                                <br><em>"<?php echo htmlspecialchars($res['comentario']); ?>"</em>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>