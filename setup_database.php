<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect without database selected
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS osa_system");
    echo "Database 'osa_system' created or already exists.<br>";
    
    // Select Database
    $pdo->exec("USE osa_system");

    // Create Users Table
    $usersSql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('student', 'staff') NOT NULL DEFAULT 'student',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($usersSql);
    echo "Table 'users' created.<br>";

    // Create Concerns Table
    $concernsSql = "CREATE TABLE IF NOT EXISTS concerns (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        subject VARCHAR(255),
        description TEXT NOT NULL,
        category VARCHAR(100),
        detailed_category VARCHAR(100),
        urgency ENUM('low', 'medium', 'high', 'critical') DEFAULT 'low',
        status ENUM('received', 'in_review', 'resolved', 'dismissed') DEFAULT 'received',
        assigned_unit VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($concernsSql);
    echo "Table 'concerns' created.<br>";

    // Seed Users (Password is 'password123')
    $password = password_hash('password123', PASSWORD_BCRYPT);
    
    // Check if users exist before inserting
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'student@osa.edu'");
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['John Student', 'student@osa.edu', $password, 'student']);
        $stmt->execute(['Jane Staff', 'staff@osa.edu', $password, 'staff']);
        echo "Sample users created (student@osa.edu / password123, staff@osa.edu / password123).<br>";
    }

    // Seed Concerns
    $stmt = $pdo->query("SELECT id FROM users WHERE email = 'student@osa.edu'");
    $studentId = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM concerns");
    if ($stmt->fetchColumn() == 0 && $studentId) {
        $concerns = [
            [$studentId, 'Issue with professor', 'My professor for Math 101 is not replying to emails.', 'Academic', 'Faculty Issue', 'medium', 'in_review', 'Academic Affairs'],
            [$studentId, 'Financial aid question', 'When will the scholarship stipend be released?', 'Financial', 'Scholarship', 'low', 'resolved', 'Scholarship Office'],
            [$studentId, 'Feeling stressed', 'I am feeling overwhelmed with my workload.', 'Behavioral', 'Mental Health', 'high', 'received', 'Guidance Office']
        ];

        $stmt = $pdo->prepare("INSERT INTO concerns (user_id, subject, description, category, detailed_category, urgency, status, assigned_unit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($concerns as $concern) {
            $stmt->execute($concern);
        }
        echo "Sample concerns inserted.<br>";
    }

    echo "<b>Database setup completed successfully!</b>";

} catch (PDOException $e) {
    die("DB Setup Failed: " . $e->getMessage());
}
?>
