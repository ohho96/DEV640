<?php
// Display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set execution time limit
set_time_limit(60);

echo "Testing MySQL connection...<br>";

try {
    $host = 'localhost:8888';
    $user = 'root';
    $pass = '';
    
    // First try connecting without database
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    echo "Basic connection successful!<br>";
    
    // Try to create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS robinsnest");
    echo "Database 'robinsnest' created or already exists.<br>";
    
    // Try connecting to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=robinsnest", $user, $pass);
    echo "Connection to 'robinsnest' database successful!<br>";
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "<br>Please check your MySQL settings.");
}
?> 