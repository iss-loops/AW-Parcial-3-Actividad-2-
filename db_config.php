<?php
/**
 * Configuración de Base de Datos - ALBA Sistema de Gestión
 * Compatible con Digital Ocean App Platform y localhost
 */

// Detectar entorno
$is_production = getenv('APP_ENV') === 'production';

if ($is_production) {
    // PRODUCCIÓN - Digital Ocean App Platform
    // App Platform puede usar DB_USER o DB_USERNAME, DB_NAME o DB_DATABASE
    define('DB_HOST', getenv('DB_HOST') ?: getenv('DATABASE_HOST') ?: 'localhost');
    define('DB_PORT', getenv('DB_PORT') ?: getenv('DATABASE_PORT') ?: '25060');
    
    // Soporta ambos formatos de nombre de variable
    define('DB_USER', getenv('DB_USER') ?: getenv('DB_USERNAME') ?: getenv('DATABASE_USERNAME') ?: 'root');
    define('DB_PASS', getenv('DB_PASS') ?: getenv('DB_PASSWORD') ?: getenv('DATABASE_PASSWORD') ?: '');
    define('DB_NAME', getenv('DB_NAME') ?: getenv('DB_DATABASE') ?: getenv('DATABASE_NAME') ?: '5AP3_israel_zacarias');
    
    // Log para debugging (solo en producción)
    error_log("DB Config - Host: " . DB_HOST . ", Port: " . DB_PORT . ", User: " . DB_USER . ", Database: " . DB_NAME);
    
    // Desactivar display de errores en producción
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    
} else {
    // DESARROLLO - localhost
    define('DB_HOST', 'localhost');
    define('DB_PORT', '3306');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', '5AP3_israel_zacarias');
    
    // Activar errores en desarrollo
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

/**
 * Función para conectar a la base de datos
 * @return mysqli Objeto de conexión MySQL
 */
function getDBConnection() {
    // Intentar conexión con puerto
    $db = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Si falla con puerto, intentar sin puerto (para compatibilidad)
    if ($db->connect_errno && DB_PORT != 3306) {
        $db = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    
    // Verificar conexión
    if ($db->connect_errno) {
        // Log del error para debugging
        $error_msg = "Database connection failed: " . $db->connect_error . 
                     " (Host: " . DB_HOST . ":" . DB_PORT . 
                     ", User: " . DB_USER . 
                     ", DB: " . DB_NAME . ")";
        error_log($error_msg);
        
        if (ini_get('display_errors')) {
            // Desarrollo: mensaje detallado
            die("<div style='padding:20px; background:#f44336; color:white; font-family:monospace;'>
                 <h2>❌ Error de Conexión a Base de Datos</h2>
                 <p><strong>Error:</strong> " . htmlspecialchars($db->connect_error) . "</p>
                 <p><strong>Host:</strong> " . htmlspecialchars(DB_HOST) . ":" . DB_PORT . "</p>
                 <p><strong>Usuario:</strong> " . htmlspecialchars(DB_USER) . "</p>
                 <p><strong>Base de datos:</strong> " . htmlspecialchars(DB_NAME) . "</p>
                 <p style='margin-top:15px; padding:10px; background:rgba(0,0,0,0.3);'>
                 <strong>Verifica:</strong><br>
                 1. Que las variables de entorno estén configuradas correctamente<br>
                 2. Que la base de datos '" . htmlspecialchars(DB_NAME) . "' exista<br>
                 3. Que el usuario tenga permisos<br>
                 4. Que database.sql haya sido importado
                 </p>
                 </div>");
        } else {
            // Producción: mensaje genérico
            die("<div style='text-align:center; padding:50px; font-family:Arial;'>
                 <h2 style='color:#f44336;'>⚠️ Error de Conexión</h2>
                 <p>No se pudo conectar a la base de datos.</p>
                 <p>Por favor, contacte al administrador del sistema.</p>
                 <p style='margin-top:20px; font-size:12px; color:#999;'>Error ID: " . time() . "</p>
                 </div>");
        }
    }
    
    // Configurar charset
    $db->set_charset("utf8mb4");
    
    return $db;
}

/**
 * Función helper para verificar configuración (debugging)
 */
function getDBConfigInfo() {
    if (!ini_get('display_errors')) {
        return "Información no disponible en producción";
    }
    
    return [
        'host' => DB_HOST,
        'port' => DB_PORT,
        'user' => DB_USER,
        'database' => DB_NAME,
        'environment' => getenv('APP_ENV') ?: 'development'
    ];
}
?>
