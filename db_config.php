<?php
/**
 * Configuración de Base de Datos - Book-O-Rama
 * Compatible con Digital Ocean App Platform + Managed Database
 * Incluye soporte SSL requerido por Managed Database
 */

// Detectar entorno
$is_production = (getenv('APP_ENV') === 'production') || !file_exists(__DIR__ . '/.env');

if ($is_production) {
    // PRODUCCIÓN - App Platform inyecta estas variables automáticamente
    $db_host = getenv('DB_HOST') ?: getenv('DATABASE_HOST') ?: 'localhost';
    $db_port = getenv('DB_PORT') ?: getenv('DATABASE_PORT') ?: '3306';
    $db_name = getenv('DB_NAME') ?: getenv('DATABASE_NAME') ?: 'db';
    $db_user = getenv('DB_USER') ?: getenv('DATABASE_USERNAME') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: getenv('DATABASE_PASSWORD') ?: '';
    
    // Log para debugging
    error_log("[" . date('Y-m-d H:i:s T') . "] DB Config - Host: $db_host, Port: $db_port, User: $db_user, Database: $db_name");
} else {
    // DESARROLLO LOCAL
    $db_host = 'localhost';
    $db_port = '3306';
    $db_name = '5AM_Swemy_Garcia';
    $db_user = 'root';
    $db_pass = '';
}

/**
 * Crear conexión a la base de datos
 * Con soporte SSL para Managed Database
 */
function getDBConnection() {
    global $db_host, $db_port, $db_name, $db_user, $db_pass;
    
    try {
        // Crear instancia mysqli
        $db = mysqli_init();
        
        if (!$db) {
            error_log("Error: mysqli_init failed");
            return null;
        }
        
        // Configurar timeout más corto para debugging
        $db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
        
        // Detectar si estamos en producción (Managed Database requiere SSL)
        $is_managed_db = strpos($db_host, 'ondigitalocean.com') !== false;
        
        if ($is_managed_db) {
            // Configurar SSL para Managed Database
            error_log("[" . date('Y-m-d H:i:s T') . "] Usando SSL para Managed Database");
            
            // SSL Mode: VERIFY_IDENTITY es el más seguro pero puede fallar
            // Intentamos primero con REQUIRED que es más flexible
            $db->ssl_set(NULL, NULL, NULL, NULL, NULL);
            $db->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
        }
        
        // Intentar conexión
        error_log("[" . date('Y-m-d H:i:s T') . "] Intentando conectar a: $db_host:$db_port");
        
        $connected = @$db->real_connect(
            $db_host,
            $db_user,
            $db_pass,
            $db_name,
            $db_port,
            NULL,
            $is_managed_db ? MYSQLI_CLIENT_SSL : 0
        );
        
        if (!$connected) {
            error_log("[" . date('Y-m-d H:i:s T') . "] MySQL Connection Error: " . $db->connect_error . " (Code: " . $db->connect_errno . ")");
            return null;
        }
        
        // Configurar charset
        $db->set_charset("utf8mb4");
        
        error_log("[" . date('Y-m-d H:i:s T') . "] ✅ Conexión exitosa a la base de datos");
        
        return $db;
        
    } catch (Exception $e) {
        error_log("[" . date('Y-m-d H:i:s T') . "] DB Connection Exception: " . $e->getMessage());
        return null;
    }
}

/**
 * Función para verificar conexión (para debugging)
 */
function testConnection() {
    $start_time = microtime(true);
    
    echo "<p>🔍 Iniciando test de conexión...</p>";
    
    $db = getDBConnection();
    
    $elapsed = round(microtime(true) - $start_time, 2);
    
    if ($db) {
        echo "<p>✅ <strong>Conexión exitosa</strong> (tiempo: {$elapsed}s)</p>";
        echo "<ul>";
        echo "<li><strong>Host:</strong> " . htmlspecialchars($db->host_info) . "</li>";
        echo "<li><strong>Versión MySQL:</strong> " . htmlspecialchars($db->server_info) . "</li>";
        echo "<li><strong>Charset:</strong> " . htmlspecialchars($db->character_set_name()) . "</li>";
        echo "</ul>";
        $db->close();
        return true;
    } else {
        echo "<p>❌ <strong>Error al conectar</strong> (timeout después de {$elapsed}s)</p>";
        return false;
    }
}
?>
