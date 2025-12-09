<?php
// Script que valida y guarda una nueva orden
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Agregar Orden</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🛒 Resultado - Agregar Orden</h1>
        
        <?php
        // Validar que lleguen todos los datos indispensables desde el formulario
        if (!isset($_POST['CustomerID']) || !isset($_POST['Amount']) || !isset($_POST['Date'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron todos los datos necesarios.';
            echo '</div>';
            echo '<a href="add_order.php" class="back-link">⬅️ Volver al formulario</a>';
            exit;
        }

        // Obtener y sanitizar datos para evitar formatos incorrectos
        $customer_id = intval($_POST['CustomerID']);
        $amount = floatval($_POST['Amount']);
        $date = trim($_POST['Date']);

        // Conectar a la base de datos para ejecutar las validaciones e inserción
        $db = getDBConnection();

        // Verificar que el cliente exista antes de asignarle una orden
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

        // Preparar consulta de inserción parametrizada
        $query = "INSERT INTO Orders (CustomerID, Amount, Date) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);

        if (!$stmt) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se pudo preparar la consulta.';
            echo '</div>';
            $db->close();
            exit;
        }

        // Vincular parámetros tipados y ejecutar la sentencia
        $stmt->bind_param("ids", $customer_id, $amount, $date);
        $stmt->execute();

        // Verificar resultado y mostrar datos principales de la orden
        if ($stmt->affected_rows > 0) {
            $order_id = $stmt->insert_id;
            
            echo '<div class="message success">';
            echo '<strong>✅ ¡Éxito!</strong> La orden ha sido agregada correctamente.';
            echo '<ul style="margin-top: 10px;">';
            echo '<li><strong>Orden ID:</strong> ' . $order_id . '</li>';
            echo '<li><strong>Cliente:</strong> ' . htmlspecialchars($customer['Name']) . ' (ID: ' . $customer_id . ')</li>';
            echo '<li><strong>Monto:</strong> $' . number_format($amount, 2) . '</li>';
            echo '<li><strong>Fecha:</strong> ' . htmlspecialchars($date) . '</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se pudo agregar la orden.';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Acciones recomendadas tras registrar la orden -->
            <a href="add_order.php" class="back-link">➕ Agregar otra orden</a> | 
            <a href="list_orders.php" class="back-link">📋 Ver todas las órdenes</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
