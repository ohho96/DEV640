<?php
// Display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Connect to MySQL without specifying a database
    $pdo = new PDO("mysql:host=localhost", "root", "<Strong>012");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS fantastic6");
    echo "Database 'fantastic6' created successfully!<br>";
    
    // Select the database
    $pdo->exec("USE fantastic6");
    
    // Drop existing tables if they exist
    $pdo->exec("DROP TABLE IF EXISTS members");
    $pdo->exec("DROP TABLE IF EXISTS messages");
    $pdo->exec("DROP TABLE IF EXISTS friends");
    $pdo->exec("DROP TABLE IF EXISTS profiles");
    
    // Create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(16) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        auth VARCHAR(16) NOT NULL,
        recip VARCHAR(16) NOT NULL,
        pm CHAR(1) NOT NULL,
        time INT UNSIGNED NOT NULL,
        message VARCHAR(4096) NOT NULL,
        INDEX(auth(6)),
        INDEX(recip(6))
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS friends (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(16) NOT NULL,
        friend VARCHAR(16) NOT NULL,
        UNIQUE KEY unique_friendship (username, friend)
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(16) NOT NULL UNIQUE,
        text TEXT,
        INDEX(username(6))
    )");
    
    echo "All tables created successfully!<br>";
    echo "<a href='index.php'>Go to the main page</a>";
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?> 