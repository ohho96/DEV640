<?php
require_once 'functions.php';

try {
    // Test the connection
    $result = $pdo->query("SELECT 1");
    echo "Database connection successful!<br>";
    
    // Test if we can access the database
    $result = $pdo->query("SHOW TABLES");
    echo "Tables in database:<br>";
    while ($row = $result->fetch()) {
        echo "- " . $row[0] . "<br>";
    }
} catch (PDOException $e) {
    die("Connection test failed: " . $e->getMessage());
}
?> 