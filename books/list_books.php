<?php
// Incluimos la configuración para consultar todos los libros
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Libros - Book-O-Rama</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>📋 Todos los Libros</h1>
        
        <?php
        // Abrir conexión para listar todos los registros
        $db = getDBConnection();
        
        // Consulta principal que ordena los libros por título para facilitar la lectura
        $query = "SELECT ISBN, Author, Title, Price FROM Books ORDER BY Title";
        $result = $db->query($query);

        if ($result && $result->num_rows > 0) {
            // Mensaje informativo con el total encontrado
            echo '<div class="message info">';
            echo '<strong>Total de libros:</strong> ' . $result->num_rows;
            echo '</div>';

            // Encabezado de tabla que describe cada columna
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ISBN</th>';
            echo '<th>Autor</th>';
            echo '<th>Título</th>';
            echo '<th>Precio</th>';
            echo '<th>Acciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Recorremos cada fila para mostrarla junto con sus botones de acción
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['ISBN']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Author']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Title']) . '</td>';
                echo '<td>$' . number_format($row['Price'], 2) . '</td>';
                echo '<td class="actions">';
                echo '<a href="edit_book.php?isbn=' . urlencode($row['ISBN']) . '" class="edit">✏️ Editar</a>';
                echo '<a href="delete_book.php?isbn=' . urlencode($row['ISBN']) . '" class="delete" onclick="return confirm(\'¿Está seguro de eliminar este libro?\')">🗑️ Eliminar</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            // Mensaje cuando no hay información a mostrar
            echo '<div class="message info">';
            echo '<strong>ℹ️ No hay libros registrados</strong> en la base de datos.';
            echo '</div>';
        }

        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Acciones disponibles después del listado -->
            <a href="add_book.html" class="back-link">➕ Agregar libro</a> | 
            <a href="search_book.html" class="back-link">🔍 Buscar</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
