<?php
require_once '../db_config.php';

// Validar que llegue el ISBN del libro a editar
if (!isset($_GET['isbn'])) {
    header('Location: list_books.php');
    exit;
}

$isbn = $_GET['isbn'];
$db = getDBConnection();

// Obtener datos del libro solicitado para precargar el formulario
$query = "SELECT ISBN, Author, Title, Price FROM Books WHERE ISBN = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();

// Si no existe el registro, regresamos a la lista
if ($result->num_rows == 0) {
    $stmt->close();
    $db->close();
    header('Location: list_books.php');
    exit;
}

// Guardamos los datos en un arreglo asociativo para mostrarlos
$book = $result->fetch_assoc();
$stmt->close();
$db->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro - Book-O-Rama</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Libro</h1>
        
        <!-- Formulario que envía los cambios al script update_book.php -->
        <form action="update_book.php" method="post">
            <fieldset>
                <legend>Información del Libro</legend>
                
                <!-- Campo individual solo lectura para mostrar el ISBN (no editable) -->
                <div class="form-group">
                    <label for="ISBN">ISBN:</label>
                          <input type="text" id="ISBN" name="ISBN" 
                              value="<?php echo htmlspecialchars($book['ISBN']); ?>" 
                              readonly style="background-color: #2e1a47;">
                </div>

                <!-- Campo individual para actualizar el autor -->
                <div class="form-group">
                    <label for="Author">Autor:</label>
                    <input type="text" id="Author" name="Author" maxlength="100" required
                           value="<?php echo htmlspecialchars($book['Author']); ?>">
                </div>

                <!-- Campo individual para actualizar el título -->
                <div class="form-group">
                    <label for="Title">Título:</label>
                    <input type="text" id="Title" name="Title" maxlength="200" required
                           value="<?php echo htmlspecialchars($book['Title']); ?>">
                </div>

                <!-- Campo individual para modificar el precio del libro -->
                <div class="form-group">
                    <label for="Price">Precio:</label>
                    <input type="number" id="Price" name="Price" step="0.01" min="0" required
                           value="<?php echo $book['Price']; ?>">
                </div>
            </fieldset>

            <!-- Botón para confirmar la edición -->
            <input type="submit" value="💾 Actualizar Libro">
            <!-- Botón secundario para regresar sin guardar -->
            <a href="list_books.php" style="text-decoration: none;">
                <button type="button" class="btn-secondary">❌ Cancelar</button>
            </a>
        </form>

        <!-- Enlace de retorno al inicio general -->
        <a href="../index.php" class="back-link">⬅️ Volver al inicio</a>
    </div>
</body>
</html>
