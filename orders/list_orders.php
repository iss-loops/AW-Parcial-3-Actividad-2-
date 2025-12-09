<?php
// Script que despliega todas las órdenes con su cliente asociado
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Órdenes - Book-O-Rama</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>📋 Todas las Órdenes</h1>
        
        <?php
        // Abrimos la conexión para consultar órdenes y totales
        $db = getDBConnection();
        
        // Traemos cada orden junto al nombre del cliente para mostrar información completa
        $query = "SELECT o.OrderID, o.CustomerID, c.Name, o.Amount, o.Date 
                  FROM Orders o 
                  INNER JOIN Customers c ON o.CustomerID = c.CustomerID 
                  ORDER BY o.Date DESC, o.OrderID DESC";
        $result = $db->query($query);

        if ($result && $result->num_rows > 0) {
            echo '<div class="message info">';
            echo '<strong>Total de órdenes:</strong> ' . $result->num_rows;
            
            // Calcular total de ventas para mostrar un resumen financiero
            $total_query = "SELECT SUM(Amount) as total FROM Orders";
            $total_result = $db->query($total_query);
            if ($total_result) {
                $total_row = $total_result->fetch_assoc();
                echo ' | <strong>Total de ventas:</strong> $' . number_format($total_row['total'], 2);
            }
            echo '</div>';

            // Tabla que describe cada orden y las acciones posibles
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
            echo '<strong>ℹ️ No hay órdenes registradas</strong> en la base de datos.';
            echo '</div>';
        }

        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Enlaces rápidos para crear o buscar órdenes -->
            <a href="add_order.php" class="back-link">➕ Agregar orden</a> | 
            <a href="search_order.html" class="back-link">🔍 Buscar</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
