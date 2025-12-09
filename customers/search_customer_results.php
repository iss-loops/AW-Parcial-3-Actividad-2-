<?php
// Script que procesa la búsqueda de clientes con filtros dinámicos
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda - Clientes</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🔍 Resultados de Búsqueda - Clientes</h1>
        
        <?php
        // Validar datos recibidos desde el formulario de búsqueda
        if (!isset($_POST['searchtype']) || !isset($_POST['searchterm'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron los criterios de búsqueda.';
            echo '</div>';
            echo '<a href="search_customer.html" class="back-link">⬅️ Volver al formulario</a>';
            exit;
        }

        // Se guarda el tipo de filtro seleccionado y el texto buscado
        $searchtype = $_POST['searchtype'];
        $searchterm = trim($_POST['searchterm']);

        // Validar que el tipo de búsqueda pertenezca al listado permitido
        $valid_types = ['CustomerID', 'Name', 'City'];
        if (!in_array($searchtype, $valid_types)) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> Tipo de búsqueda no válido.';
            echo '</div>';
            exit;
        }

        // Conectar a la base de datos
        $db = getDBConnection();

        // Preparar consulta con LIKE para permitir coincidencias parciales
        $query = "SELECT CustomerID, Name, Address, City FROM Customers WHERE $searchtype LIKE ?";
        $stmt = $db->prepare($query);
        
        $search_param = "%{$searchterm}%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mostrar resumen de cuántos registros cumplieron la condición
            echo '<div class="message success">';
            echo '<strong>✅ Se encontraron ' . $result->num_rows . ' resultado(s)</strong>';
            echo '</div>';

            // Tabla con los resultados y acciones subsecuentes para cada cliente
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Nombre</th>';
            echo '<th>Dirección</th>';
            echo '<th>Ciudad</th>';
            echo '<th>Acciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['CustomerID'] . '</td>';
                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['City']) . '</td>';
                echo '<td class="actions">';
                echo '<a href="edit_customer.php?id=' . $row['CustomerID'] . '" class="edit">✏️ Editar</a>';
                echo '<a href="delete_customer.php?id=' . $row['CustomerID'] . '" class="delete" onclick="return confirm(\'¿Está seguro de eliminar este cliente?\')">🗑️ Eliminar</a>';
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
            <!-- Acciones recomendadas después de consultar los resultados -->
            <a href="search_customer.html" class="back-link">🔍 Nueva búsqueda</a> | 
            <a href="list_customers.php" class="back-link">📋 Ver todos</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
