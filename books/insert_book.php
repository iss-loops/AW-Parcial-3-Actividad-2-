<?php
// Incluimos la configuración para poder usar la función de conexión
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Agregar Libro</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>📖 Resultado - Agregar Libro</h1>
        
        <?php
        // Validar que lleguen todos los datos necesarios desde el formulario
        if (!isset($_POST['ISBN']) || !isset($_POST['Author']) || 
            !isset($_POST['Title']) || !isset($_POST['Price'])) {
            
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron todos los datos necesarios.';
            echo '</div>';
            echo '<a href="add_book.html" class="back-link">⬅️ Volver al formulario</a>';
            exit;
        }

        // Obtener cada valor enviado y limpiar espacios para evitar errores de captura
        $isbn = trim($_POST['ISBN']);
        $author = trim($_POST['Author']);
        $title = trim($_POST['Title']);
        $price = floatval($_POST['Price']);

        // Conectar a la base de datos utilizando la función centralizada
        $db = getDBConnection();

        // Preparar consulta parametrizada para prevenir inyecciones SQL
        $query = "INSERT INTO Books (ISBN, Author, Title, Price) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);

        if (!$stmt) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se pudo preparar la consulta.';
            echo '</div>';
            $db->close();
            exit;
        }

        // Vincular parámetros tipados (s=string, d=double) y ejecutar la consulta
        $stmt->bind_param("sssd", $isbn, $author, $title, $price);
        $stmt->execute();

        // Verificar si realmente se insertó el registro mediante affected_rows
        if ($stmt->affected_rows > 0) {
            echo '<div class="message success">';
            echo '<strong>✅ ¡Éxito!</strong> El libro ha sido agregado correctamente.';
            echo '<ul style="margin-top: 10px;">';
            echo '<li><strong>ISBN:</strong> ' . htmlspecialchars($isbn) . '</li>';
            echo '<li><strong>Autor:</strong> ' . htmlspecialchars($author) . '</li>';
            echo '<li><strong>Título:</strong> ' . htmlspecialchars($title) . '</li>';
            echo '<li><strong>Precio:</strong> $' . number_format($price, 2) . '</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se pudo agregar el libro. ';
            echo 'Verifique que el ISBN no esté duplicado.';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Enlaces útiles para continuar trabajando tras la inserción -->
            <a href="add_book.html" class="back-link">➕ Agregar otro libro</a> | 
            <a href="list_books.php" class="back-link">📋 Ver todos los libros</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
