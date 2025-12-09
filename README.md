# 📚 Book-O-Rama - Sistema CRUD Completo

Sistema de gestión completo para libros, clientes y órdenes desarrollado en PHP y MySQL.

---

## 🗂️ Estructura del Proyecto

```
bookorama/
├── index.php                 # Página principal
├── style.css                 # Estilos CSS globales
├── db_config.php            # Configuración de base de datos
│
├── books/                   # Módulo de Libros
│   ├── add_book.html
│   ├── insert_book.php
│   ├── search_book.html
│   ├── search_book_results.php
│   ├── list_books.php
│   ├── edit_book.php
│   ├── update_book.php
│   └── delete_book.php
│
├── customers/               # Módulo de Clientes
│   ├── add_customer.html
│   ├── insert_customer.php
│   ├── search_customer.html
│   ├── search_customer_results.php
│   ├── list_customers.php
│   ├── edit_customer.php
│   ├── update_customer.php
│   └── delete_customer.php
│
└── orders/                  # Módulo de Órdenes
    ├── add_order.php
    ├── insert_order.php
    ├── search_order.html
    ├── search_order_results.php
    ├── list_orders.php
    ├── edit_order.php
    ├── update_order.php
    └── delete_order.php
```

---

## 🛠️ Instalación

### 1. Requisitos Previos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) o XAMPP/WAMP/MAMP

### 2. Configurar Base de Datos

Ejecuta el siguiente script SQL en tu servidor MySQL:

```sql
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS 5AP3_israel_zacarias;
USE 5AP3_israel_zacarias;

-- Tabla Books
CREATE TABLE IF NOT EXISTS Books (
    ISBN VARCHAR(13) PRIMARY KEY,
    Author VARCHAR(100) NOT NULL,
    Title VARCHAR(200) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla Customers
CREATE TABLE IF NOT EXISTS Customers (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Address VARCHAR(200) NOT NULL,
    City VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla Orders
CREATE TABLE IF NOT EXISTS Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    Amount DECIMAL(10, 2) NOT NULL,
    Date DATE NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. Configurar Conexión

Edita el archivo `db_config.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Tu usuario de MySQL
define('DB_PASS', '');              // Tu contraseña de MySQL
define('DB_NAME', '5AP3_israel_zacarias');
```

### 4. Copiar Archivos

Copia todos los archivos del proyecto a tu carpeta web:
- **XAMPP**: `C:\xampp\htdocs\bookorama\`
- **WAMP**: `C:\wamp\www\bookorama\`
- **Linux**: `/var/www/html/bookorama/`

### 5. Acceder al Sistema

Abre tu navegador y visita:
```
http://localhost/bookorama/
```

---

## 🎯 Funcionalidades

### 📖 Módulo de Libros (Books)
- ✅ **Agregar**: Registrar nuevos libros con ISBN, autor, título y precio
- ✅ **Buscar**: Buscar libros por ISBN, autor o título
- ✅ **Listar**: Ver todos los libros registrados
- ✅ **Editar**: Actualizar información de libros existentes
- ✅ **Eliminar**: Borrar libros de la base de datos

### 👥 Módulo de Clientes (Customers)
- ✅ **Agregar**: Registrar nuevos clientes con nombre, dirección y ciudad
- ✅ **Buscar**: Buscar clientes por ID, nombre o ciudad
- ✅ **Listar**: Ver todos los clientes registrados
- ✅ **Editar**: Actualizar información de clientes
- ✅ **Eliminar**: Borrar clientes (valida que no tengan órdenes activas)

### 🛒 Módulo de Órdenes (Orders)
- ✅ **Agregar**: Crear nuevas órdenes asociadas a clientes
- ✅ **Buscar**: Buscar órdenes por ID de orden o ID de cliente
- ✅ **Listar**: Ver todas las órdenes con información del cliente
- ✅ **Editar**: Modificar órdenes existentes
- ✅ **Eliminar**: Borrar órdenes de la base de datos

---

## 🔐 Características de Seguridad

- ✅ Uso de **Prepared Statements** (prevención de SQL Injection)
- ✅ Validación de datos en servidor
- ✅ Sanitización de salida HTML (prevención de XSS)
- ✅ Validación de integridad referencial (Foreign Keys)
- ✅ Confirmación de eliminación con JavaScript

---

## 💡 Datos de Prueba (Opcional)

Puedes insertar estos datos para probar el sistema:

```sql
-- Libros de ejemplo
INSERT INTO Books (ISBN, Author, Title, Price) VALUES
('9786073193238', 'Gabriel García Márquez', 'Cien Años de Soledad', 299.00),
('9788420412146', 'George Orwell', '1984', 249.00),
('9780307474728', 'Yuval Noah Harari', 'Sapiens', 399.00);

-- Clientes de ejemplo
INSERT INTO Customers (Name, Address, City) VALUES
('Juan Pérez', 'Av. Reforma 123', 'Ciudad de México'),
('María García', 'Calle Principal 456', 'Guadalajara'),
('Carlos López', 'Boulevard Sur 789', 'Monterrey');

-- Órdenes de ejemplo (después de insertar clientes)
INSERT INTO Orders (CustomerID, Amount, Date) VALUES
(1, 548.00, '2024-12-01'),
(2, 399.00, '2024-12-05'),
(1, 299.00, '2024-12-08');
```

---

## 📋 Notas Importantes

1. **Relación entre tablas**: Los clientes no se pueden eliminar si tienen órdenes asociadas
2. **Validaciones**: Todos los campos son obligatorios
3. **Búsquedas**: Las búsquedas en texto son parciales (LIKE '%término%')
4. **Formato de fecha**: Usar formato YYYY-MM-DD (2024-12-09)
5. **ISBN**: Debe ser único, no se permiten duplicados

---

## 🎨 Personalización

### Cambiar Colores
Edita `style.css` y modifica las variables de color:
```css
/* Color principal */
background: #4CAF50;  /* Verde actual */

/* Cambiar a azul */
background: #2196F3;
```

### Cambiar Nombre de la Base de Datos
1. Edita `db_config.php`
2. Cambia el valor de `DB_NAME`
3. Crea la nueva base de datos en MySQL

---

## 🐛 Solución de Problemas

### Error: "Could not connect to database"
- Verifica que MySQL esté ejecutándose
- Confirma las credenciales en `db_config.php`
- Asegúrate de que la base de datos exista

### Página en blanco
- Activa la visualización de errores en PHP:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```

### Caracteres extraños (ñ, acentos)
- Verifica que la codificación sea UTF-8
- Confirma el charset en MySQL: `utf8mb4`

---

## 👨‍💻 Autor

Desarrollado para el curso 5AM - Tercer Parcial  
**Alumno**: Israel Zacarías  
**Base de Datos**: 5AP3_israel_zacarias

---

## 📄 Licencia

Proyecto educativo - Libre uso para fines académicos

---

¡Listo para usar! 🚀
