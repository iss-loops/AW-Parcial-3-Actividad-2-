-- ============================================
-- Book-O-Rama - Script de Inicialización
-- Base de Datos: 5AP3_israel_zacarias
-- ============================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS 5AP3_israel_zacarias;
USE 5AP3_israel_zacarias;

-- ============================================
-- TABLA: Books
-- Almacena información de libros
-- ============================================
CREATE TABLE IF NOT EXISTS Books (
    ISBN VARCHAR(13) PRIMARY KEY COMMENT 'ISBN del libro (clave primaria)',
    Author VARCHAR(100) NOT NULL COMMENT 'Nombre del autor',
    Title VARCHAR(200) NOT NULL COMMENT 'Título del libro',
    Price DECIMAL(10, 2) NOT NULL COMMENT 'Precio del libro',
    INDEX idx_author (Author),
    INDEX idx_title (Title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: Customers
-- Almacena información de clientes
-- ============================================
CREATE TABLE IF NOT EXISTS Customers (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID único del cliente',
    Name VARCHAR(100) NOT NULL COMMENT 'Nombre completo del cliente',
    Address VARCHAR(200) NOT NULL COMMENT 'Dirección del cliente',
    City VARCHAR(100) NOT NULL COMMENT 'Ciudad del cliente',
    INDEX idx_name (Name),
    INDEX idx_city (City)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: Orders
-- Almacena órdenes de compra
-- ============================================
CREATE TABLE IF NOT EXISTS Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID único de la orden',
    CustomerID INT NOT NULL COMMENT 'ID del cliente (clave foránea)',
    Amount DECIMAL(10, 2) NOT NULL COMMENT 'Monto total de la orden',
    Date DATE NOT NULL COMMENT 'Fecha de la orden',
    INDEX idx_customer (CustomerID),
    INDEX idx_date (Date),
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATOS DE PRUEBA (OPCIONAL)
-- Descomenta las siguientes líneas para insertar datos de ejemplo
-- ============================================


-- Insertar libros de ejemplo
INSERT INTO Books (ISBN, Author, Title, Price) VALUES
('9786073193238', 'Gabriel García Márquez', 'Cien Años de Soledad', 299.00),
('9788420412146', 'George Orwell', '1984', 249.00),
('9780307474728', 'Yuval Noah Harari', 'Sapiens: De animales a dioses', 399.00),
('9788408117117', 'Carlos Ruiz Zafón', 'La Sombra del Viento', 349.00),
('9780141439518', 'Jane Austen', 'Orgullo y Prejuicio', 199.00),
('9788499890944', 'Antoine de Saint-Exupéry', 'El Principito', 149.00),
('9788408117124', 'Arturo Pérez-Reverte', 'El Capitán Alatriste', 279.00),
('9780439708180', 'J.K. Rowling', 'Harry Potter y la Piedra Filosofal', 399.00);

-- Insertar clientes de ejemplo
INSERT INTO Customers (Name, Address, City) VALUES
('Juan Pérez Martínez', 'Av. Reforma 123, Col. Centro', 'Ciudad de México'),
('María García López', 'Calle Principal 456, Col. Moderna', 'Guadalajara'),
('Carlos López Hernández', 'Boulevard Sur 789, Col. Industrial', 'Monterrey'),
('Ana Rodríguez Sánchez', 'Av. Juárez 321, Col. Centro', 'Puebla'),
('Pedro Martínez González', 'Calle 5 de Mayo 654, Col. Centro', 'Querétaro');

-- Insertar órdenes de ejemplo (después de insertar clientes)
INSERT INTO Orders (CustomerID, Amount, Date) VALUES
(1, 548.00, '2024-11-15'),
(1, 299.00, '2024-11-20'),
(2, 399.00, '2024-11-22'),
(3, 498.00, '2024-11-25'),
(2, 149.00, '2024-12-01'),
(4, 679.00, '2024-12-03'),
(5, 399.00, '2024-12-05'),
(1, 279.00, '2024-12-08'),
(3, 199.00, '2024-12-09');

-- ============================================
-- VERIFICACIÓN
-- ============================================
SELECT 'Base de datos creada exitosamente!' as Status;
SELECT COUNT(*) as 'Total Books' FROM Books;
SELECT COUNT(*) as 'Total Customers' FROM Customers;
SELECT COUNT(*) as 'Total Orders' FROM Orders;

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
