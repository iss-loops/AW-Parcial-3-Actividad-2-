<?php
/**
 * Test Simple de Conexión a BD
 * Script minimalista para diagnosticar problemas de conexión
 */

// Configurar para mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Test BD Simple</title>";
echo "<style>body{font-family:Arial;margin:40px;} .box{padding:15px;margin:10px 0;border-radius:5px;} ";
echo ".success{background:#d4edda;color:#155724;} .error{background:#f8d7da;color:#721c24;} ";
echo ".info{background:#d1ecf1;color:#0c5460;}</style></head><body>";

echo "<h1>🔍 Diagnóstico de Conexión a Base de Datos</h1>";

// Paso 1: Verificar extensión mysqli
echo "<div class='box info'><h2>1️⃣ Verificar extensión mysqli</h2>";
if (extension_loaded('mysqli')) {
    echo "✅ Extensión mysqli está cargada<br>";
    echo "Versión mysqli: " . mysqli_get_client_info() . "<br>";
} else {
    echo "❌ ERROR: Extensión mysqli NO está disponible<br>";
    echo "</div></body></html>";
    exit;
}
echo "</div>";

// Paso 2: Leer variables de entorno
echo "<div class='box info'><h2>2️⃣ Variables de Entorno</h2>";
$env_vars = [
    'APP_ENV' => getenv('APP_ENV'),
    'DB_HOST' => getenv('DB_HOST'),
    'DATABASE_HOST' => getenv('DATABASE_HOST'),
    'DB_PORT' => getenv('DB_PORT'),
    'DATABASE_PORT' => getenv('DATABASE_PORT'),
    'DB_NAME' => getenv('DB_NAME'),
    'DATABASE_NAME' => getenv('DATABASE_NAME'),
    'DB_USER' => getenv('DB_USER'),
    'DATABASE_USERNAME' => getenv('DATABASE_USERNAME'),
];

echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
echo "<tr><th>Variable</th><th>Valor</th></tr>";
foreach ($env_vars as $key => $value) {
    $display = $value ? htmlspecialchars($value) : '<span style="color:#999;">No configurado</span>';
    echo "<tr><td><code>$key</code></td><td>$display</td></tr>";
}
echo "</table>";

// Determinar valores finales
$db_host = getenv('DB_HOST') ?: getenv('DATABASE_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: getenv('DATABASE_PORT') ?: '3306';
$db_name = getenv('DB_NAME') ?: getenv('DATABASE_NAME') ?: 'db';
$db_user = getenv('DB_USER') ?: getenv('DATABASE_USERNAME') ?: 'root';
$db_pass = getenv('DB_PASS') ?: getenv('DATABASE_PASSWORD') ?: '';

echo "<p><strong>Valores que se usarán:</strong></p>";
echo "<ul>";
echo "<li>Host: <code>$db_host</code></li>";
echo "<li>Port: <code>$db_port</code></li>";
echo "<li>Database: <code>$db_name</code></li>";
echo "<li>User: <code>$db_user</code></li>";
echo "<li>Password: <code>" . ($db_pass ? '***' . substr($db_pass, -4) : 'No configurado') . "</code></li>";
echo "</ul>";
echo "</div>";

// Paso 3: Intentar conexión
echo "<div class='box info'><h2>3️⃣ Intentar Conexión</h2>";

$start_time = microtime(true);
echo "<p>⏱️ Iniciando conexión...</p>";
flush();

try {
    // Crear instancia
    $db = mysqli_init();
    
    if (!$db) {
        throw new Exception("mysqli_init() falló");
    }
    
    // Configurar timeout
    $db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    
    // Detectar Managed Database
    $is_managed = strpos($db_host, 'ondigitalocean.com') !== false;
    
    if ($is_managed) {
        echo "<p>🔒 Managed Database detectado - Configurando SSL...</p>";
        $db->ssl_set(NULL, NULL, NULL, NULL, NULL);
        $db->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
    }
    
    // Intentar conexión
    echo "<p>🔌 Conectando a $db_host:$db_port...</p>";
    flush();
    
    $connected = @$db->real_connect(
        $db_host,
        $db_user,
        $db_pass,
        $db_name,
        $db_port,
        NULL,
        $is_managed ? MYSQLI_CLIENT_SSL : 0
    );
    
    $elapsed = round(microtime(true) - $start_time, 2);
    
    if ($connected) {
        echo "</div><div class='box success'>";
        echo "<h2>✅ ¡CONEXIÓN EXITOSA!</h2>";
        echo "<p>Tiempo de conexión: <strong>{$elapsed} segundos</strong></p>";
        echo "<ul>";
        echo "<li>Host info: " . htmlspecialchars($db->host_info) . "</li>";
        echo "<li>MySQL version: " . htmlspecialchars($db->server_info) . "</li>";
        echo "<li>Protocol version: " . $db->protocol_version . "</li>";
        echo "<li>Charset: " . htmlspecialchars($db->character_set_name()) . "</li>";
        echo "</ul>";
        
        // Probar query simple
        echo "<h3>🧪 Probando query simple</h3>";
        $result = $db->query("SELECT 1 as test");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>✅ Query ejecutado correctamente: test = " . $row['test'] . "</p>";
        }
        
        // Verificar tablas
        echo "<h3>📊 Verificar tablas</h3>";
        $tables = $db->query("SHOW TABLES");
        if ($tables) {
            if ($tables->num_rows > 0) {
                echo "<p>Se encontraron <strong>" . $tables->num_rows . "</strong> tabla(s):</p><ul>";
                while ($row = $tables->fetch_array()) {
                    echo "<li>" . htmlspecialchars($row[0]) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>⚠️ No hay tablas en la base de datos.</p>";
            }
        }
        
        $db->close();
        echo "</div>";
        
    } else {
        throw new Exception($db->connect_error . " (Error #" . $db->connect_errno . ")");
    }
    
} catch (Exception $e) {
    $elapsed = round(microtime(true) - $start_time, 2);
    echo "</div><div class='box error'>";
    echo "<h2>❌ ERROR DE CONEXIÓN</h2>";
    echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Tiempo transcurrido:</strong> {$elapsed} segundos</p>";
    echo "<h3>🔍 Posibles causas:</h3>";
    echo "<ul>";
    echo "<li><strong>Trusted Sources no configurado:</strong> La BD bloquea conexiones desde App Platform</li>";
    echo "<li><strong>Credenciales incorrectas:</strong> Usuario o contraseña inválidos</li>";
    echo "<li><strong>Base de datos no existe:</strong> Verifica el nombre de la BD</li>";
    echo "<li><strong>SSL requerido:</strong> Managed Database requiere SSL</li>";
    echo "<li><strong>Firewall:</strong> Puerto 25060 bloqueado</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<div class='box info'>";
echo "<h2>📋 Próximos Pasos</h2>";
echo "<p>Si la conexión falló, verifica en Digital Ocean:</p>";
echo "<ol>";
echo "<li><strong>Databases</strong> → Tu BD → <strong>Settings</strong> → <strong>Trusted Sources</strong></li>";
echo "<li>Agregar <strong>App Platform</strong> → Selecciona tu app</li>";
echo "<li>Guardar y esperar 1-2 minutos</li>";
echo "<li>Recargar esta página</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>
