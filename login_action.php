<?php
require_once 'db.php';
require_once 'auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'staff') {
                header("Location: staff/dashboard.php");
            } else {
                header("Location: student/dashboard.php");
            }
            exit();
        } else {
            // Login Failed
            header("Location: login.php?error=invalid");
            exit();
        }
    } catch (PDOException $e) {
        // Log error and redirect
        // error_log($e->getMessage());
        header("Location: login.php?error=db");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
