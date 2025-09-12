<?php
// Simple debug for perankingan module
session_start();
echo "<h1>DEBUG: Perankingan Module Test</h1>";
echo "<p>Session Level: " . ($_SESSION['leveluser'] ?? 'NOT SET') . "</p>";
echo "<p>Module Parameter: " . ($_GET['module'] ?? 'NOT SET') . "</p>";

// Test the ranking.php file include
echo "<h2>Testing ranking.php file inclusion:</h2>";

if(file_exists("modul/mod_ranking/ranking.php")) {
    echo "<p>✅ File exists: modul/mod_ranking/ranking.php</p>";
    
    // Check for PHP syntax errors
    $output = shell_exec("php -l modul/mod_ranking/ranking.php 2>&1");
    echo "<p><strong>PHP Syntax Check:</strong><br><pre>$output</pre></p>";
    
    if(strpos($output, 'No syntax errors') !== false) {
        echo "<p>✅ No PHP syntax errors detected</p>";
        echo "<h3>Including ranking.php:</h3>";
        
        // Include the file and catch any errors
        ob_start();
        try {
            include "modul/mod_ranking/ranking.php";
            $content = ob_get_contents();
        } catch (Exception $e) {
            $content = "ERROR: " . $e->getMessage();
        }
        ob_end_clean();
        
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo $content;
        echo "</div>";
    } else {
        echo "<p>❌ PHP syntax errors found!</p>";
    }
} else {
    echo "<p>❌ File does not exist: modul/mod_ranking/ranking.php</p>";
}
?>
