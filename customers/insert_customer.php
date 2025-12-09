<?php
// Incluimos la configuración para usar la conexión compartida
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Agregar Cliente</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>👤 Resultado - Agregar Cliente</h1>
        
        <?php
        // Validar que lleguen todos los datos obligatorios del formulario
        if (!isset($_POST['Name']) || !isset($_POST['Address']) || !isset($_POST['City'])) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se recibieron todos los datos necesarios.';
            echo '</div>';
            echo '<a href="add_customer.html" class="back-link">⬅️ Volver al formulario</a>';
            exit;
        }

        // Obtener cada dato recibido y limpiar espacios para asegurar consistencia
        $name = trim($_POST['Name']);
        $address = trim($_POST['Address']);
        $city = trim($_POST['City']);

        // Conectar a la base de datos para poder insertar el registro
        $db = getDBConnection();

        // Preparar consulta parametrizada que evita inyección SQL
        $query = "INSERT INTO Customers (Name, Address, City) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);

        if (!$stmt) {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se pudo preparar la consulta.';
            echo '</div>';
            $db->close();
            exit;
        }

        // Vincular parámetros (todos string) y ejecutar la inserción
        $stmt->bind_param("sss", $name, $address, $city);
        $stmt->execute();

        // Verificar resultado para mostrar el ID generado y los datos guardados
        if ($stmt->affected_rows > 0) {
            $customer_id = $stmt->insert_id;
            
            echo '<div class="message success">';
            echo '<strong>✅ ¡Éxito!</strong> El cliente ha sido agregado correctamente.';
            echo '<ul style="margin-top: 10px;">';
            echo '<li><strong>ID:</strong> ' . $customer_id . '</li>';
            echo '<li><strong>Nombre:</strong> ' . htmlspecialchars($name) . '</li>';
            echo '<li><strong>Dirección:</strong> ' . htmlspecialchars($address) . '</li>';
            echo '<li><strong>Ciudad:</strong> ' . htmlspecialchars($city) . '</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No se pudo agregar el cliente.';
            echo '</div>';
        }

        $stmt->close();
        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Acciones sugeridas tras guardar al cliente -->
            <a href="add_customer.html" class="back-link">➕ Agregar otro cliente</a> | 
            <a href="list_customers.php" class="back-link">📋 Ver todos los clientes</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
