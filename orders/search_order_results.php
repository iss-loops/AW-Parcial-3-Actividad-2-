<?php
// Script que ejecuta la búsqueda de órdenes por ID de orden o cliente
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda - Órdenes</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🔍 Resultados de Búsqueda - Órdenes</h1>
        
        <?php
        // Validar datos recibidos desde el formulario de filtros
        if (!isset($_POST['searchtype']) || !isset($_POST['searchterm'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron los criterios de búsqueda.';
            echo '</div>';
            echo '<a href="search_order.html" class="back-link">⬅️ Volver al formulario</a>';
            exit;
        }

        // Tipo de búsqueda seleccionado e ID a consultar
        $searchtype = $_POST['searchtype'];
        $searchterm = intval($_POST['searchterm']);

        // Validar que el tipo de búsqueda sea uno de los permitidos
        $valid_types = ['OrderID', 'CustomerID'];
        if (!in_array($searchtype, $valid_types)) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> Tipo de búsqueda no válido.';
            echo '</div>';
            exit;
        }

        // Conectar a la base de datos
        $db = getDBConnection();

        // Preparar consulta con JOIN para traer también el nombre del cliente
        $query = "SELECT o.OrderID, o.CustomerID, c.Name, o.Amount, o.Date 
                  FROM Orders o 
                  INNER JOIN Customers c ON o.CustomerID = c.CustomerID 
                  WHERE o.$searchtype = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $searchterm);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mensaje de éxito mostrando la cantidad de coincidencias exactas
            echo '<div class="message success">';
            echo '<strong>✅ Se encontraron ' . $result->num_rows . ' resultado(s)</strong>';
            echo '</div>';

            // Tabla detallada de órdenes y sus acciones
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Orden ID</th>';
            echo '<th>Cliente ID</th>';
            echo '<th>Nombre Cliente</th>';
            echo '<th>Monto</th>';
            echo '<th>Fecha</th>';
            echo '<th>Acciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['OrderID'] . '</td>';
                echo '<td>' . $row['CustomerID'] . '</td>';
                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                echo '<td>$' . number_format($row['Amount'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($row['Date']) . '</td>';
                echo '<td class="actions">';
                echo '<a href="edit_order.php?id=' . $row['OrderID'] . '" class="edit">✏️ Editar</a>';
                echo '<a href="delete_order.php?id=' . $row['OrderID'] . '" class="delete" onclick="return confirm(\'¿Está seguro de eliminar esta orden?\')">🗑️ Eliminar</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<div class="message info">';
            echo '<strong>ℹ️ No se encontraron resultados</strong> para el ID ' . $searchterm . ' en ' . htmlspecialchars($searchtype) . '.';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Navegación recomendada después de revisar los resultados -->
            <a href="search_order.html" class="back-link">🔍 Nueva búsqueda</a> | 
            <a href="list_orders.php" class="back-link">📋 Ver todas</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
