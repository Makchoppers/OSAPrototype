<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password is empty
$dbname = 'osa_system';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If database not found, we might be running the setup script, so ignore or handle
    if ($e->getCode() == 1049) {
        // Database doesn't exist yet
    } else {
        die("Database connection failed: (" . $e->getCode() . ") " . $e->getMessage());
    }
}
?>
