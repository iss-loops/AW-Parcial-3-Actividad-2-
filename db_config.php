<?php
/*
 |--------------------------------------------------------------------------
 | Archivo: db_config.php
 | Descripción: Centraliza las credenciales y la función que crea conexiones
 |              a MySQL para que cada módulo CRUD reutilice la misma lógica.
 */

// Host donde vive MySQL (localhost cuando se trabaja de forma local)
define('DB_HOST', 'localhost');

// Usuario con permisos para manipular la base de datos del sistema
define('DB_USER', 'root');

// Contraseña asociada al usuario anterior (vacía por defecto en XAMPP)
define('DB_PASS', '');

// Nombre específico de la base de datos usada por Book-O-Rama
define('DB_NAME', '5AP3_israel_zacarias');

/*
 |--------------------------------------------------------------------------
 | Función: getDBConnection
 | Objetivo: Crear y retornar una instancia mysqli ya conectada y lista para
 |           ejecutar consultas. También valida errores de conexión y define
 |           UTF-8 como codificación para evitar problemas con acentos.
 */
function getDBConnection() {
    // Se genera el objeto de conexión usando las constantes anteriores
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Si MySQL reporta un error al conectar, detenemos el script con un mensaje
    if ($db->connect_errno) {
        die("<p>Error: No se pudo conectar a la base de datos.<br/>Por favor, intente más tarde.</p>");
    }
    
    // Forzamos UTF-8 para que los datos acentuados se guarden y muestren bien
    $db->set_charset("utf8");
    return $db;
}
?>
