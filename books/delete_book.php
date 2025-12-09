<?php
// Incluimos utilería para poder conectarnos a la base de datos
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Eliminar Libro</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🗑️ Resultado - Eliminar Libro</h1>
        
        <?php
        // Validar que se reciba el ISBN desde la URL
        if (!isset($_GET['isbn'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se especificó el libro a eliminar.';
            echo '</div>';
            echo '<a href="list_books.php" class="back-link">⬅️ Volver a la lista</a>';
            exit;
        }

        $isbn = $_GET['isbn'];
        $db = getDBConnection();

        // Primero recuperar los datos del libro para mostrar un resumen
        $query = "SELECT Title, Author FROM Books WHERE ISBN = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
            $stmt->close();

            // Si existe, procedemos a eliminarlo con otra consulta preparada
            $delete_query = "DELETE FROM Books WHERE ISBN = ?";
            $delete_stmt = $db->prepare($delete_query);
            $delete_stmt->bind_param("s", $isbn);
            $delete_stmt->execute();

            // Confirmamos si realmente se eliminó y mostramos los datos afectados
            if ($delete_stmt->affected_rows > 0) {
                echo '<div class="message success">';
                echo '<strong>✅ ¡Éxito!</strong> El libro ha sido eliminado correctamente.';
                echo '<ul style="margin-top: 10px;">';
                echo '<li><strong>ISBN:</strong> ' . htmlspecialchars($isbn) . '</li>';
                echo '<li><strong>Título:</strong> ' . htmlspecialchars($book['Title']) . '</li>';
                echo '<li><strong>Autor:</strong> ' . htmlspecialchars($book['Author']) . '</li>';
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<div class="message error">';
                echo '<strong>❌ Error:</strong> No se pudo eliminar el libro.';
                echo '</div>';
            }

            $delete_stmt->close();
        } else {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> El libro no existe en la base de datos.';
            echo '</div>';
            $stmt->close();
        }

        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Accesos rápidos posteriores a la eliminación -->
            <a href="list_books.php" class="back-link">📋 Ver todos los libros</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
