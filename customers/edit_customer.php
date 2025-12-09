<?php
require_once '../db_config.php';

// Validar que llegue el ID del cliente a editar
if (!isset($_GET['id'])) {
    header('Location: list_customers.php');
    exit;
}

$customer_id = intval($_GET['id']);
$db = getDBConnection();

// Consultar los datos actuales para precargar el formulario
$query = "SELECT CustomerID, Name, Address, City FROM Customers WHERE CustomerID = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

// Si no existe el cliente, regresamos a la lista
if ($result->num_rows == 0) {
    $stmt->close();
    $db->close();
    header('Location: list_customers.php');
    exit;
}

// Guardamos los datos en un arreglo asociativo
$customer = $result->fetch_assoc();
$stmt->close();
$db->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - Book-O-Rama</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Cliente</h1>
        
        <!-- Formulario que enviará los cambios al script update_customer.php -->
        <form action="update_customer.php" method="post">
            <fieldset>
                <legend>Información del Cliente</legend>
                
                <!-- ID oculto que se envía al servidor para identificar el registro -->
                <input type="hidden" name="CustomerID" value="<?php echo $customer['CustomerID']; ?>">
                
                <!-- Campo visible solo lectura para mostrar el ID -->
                <div class="form-group">
                    <label for="CustomerID_display">ID:</label>
                          <input type="text" id="CustomerID_display" 
                              value="<?php echo $customer['CustomerID']; ?>" 
                              readonly style="background-color: #2e1a47; width: 100px;">
                </div>

                <!-- Campo individual que permite editar el nombre -->
                <div class="form-group">
                    <label for="Name">Nombre:</label>
                    <input type="text" id="Name" name="Name" maxlength="100" required
                           value="<?php echo htmlspecialchars($customer['Name']); ?>">
                </div>

                <!-- Campo individual para actualizar la dirección completa -->
                <div class="form-group">
                    <label for="Address">Dirección:</label>
                    <input type="text" id="Address" name="Address" maxlength="200" required
                           value="<?php echo htmlspecialchars($customer['Address']); ?>">
                </div>

                <!-- Campo individual para editar la ciudad -->
                <div class="form-group">
                    <label for="City">Ciudad:</label>
                    <input type="text" id="City" name="City" maxlength="100" required
                           value="<?php echo htmlspecialchars($customer['City']); ?>">
                </div>
            </fieldset>

            <!-- Botón para confirmar la actualización -->
            <input type="submit" value="💾 Actualizar Cliente">
            <!-- Botón para regresar sin guardar -->
            <a href="list_customers.php" style="text-decoration: none;">
                <button type="button" class="btn-secondary">❌ Cancelar</button>
            </a>
        </form>

        <!-- Enlace hacia el tablero general -->
        <a href="../index.php" class="back-link">⬅️ Volver al inicio</a>
    </div>
</body>
</html>
