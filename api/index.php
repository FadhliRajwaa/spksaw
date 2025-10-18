<?php
// Simple router for Vercel PHP runtime to serve the legacy app
// All routes are directed here via vercel.json unless matched as static assets

// Project root (directory containing this api/ folder)
$ROOT = dirname(__DIR__);

// Normalize requested path
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = $uri ?: '/';

// Helper to execute a PHP entry file as if it was requested directly
function run_php_entry($target)
{
    // Use ephemeral /tmp for PHP sessions in serverless environment
    if (function_exists('sys_get_temp_dir')) {
        @ini_set('session.save_path', sys_get_temp_dir());
    }
    // Adjust server vars so relative paths and links behave
    $_SERVER['SCRIPT_FILENAME'] = $target;
    $_SERVER['SCRIPT_NAME'] = '/' . ltrim(str_replace(getcwd(), '', $target), '/');
    $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];

    chdir(dirname($target));
    require $target;
    return true;
}

// Routing rules
$entry = null;
if ($uri === '/' || $uri === '/index.php' || $uri === '/api' || $uri === '/api/' || $uri === '/api/index.php') {
    // Home → admin dashboard
    $entry = $ROOT . '/administrator/index.php';
} elseif (strpos($uri, '/administrator') === 0) {
    $path = $ROOT . $uri;
    if (is_dir($path)) {
        $entry = rtrim($path, '/') . '/index.php';
    } elseif (is_file($path) && substr($path, -4) === '.php') {
        $entry = $path;
    } else {
        // Fallback to admin index for any /administrator path
        $entry = $ROOT . '/administrator/index.php';
    }
} else {
    // Any other path → admin index (the app is admin-first)
    $entry = $ROOT . '/administrator/index.php';
}

if ($entry && file_exists($entry)) {
    run_php_entry($entry);
    return;
}

http_response_code(404);
header('Content-Type: text/plain');
echo "Not Found";
