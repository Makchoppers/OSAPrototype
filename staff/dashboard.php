<?php
require_once '../auth.php';
require_once '../db.php';

requireRole('staff');

$userName = $_SESSION['user_name'];

// Fetch Stats
$stmt = $pdo->query("SELECT COUNT(*) FROM concerns WHERE status = 'received'");
$newConcernsCount = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM concerns WHERE urgency = 'high' OR urgency = 'critical'");
$highUrgencyCount = $stmt->fetchColumn();

// Fetch Recent Concerns
$stmt = $pdo->query("SELECT * FROM concerns ORDER BY created_at DESC LIMIT 10");
$recentConcerns = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - OSA System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <div class="logo-text">OSA Staff Portal</div>
            <nav class="nav-links">
                <a href="dashboard.php" style="color: white; font-weight: bold;">Dashboard</a>
                <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.3);"></div>
                <span style="font-size: 0.9rem;">Officer <?php echo htmlspecialchars($userName); ?></span>
                <a href="../logout.php" class="btn btn-sm" style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem;">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 2rem;">
        <h1 style="margin-bottom: 2rem;">OSA Staff Dashboard</h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card" style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary-color);"><?php echo $newConcernsCount; ?></div>
                <div style="font-weight: 500; color: var(--text-light);">New Concerns<br><span style="font-size:0.8rem;">Needs Review</span></div>
            </div>
            <div class="card" style="display: flex; align-items: center; gap: 1rem; border-left: 4px solid #ef4444;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #ef4444;"><?php echo $highUrgencyCount; ?></div>
                <div style="font-weight: 500; color: var(--text-light);">High Urgency Alerts<br><span style="font-size:0.8rem;">Action Required</span></div>
            </div>
        </div>

        <div class="card">
            <h3>Incoming Concerns</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 1rem; min-width: 600px;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                            <th style="padding: 1rem 0.5rem; color: var(--text-light); font-size: 0.85rem;">Concern</th>
                            <th style="padding: 1rem 0.5rem; color: var(--text-light); font-size: 0.85rem;">AI Suggested</th>
                            <th style="padding: 1rem 0.5rem; color: var(--text-light); font-size: 0.85rem;">Urgency</th>
                            <th style="padding: 1rem 0.5rem; color: var(--text-light); font-size: 0.85rem;">Assigned Unit</th>
                            <th style="padding: 1rem 0.5rem; color: var(--text-light); font-size: 0.85rem;">Status</th>
                            <th style="padding: 1rem 0.5rem; color: var(--text-light); font-size: 0.85rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentConcerns as $concern): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.8rem 0.5rem; font-weight: 500;">
                                <?php echo htmlspecialchars($concern['subject'] ?: 'No Subject'); ?>
                                <div style="font-size: 0.8rem; color: var(--text-light); margin-top: 0.2rem; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo htmlspecialchars($concern['description']); ?>
                                </div>
                            </td>
                            <td style="padding: 0.8rem 0.5rem;">
                                <span style="background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                                    <?php echo htmlspecialchars($concern['detailed_category'] ?: $concern['category']); ?>
                                </span>
                            </td>
                            <td style="padding: 0.8rem 0.5rem;">
                                <span class="urgency-<?php echo $concern['urgency']; ?>"><?php echo ucfirst($concern['urgency']); ?></span>
                            </td>
                            <td style="padding: 0.8rem 0.5rem; font-size: 0.9rem;"><?php echo htmlspecialchars($concern['assigned_unit'] ?: '-'); ?></td>
                            <td style="padding: 0.8rem 0.5rem;">
                                <span class="status-badge status-<?php echo $concern['status']; ?>" style="font-size: 0.7rem;">
                                    <?php echo ucfirst(str_replace('_', ' ', $concern['status'])); ?>
                                </span>
                            </td>
                            <td style="padding: 0.8rem 0.5rem;">
                                <a href="review_concern.php?id=<?php echo $concern['id']; ?>" class="btn btn-sm btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Review</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="../script.js"></script>
</body>
</html>
