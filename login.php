<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'staff') {
        header("Location: staff/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OSA Student Concern System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="https://via.placeholder.com/60/0d4b9f/ffffff?text=OSA" alt="Logo" style="border-radius: 50%; margin-bottom: 1rem;">
                <h1 class="auth-title">Login</h1>
                <p class="auth-subtitle">AI-Powered Student Concern Intake System</p>
            </div>
            
            <?php if(isset($_GET['error'])): ?>
                <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    Invalid email or password.
                </div>
            <?php endif; ?>

            <form action="login_action.php" method="POST">
                <div class="form-group" style="text-align: left;">
                    <label class="form-label" for="email">Student ID / Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="student@osa.edu" required>
                </div>
                
                <div class="form-group" style="text-align: left;">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem;">Login</button>
            </form>
            
            <div style="margin-top: 1.5rem; font-size: 0.85rem; color: #6b7280;">
                Login as Student or OSA Staff.
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
