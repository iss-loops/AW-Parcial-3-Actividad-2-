<?php
require_once '../db_config.php';
$db = getDBConnection();

// Obtener lista de clientes para poblar el select y relacionar la orden
$query = "SELECT CustomerID, Name FROM Customers ORDER BY Name";
$result = $db->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Orden - Book-O-Rama</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🛒 Agregar Nueva Orden</h1>
        
        <?php
        if ($result && $result->num_rows > 0) {
        ?>
        
        <!-- Formulario que envía la orden al script insert_order.php -->
        <form action="insert_order.php" method="post">
            <fieldset>
                <legend>Información de la Orden</legend>
                
                <!-- Campo individual para seleccionar el cliente dueño de la orden -->
                <div class="form-group">
                    <label for="CustomerID">Cliente:</label>
                    <select id="CustomerID" name="CustomerID" required>
                        <option value="">-- Seleccione un cliente --</option>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['CustomerID'] . '">';
                            echo htmlspecialchars($row['Name']) . ' (ID: ' . $row['CustomerID'] . ')';
                            echo '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Campo individual para ingresar el monto total de la orden -->
                <div class="form-group">
                    <label for="Amount">Monto:</label>
                    <input type="number" id="Amount" name="Amount" step="0.01" min="0" required
                           placeholder="Ej: 1500.00">
                </div>

                <!-- Campo individual para seleccionar la fecha de compra -->
                <div class="form-group">
                    <label for="Date">Fecha:</label>
                    <input type="date" id="Date" name="Date" required
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
            </fieldset>

            <!-- Botón que guarda la orden nueva -->
            <input type="submit" value="💾 Guardar Orden">
        </form>
        
        <?php
        } else {
            // Si no hay clientes, no es posible crear una orden
            echo '<div class="message error">';
            echo '<strong>❌ Error:</strong> No hay clientes registrados. ';
            echo 'Debe agregar al menos un cliente antes de crear una orden.';
            echo '</div>';
            echo '<a href="../customers/add_customer.html" class="back-link">➕ Agregar Cliente</a>';
        }
        
        $db->close();
        ?>

        <!-- Enlace para volver al tablero general -->
        <a href="../index.php" class="back-link">⬅️ Volver al inicio</a>
    </div>
</body>
</html>
