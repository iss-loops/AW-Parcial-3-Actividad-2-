<?php
// Script encargado de validar y eliminar clientes
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Eliminar Cliente</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🗑️ Resultado - Eliminar Cliente</h1>
        
        <?php
        // Validar que llegue el ID del cliente mediante GET
        if (!isset($_GET['id'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se especificó el cliente a eliminar.';
            echo '</div>';
            echo '<a href="list_customers.php" class="back-link">⬅️ Volver a la lista</a>';
            exit;
        }

        $customer_id = intval($_GET['id']);
        $db = getDBConnection();

        // Primero consultamos los datos del cliente para confirmar su existencia
        $query = "SELECT Name, City FROM Customers WHERE CustomerID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $customer = $result->fetch_assoc();
            $stmt->close();

            // Verificar si el cliente tiene órdenes asociadas que impidan borrarlo
            $check_query = "SELECT COUNT(*) as order_count FROM Orders WHERE CustomerID = ?";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bind_param("i", $customer_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $check_row = $check_result->fetch_assoc();
            $check_stmt->close();

            if ($check_row['order_count'] > 0) {
                echo '<div class="message error">';
                echo '<strong>❌ No se puede eliminar:</strong> El cliente tiene ' . $check_row['order_count'] . ' orden(es) asociada(s).';
                echo '<br><br>Debe eliminar primero las órdenes del cliente antes de eliminarlo.';
                echo '</div>';
            } else {
                // Si no tiene órdenes, procedemos a eliminarlo
                $delete_query = "DELETE FROM Customers WHERE CustomerID = ?";
                $delete_stmt = $db->prepare($delete_query);
                $delete_stmt->bind_param("i", $customer_id);
                $delete_stmt->execute();

                // Confirmamos si realmente se eliminó y mostramos sus datos
                if ($delete_stmt->affected_rows > 0) {
                    echo '<div class="message success">';
                    echo '<strong>✅ ¡Éxito!</strong> El cliente ha sido eliminado correctamente.';
                    echo '<ul style="margin-top: 10px;">';
                    echo '<li><strong>ID:</strong> ' . $customer_id . '</li>';
                    echo '<li><strong>Nombre:</strong> ' . htmlspecialchars($customer['Name']) . '</li>';
                    echo '<li><strong>Ciudad:</strong> ' . htmlspecialchars($customer['City']) . '</li>';
                    echo '</ul>';
                    echo '</div>';
                } else {
                    echo '<div class="message error">';
                    echo '<strong>❌ Error:</strong> No se pudo eliminar el cliente.';
                    echo '</div>';
                }

                $delete_stmt->close();
            }
        } else {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> El cliente no existe en la base de datos.';
            echo '</div>';
            $stmt->close();
        }

        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Acciones propuestas después del borrado -->
            <a href="list_customers.php" class="back-link">📋 Ver todos los clientes</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
