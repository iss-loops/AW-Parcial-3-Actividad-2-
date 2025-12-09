<?php
// Incluimos utilería de conexión para actualizar registros
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Actualizar Libro</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Resultado - Actualizar Libro</h1>
        
        <?php
        // Validar que todos los campos requeridos hayan llegado en la petición POST
        if (!isset($_POST['ISBN']) || !isset($_POST['Author']) || 
            !isset($_POST['Title']) || !isset($_POST['Price'])) {
            
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron todos los datos necesarios.';
            echo '</div>';
            echo '<a href="list_books.php" class="back-link">⬅️ Volver a la lista</a>';
            exit;
        }

        // Limpiar los valores recibidos para evitar espacios extra
        $isbn = trim($_POST['ISBN']);
        $author = trim($_POST['Author']);
        $title = trim($_POST['Title']);
        $price = floatval($_POST['Price']);

        // Abrimos una conexión nueva para ejecutar el UPDATE
        $db = getDBConnection();

        // Actualizar libro utilizando parámetros preparados
        $query = "UPDATE Books SET Author = ?, Title = ?, Price = ? WHERE ISBN = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssds", $author, $title, $price, $isbn);
        $stmt->execute();

        // Informar el resultado al usuario dependiendo si hubo cambios
        if ($stmt->affected_rows > 0) {
            echo '<div class="message success">';
            echo '<strong>✅ ¡Éxito!</strong> El libro ha sido actualizado correctamente.';
            echo '<ul style="margin-top: 10px;">';
            echo '<li><strong>ISBN:</strong> ' . htmlspecialchars($isbn) . '</li>';
            echo '<li><strong>Autor:</strong> ' . htmlspecialchars($author) . '</li>';
            echo '<li><strong>Título:</strong> ' . htmlspecialchars($title) . '</li>';
            echo '<li><strong>Precio:</strong> $' . number_format($price, 2) . '</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="message info">';
            echo '<strong>ℹ️ No se realizaron cambios</strong> en el libro (los datos eran idénticos).';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Enlaces para continuar trabajando tras la actualización -->
            <a href="list_books.php" class="back-link">📋 Ver todos los libros</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
