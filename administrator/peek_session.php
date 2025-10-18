<?php
session_start();
header('Content-Type: text/plain; charset=utf-8');
echo "=== SESSION DUMP ===\n";
print_r($_SESSION);
echo "\n=== SERVER ===\n";
echo 'REQUEST_URI: ' . ($_SERVER['REQUEST_URI'] ?? '') . "\n";
echo 'SCRIPT_NAME: ' . ($_SERVER['SCRIPT_NAME'] ?? '') . "\n";
echo "\n=== NORMALIZED ===\n";
$level = isset($_SESSION['leveluser']) ? $_SESSION['leveluser'] : '(not set)';
echo 'leveluser raw: ' . var_export($level, true) . "\n";
echo 'leveluser normalized: ' . strtolower(trim((string)$level)) . "\n";
?>
