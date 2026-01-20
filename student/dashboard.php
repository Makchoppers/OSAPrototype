<?php
require_once '../auth.php';
require_once '../db.php';

requireRole('student');

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

// Fetch Stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM concerns WHERE user_id = ?");
$stmt->execute([$userId]);
$totalConcerns = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM concerns WHERE user_id = ? AND status != 'resolved' AND status != 'dismissed'");
$stmt->execute([$userId]);
$pendingConcerns = $stmt->fetchColumn();

// Fetch Recent Concerns
$stmt = $pdo->prepare("SELECT subject, status, created_at FROM concerns WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$userId]);
$recentConcerns = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - OSA System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <div class="logo-text">OSA Student Intake</div>
            <nav class="nav-links">
                <a href="dashboard.php" style="color: white; font-weight: bold;">Dashboard</a>
                <a href="my_concerns.php">My Concerns</a>
                <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.3);"></div>
                <span style="font-size: 0.9rem;">Hi, <?php echo htmlspecialchars($userName); ?></span>
                <a href="../logout.php" class="btn btn-sm" style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem;">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem; align-items: stretch;">
            <div class="stat-card" style="flex: 1; min-width: 200px;">
                <div>
                    <div class="stat-number"><?php echo $totalConcerns; ?></div>
                    <div class="stat-label">Total Concerns</div>
                </div>
            </div>
            <div class="stat-card" style="flex: 1; min-width: 200px; border-left: 4px solid #f59e0b;">
                <div>
                    <div class="stat-number"><?php echo $pendingConcerns; ?></div>
                    <div class="stat-label">Pending Concerns</div>
                </div>
            </div>
            <div style="flex: 1; min-width: 200px; display: flex; align-items: stretch;">
                 <a href="submit_concern.php" class="btn btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: var(--shadow-sm);">
                    <span style="font-size: 1.5rem; margin-right: 0.5rem; font-weight: 300;">+</span> Submit New Concern
                </a>
            </div>
        </div>

        <div class="card">
            <h3>Recent Concerns</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem 0.5rem; color: var(--text-light); font-weight: 600; font-size: 0.9rem;">Subject</th>
                        <th style="padding: 1rem 0.5rem; color: var(--text-light); font-weight: 600; font-size: 0.9rem;">Status</th>
                        <th style="padding: 1rem 0.5rem; color: var(--text-light); font-weight: 600; font-size: 0.9rem;">Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentConcerns as $concern): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem 0.5rem; font-weight: 500;"><?php echo htmlspecialchars($concern['subject'] ?: 'No Subject'); ?></td>
                        <td style="padding: 1rem 0.5rem;">
                            <span class="status-badge status-<?php echo $concern['status']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $concern['status'])); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem 0.5rem; color: var(--text-light); font-size: 0.9rem;">
                            <?php echo date('M d, Y', strtotime($concern['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($recentConcerns) === 0): ?>
                        <tr><td colspan="3" style="padding: 2rem; text-align: center; color: var(--text-light);">No concerns submitted yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../script.js"></script>
</body>
</html>
