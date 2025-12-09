<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book-O-Rama - Sistema de Gestión</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Contenedor principal que centra el tablero de navegación -->
    <div class="container">
        <!-- Título general del sistema -->
        <h1>📚 ALBA- Sistema de Gestión</h1>
        <!-- Mensaje introductorio con descripción corta del sistema -->
        <p style="text-align: center; color: #a29bfe; margin-bottom: 30px;">
            Sistema completo de gestión de libros, clientes y órdenes
        </p>

        <!-- Menú de navegación que distribuye los módulos CRUD -->
        <div class="nav-menu">
            <!-- BOOKS MODULE: accesso rápido a las pantallas de libros -->
            <div class="nav-section">
                <!-- Cabecera que identifica el módulo actual -->
                <h3>📖 Libros (Books)</h3>
                <!-- Enlace directo al formulario para capturar un nuevo libro -->
                <a href="books/add_book.html">➕ Agregar Libro</a>
                <!-- Enlace para abrir el formulario de búsqueda de libros -->
                <a href="books/search_book.html">🔍 Buscar Libro</a>
                <!-- Enlace que lista todos los registros de libros existentes -->
                <a href="books/list_books.php">📋 Listar Todos</a>
            </div>

            <!-- CUSTOMERS MODULE: opciones del módulo de clientes -->
            <div class="nav-section">
                <h3>👥 Clientes (Customers)</h3>
                <!-- Botón que abre el formulario para registrar un cliente -->
                <a href="customers/add_customer.html">➕ Agregar Cliente</a>
                <!-- Botón para ir directamente al buscador de clientes -->
                <a href="customers/search_customer.html">🔍 Buscar Cliente</a>
                <!-- Botón que muestra el listado general de clientes -->
                <a href="customers/list_customers.php">📋 Listar Todos</a>
            </div>

            <!-- ORDERS MODULE: accesos rápidos a órdenes -->
            <div class="nav-section">
                <h3>🛒 Órdenes (Orders)</h3>
                <!-- Ir al formulario donde se genera una nueva orden -->
                <a href="orders/add_order.php">➕ Agregar Orden</a>
                <!-- Ir al buscador de órdenes por ID -->
                <a href="orders/search_order.html">🔍 Buscar Orden</a>
                <!-- Mostrar todas las órdenes registradas -->
                <a href="orders/list_orders.php">📋 Listar Todas</a>
            </div>
        </div>

        <!-- Tarjeta informativa con detalles técnicos del sistema -->
        <div style="margin-top: 40px; padding: 20px; background: #2e1a47; border-radius: 5px;">
            <h3 style="color: #00f5ff;">ℹ️ Información del Sistema</h3>
            <ul style="margin-left: 20px; line-height: 2;">
                <!-- Referencia a la base de datos física utilizada -->
                <li><strong>Base de datos:</strong> 5AP3_israel_zacarias</li>
                <!-- Lista las tablas principales administradas -->
                <li><strong>Tablas:</strong> Books, Customers, Orders</li>
                <!-- Recordatorio del alcance CRUD que ofrece la aplicación -->
                <li><strong>Operaciones:</strong> Crear, Buscar, Actualizar, Eliminar (CRUD)</li>
            </ul>
        </div>
    </div>
</body>
</html>
