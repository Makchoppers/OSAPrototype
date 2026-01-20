<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['role'] !== $role) {
        // Redirect to appropriate dashboard if wrong role
        if ($_SESSION['role'] === 'student') {
            header("Location: student/dashboard.php");
        } else {
            header("Location: staff/dashboard.php");
        }
        exit();
    }
}

function getCurrentUser() {
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
}
?>
