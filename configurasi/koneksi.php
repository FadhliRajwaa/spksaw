<?php
// Global timeout for heavier operations (PDF, laporan)
set_time_limit(1800);

// Lightweight .env loader for local development
function load_env_if_exists($path)
{
    if (!file_exists($path)) return;
    $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) return;
    foreach ($lines as $line) {
        if (strlen($line) === 0 || $line[0] === '#' || strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        $value = trim($value, "\"' ");
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

// Try to load .env from project root when running locally
load_env_if_exists(dirname(__DIR__) . '/.env');

// Prefer environment variables (Vercel/Aiven), fallback to local defaults
$server   = getenv('DB_HOST') ?: 'localhost';
$user     = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'spksaw';
$port     = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

// SSL options for Aiven (optional)
$sslMode        = getenv('DB_SSL_MODE') ?: '';
$sslCaBase64    = getenv('DB_SSL_CA_BASE64') ?: '';
$sslVerify      = getenv('DB_SSL_VERIFY_SERVER') ? filter_var(getenv('DB_SSL_VERIFY_SERVER'), FILTER_VALIDATE_BOOLEAN) : false;

$mysqli = mysqli_init();
mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 10);

$flags = 0;
if (!empty($sslMode)) {
    // If CA is provided (recommended by Aiven), persist it to a temp file and enable verification when requested
    if (!empty($sslCaBase64)) {
        $caFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'aiven-ca.pem';
        if (!file_exists($caFile)) {
            @file_put_contents($caFile, base64_decode($sslCaBase64));
        }
        if (function_exists('mysqli_ssl_set')) {
            @mysqli_ssl_set($mysqli, null, null, $caFile, null, null);
        }
        if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
            @mysqli_options($mysqli, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, $sslVerify);
        }
    }
    $flags |= MYSQLI_CLIENT_SSL; // Require encrypted connection
}

if (!@mysqli_real_connect($mysqli, $server, $user, $password, $database, $port, null, $flags)) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

@mysqli_set_charset($mysqli, 'utf8mb4');

// Backward compatible variable name used across the app
$koneksi = $mysqli;
?>