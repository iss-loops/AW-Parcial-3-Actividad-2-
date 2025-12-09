<?php
// Script que elimina una orden específica
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Eliminar Orden</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🗑️ Resultado - Eliminar Orden</h1>
        
        <?php
        // Validar que llegue el ID de la orden mediante la URL
        if (!isset($_GET['id'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se especificó la orden a eliminar.';
            echo '</div>';
            echo '<a href="list_orders.php" class="back-link">⬅️ Volver a la lista</a>';
            exit;
        }

        $order_id = intval($_GET['id']);
        $db = getDBConnection();

        // Primero consultamos la información de la orden para mostrar un resumen
        $query = "SELECT o.OrderID, o.Amount, o.Date, c.Name 
                  FROM Orders o 
                  INNER JOIN Customers c ON o.CustomerID = c.CustomerID 
                  WHERE o.OrderID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $order = $result->fetch_assoc();
            $stmt->close();

            // Eliminar la orden usando una sentencia preparada
            $delete_query = "DELETE FROM Orders WHERE OrderID = ?";
            $delete_stmt = $db->prepare($delete_query);
            $delete_stmt->bind_param("i", $order_id);
            $delete_stmt->execute();

            // Confirmamos si se eliminó correctamente
            if ($delete_stmt->affected_rows > 0) {
                echo '<div class="message success">';
                echo '<strong>✅ ¡Éxito!</strong> La orden ha sido eliminada correctamente.';
                echo '<ul style="margin-top: 10px;">';
                echo '<li><strong>Orden ID:</strong> ' . $order['OrderID'] . '</li>';
                echo '<li><strong>Cliente:</strong> ' . htmlspecialchars($order['Name']) . '</li>';
                echo '<li><strong>Monto:</strong> $' . number_format($order['Amount'], 2) . '</li>';
                echo '<li><strong>Fecha:</strong> ' . htmlspecialchars($order['Date']) . '</li>';
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<div class="message error">';
                echo '<strong>❌ Error:</strong> No se pudo eliminar la orden.';
                echo '</div>';
            }

            $delete_stmt->close();
        } else {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> La orden no existe en la base de datos.';
            echo '</div>';
            $stmt->close();
        }

        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Enlaces para continuar navegando tras eliminar -->
            <a href="list_orders.php" class="back-link">📋 Ver todas las órdenes</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
