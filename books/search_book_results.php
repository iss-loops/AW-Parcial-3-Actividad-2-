<?php
// Archivo responsable de ejecutar la búsqueda filtrada de libros
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda - Libros</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🔍 Resultados de Búsqueda - Libros</h1>
        
        <?php
        // Validar datos recibidos desde el formulario de búsqueda
        if (!isset($_POST['searchtype']) || !isset($_POST['searchterm'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron los criterios de búsqueda.';
            echo '</div>';
            echo '<a href="search_book.html" class="back-link">⬅️ Volver al formulario</a>';
            exit;
        }

        // Guardamos el tipo de filtro y el texto buscado (sin espacios extra)
        $searchtype = $_POST['searchtype'];
        $searchterm = trim($_POST['searchterm']);

        // Validar que el tipo de búsqueda pertenezca a los permitidos
        $valid_types = ['ISBN', 'Author', 'Title'];
        if (!in_array($searchtype, $valid_types)) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> Tipo de búsqueda no válido.';
            echo '</div>';
            exit;
        }

        // Conectar a la base de datos para ejecutar la consulta
        $db = getDBConnection();

        // Preparar consulta con LIKE para búsqueda parcial y parametrizada
        $query = "SELECT ISBN, Author, Title, Price FROM Books WHERE $searchtype LIKE ?";
        $stmt = $db->prepare($query);
        
        $search_param = "%{$searchterm}%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mostrar mensaje con el total de coincidencias encontradas
            echo '<div class="message success">';
            echo '<strong>✅ Se encontraron ' . $result->num_rows . ' resultado(s)</strong>';
            echo '</div>';

            // Tabla con los datos encontrados y botones de acción
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
            echo '<div class="message info">';
            echo '<strong>ℹ️ No se encontraron resultados</strong> para "' . htmlspecialchars($searchterm) . '" en ' . htmlspecialchars($searchtype) . '.';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Enlaces rápidos tras consultar los resultados -->
            <a href="search_book.html" class="back-link">🔍 Nueva búsqueda</a> | 
            <a href="list_books.php" class="back-link">📋 Ver todos</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
