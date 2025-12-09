<?php
require_once '../db_config.php';

// Validar que llegue el ID
if (!isset($_GET['id'])) {
    header('Location: list_orders.php');
    exit;
}

$order_id = intval($_GET['id']);
$db = getDBConnection();

// Obtener datos de la orden
$query = "SELECT OrderID, CustomerID, Amount, Date FROM Orders WHERE OrderID = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt->close();
    $db->close();
    header('Location: list_orders.php');
    exit;
}

$order = $result->fetch_assoc();
$stmt->close();

// Obtener lista de clientes para el dropdown
$customers_query = "SELECT CustomerID, Name FROM Customers ORDER BY Name";
$customers_result = $db->query($customers_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Orden - Book-O-Rama</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Orden</h1>
        
        <form action="update_order.php" method="post">
            <fieldset>
                <legend>Información de la Orden</legend>
                
                <input type="hidden" name="OrderID" value="<?php echo $order['OrderID']; ?>">
                
                <div class="form-group">
                    <label for="OrderID_display">Orden ID:</label>
                          <input type="text" id="OrderID_display" 
                              value="<?php echo $order['OrderID']; ?>" 
                              readonly style="background-color: #2e1a47; width: 100px;">
                </div>

                <div class="form-group">
                    <label for="CustomerID">Cliente:</label>
                    <select id="CustomerID" name="CustomerID" required>
                        <?php
                        if ($customers_result && $customers_result->num_rows > 0) {
                            while ($customer = $customers_result->fetch_assoc()) {
                                $selected = ($customer['CustomerID'] == $order['CustomerID']) ? 'selected' : '';
                                echo '<option value="' . $customer['CustomerID'] . '" ' . $selected . '>';
                                echo htmlspecialchars($customer['Name']) . ' (ID: ' . $customer['CustomerID'] . ')';
                                echo '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Amount">Monto:</label>
                    <input type="number" id="Amount" name="Amount" step="0.01" min="0" required
                           value="<?php echo $order['Amount']; ?>">
                </div>

                <div class="form-group">
                    <label for="Date">Fecha:</label>
                    <input type="date" id="Date" name="Date" required
                           value="<?php echo $order['Date']; ?>">
                </div>
            </fieldset>

            <input type="submit" value="💾 Actualizar Orden">
            <a href="list_orders.php" style="text-decoration: none;">
                <button type="button" class="btn-secondary">❌ Cancelar</button>
            </a>
        </form>

        <a href="../index.php" class="back-link">⬅️ Volver al inicio</a>
    </div>
</body>
</html>
<?php
$db->close();
?>
