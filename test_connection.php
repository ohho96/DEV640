<?php
require_once 'functions.php';

try {
    // Test the connection
    $result = queryMysql("SHOW TABLES");
    echo "Successfully connected to database!<br>";
    echo "Available tables:<br>";
    while ($row = $result->fetch()) {
        echo "- " . $row['Tables_in_fantastic6'] . "<br>";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?> 