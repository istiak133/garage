<?php
include 'config.php';

echo "<h1>Database Connection Test</h1>";

// Check connection
if (!$conn->connect_error) {
    echo "<p style='color:green'>Database connection successful!</p>";
    
    // Check if tables exist
    $tables = ["mechanics", "appointments"];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<p style='color:green'>Table '$table' exists.</p>";
            
            // Check row count
            $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
            $count = $count_result->fetch_assoc()['count'];
            echo "<p>Table '$table' has $count rows.</p>";
            
            // Show table structure
            echo "<h3>Structure of '$table' table:</h3>";
            $struct_result = $conn->query("DESCRIBE $table");
            echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            while ($row = $struct_result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:red'>Table '$table' does not exist!</p>";
        }
        echo "<hr>";
    }
    
    // Check PHP errors
    echo "<h3>PHP Error Reporting</h3>";
    echo "<p>Current error reporting level: " . error_reporting() . "</p>";
    if (ini_get('display_errors')) {
        echo "<p style='color:green'>Display errors is ON</p>";
    } else {
        echo "<p style='color:orange'>Display errors is OFF</p>";
    }
    
} else {
    echo "<p style='color:red'>Database connection failed: " . $conn->connect_error . "</p>";
}

// Check Apache and PHP configuration
echo "<h3>Server Information</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
?>
