<?php
/**
 * Database Configuration
 * Travel Explorer / Voyagely
 * 
 * Establishes MySQLi connection to travel_explorer database
 * Credentials: localhost, root (no password), travel_explorer
 * 
 * Usage: require_once('config/db.php'); to include in any page
 */

// Database credentials
$db_host = 'localhost';
$db_user = 'root';
$db_password = ''; // Empty password (default XAMPP/WAMP setup)
$db_name = 'travel_explorer';

// Create connection using MySQLi
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Optional: suppress warnings in development (comment out for production)
mysqli_report(MYSQLI_REPORT_OFF);