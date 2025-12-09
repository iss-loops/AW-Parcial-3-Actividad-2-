<?php
// Script encargado de listar todos los clientes registrados
require_once '../db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Clientes - Book-O-Rama</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>📋 Todos los Clientes</h1>
        
        <?php
        // Abrimos una conexión para ejecutar el SELECT
        $db = getDBConnection();
        
        // Obtenemos todos los clientes ordenados alfabéticamente por nombre
        $query = "SELECT CustomerID, Name, Address, City FROM Customers ORDER BY Name";
        $result = $db->query($query);

        if ($result && $result->num_rows > 0) {
            echo '<div class="message info">';
            echo '<strong>Total de clientes:</strong> ' . $result->num_rows;
            echo '</div>';

            // Cabecera de tabla que explica cada columna
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Nombre</th>';
            echo '<th>Dirección</th>';
            echo '<th>Ciudad</th>';
            echo '<th>Acciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Recorremos cada cliente para dibujarlo y adjuntar acciones CRUD
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['CustomerID'] . '</td>';
                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['City']) . '</td>';
                echo '<td class="actions">';
                echo '<a href="edit_customer.php?id=' . $row['CustomerID'] . '" class="edit">✏️ Editar</a>';
                echo '<a href="delete_customer.php?id=' . $row['CustomerID'] . '" class="delete" onclick="return confirm(\'¿Está seguro de eliminar este cliente?\')">🗑️ Eliminar</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            // Mensaje mostrado cuando no existen clientes almacenados
            echo '<div class="message info">';
            echo '<strong>ℹ️ No hay clientes registrados</strong> en la base de datos.';
            echo '</div>';
        }

        $db->close();
        ?>

        <div style="margin-top: 20px;">
            <!-- Enlaces para ejecutar otras acciones con clientes -->
            <a href="add_customer.html" class="back-link">➕ Agregar cliente</a> | 
            <a href="search_customer.html" class="back-link">🔍 Buscar</a> | 
            <a href="../index.php" class="back-link">🏠 Inicio</a>
        </div>
    </div>
</body>
</html>
