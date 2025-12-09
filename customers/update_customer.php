<?php
// Script que procesa la actualización de clientes
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Actualizar Cliente</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Resultado - Actualizar Cliente</h1>
        
        <?php
        // Validar que todos los campos necesarios se hayan enviado via POST
        if (!isset($_POST['CustomerID']) || !isset($_POST['Name']) || 
            !isset($_POST['Address']) || !isset($_POST['City'])) {
            
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron todos los datos necesarios.';
            echo '</div>';
            echo '<a href="list_customers.php" class="back-link">⬅️ Volver a la lista</a>';
            exit;
        }

        // Limpiamos y convertimos los datos de entrada para asegurar consistencia
        $customer_id = intval($_POST['CustomerID']);
        $name = trim($_POST['Name']);
        $address = trim($_POST['Address']);
        $city = trim($_POST['City']);

        // Obtenemos la conexión para ejecutar el UPDATE
        $db = getDBConnection();

        // Actualizar cliente mediante sentencia preparada
        $query = "UPDATE Customers SET Name = ?, Address = ?, City = ? WHERE CustomerID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssi", $name, $address, $city, $customer_id);
        $stmt->execute();

        // Informamos al usuario si hubo cambios o no
        if ($stmt->affected_rows > 0) {
            echo '<div class="message success">';
            echo '<strong>✅ ¡Éxito!</strong> El cliente ha sido actualizado correctamente.';
            echo '<ul style="margin-top: 10px;">';
            echo '<li><strong>ID:</strong> ' . $customer_id . '</li>';
            echo '<li><strong>Nombre:</strong> ' . htmlspecialchars($name) . '</li>';
            echo '<li><strong>Dirección:</strong> ' . htmlspecialchars($address) . '</li>';
            echo '<li><strong>Ciudad:</strong> ' . htmlspecialchars($city) . '</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="message info">';
            echo '<strong>ℹ️ No se realizaron cambios</strong> en el cliente (los datos eran idénticos).';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Accesos directos tras actualizar -->
            <a href="list_customers.php" class="back-link">📋 Ver todos los clientes</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
