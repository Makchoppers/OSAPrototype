<?php
require_once '../auth.php';
requireRole('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Concern - OSA System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <div class="logo-text">OSA Student Intake</div>
            <nav class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="my_concerns.php">My Concerns</a>
                 <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.3);"></div>
                <a href="../logout.php" class="btn btn-sm" style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem;">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 2rem; max-width: 800px;">
        <div class="card">
            <h2 style="margin-bottom: 2rem; text-align: center;">Submit a New Concern</h2>
            
            <form action="submit_concern_action.php" method="POST">
                <div class="form-group">
                    <label class="form-label" for="subject">Subject (Optional)</label>
                    <input type="text" id="subject" name="subject" class="form-input" placeholder="e.g. Issue with grades">
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Describe your concerns</label>
                    <textarea id="description" name="description" class="form-textarea" placeholder="Please provide detailed information about your concern..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Category (Optional)</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Select a category...</option>
                        <option value="Academic">Academic</option>
                        <option value="Financial">Financial</option>
                        <option value="Behavioral">Behavioral</option>
                        <option value="Social">Social</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1rem;">Submit Concern</button>
                    <p style="margin-top: 1rem; font-size: 0.85rem; color: var(--text-light); font-style: italic;">
                        Note: Your concern will be analyzed using AI for classification and urgency.
                    </p>
                </div>
            </form>
        </div>
    </div>
    <script src="../script.js"></script>
</body>
</html>
