<?php
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Actualizar Orden</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Resultado - Actualizar Orden</h1>
        
        <?php
        // Validar datos recibidos
        if (!isset($_POST['OrderID']) || !isset($_POST['CustomerID']) || 
            !isset($_POST['Amount']) || !isset($_POST['Date'])) {
            
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron todos los datos necesarios.';
            echo '</div>';
            echo '<a href="list_orders.php" class="back-link">⬅️ Volver a la lista</a>';
            exit;
        }

        $order_id = intval($_POST['OrderID']);
        $customer_id = intval($_POST['CustomerID']);
        $amount = floatval($_POST['Amount']);
        $date = trim($_POST['Date']);

        $db = getDBConnection();

        // Verificar que el cliente exista
        $check_query = "SELECT Name FROM Customers WHERE CustomerID = ?";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->bind_param("i", $customer_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows == 0) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> El cliente seleccionado no existe.';
            echo '</div>';
            $check_stmt->close();
            $db->close();
            exit;
        }

        $customer = $check_result->fetch_assoc();
        $check_stmt->close();

        // Actualizar orden
        $query = "UPDATE Orders SET CustomerID = ?, Amount = ?, Date = ? WHERE OrderID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("idsi", $customer_id, $amount, $date, $order_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo '<div class="message success">';
            echo '<strong>✅ ¡Éxito!</strong> La orden ha sido actualizada correctamente.';
            echo '<ul style="margin-top: 10px;">';
            echo '<li><strong>Orden ID:</strong> ' . $order_id . '</li>';
            echo '<li><strong>Cliente:</strong> ' . htmlspecialchars($customer['Name']) . ' (ID: ' . $customer_id . ')</li>';
            echo '<li><strong>Monto:</strong> $' . number_format($amount, 2) . '</li>';
            echo '<li><strong>Fecha:</strong> ' . htmlspecialchars($date) . '</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="message info">';
            echo '<strong>ℹ️ No se realizaron cambios</strong> en la orden (los datos eran idénticos).';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <a href="list_orders.php" class="back-link">📋 Ver todas las órdenes</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
