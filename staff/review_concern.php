<?php
require_once '../auth.php';
require_once '../db.php';

requireRole('staff');

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM concerns WHERE id = ?");
$stmt->execute([$id]);
$concern = $stmt->fetch();

if (!$concern) {
    die("Concern not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Concern - OSA System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <div class="logo-text">OSA Staff Portal</div>
            <nav class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.3);"></div>
                <a href="../logout.php" class="btn btn-sm" style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem;">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 2rem; max-width: 900px;">
        <div style="margin-bottom: 1rem;">
            <a href="dashboard.php" style="color: var(--text-light); font-size: 0.9rem;">&larr; Back to Dashboard</a>
        </div>
        
        <div class="card">
            <h2 style="margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">Review Concern #<?php echo $concern['id']; ?></h2>

            <div style="margin-bottom: 2rem;">
                <label class="form-label" style="color: var(--text-light);">Concern Subject</label>
                <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
                    "<?php echo htmlspecialchars($concern['subject'] ?: 'No Subject'); ?>"
                </div>

                <label class="form-label" style="color: var(--text-light);">Description</label>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-size: 0.95rem; line-height: 1.5;">
                    <?php echo nl2br(htmlspecialchars($concern['description'])); ?>
                </div>
            </div>

            <form action="update_concern_action.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $concern['id']; ?>">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Category (AI Suggested)</label>
                            <input type="text" name="category" class="form-input" value="<?php echo htmlspecialchars($concern['category']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Detailed Category</label>
                            <input type="text" name="detailed_category" class="form-input" value="<?php echo htmlspecialchars($concern['detailed_category']); ?>">
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label class="form-label">Urgency Level</label>
                            <select name="urgency" class="form-select">
                                <option value="low" <?php echo $concern['urgency'] == 'low' ? 'selected' : ''; ?>>Low</option>
                                <option value="medium" <?php echo $concern['urgency'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                                <option value="high" <?php echo $concern['urgency'] == 'high' ? 'selected' : ''; ?>>High</option>
                                <option value="critical" <?php echo $concern['urgency'] == 'critical' ? 'selected' : ''; ?>>Critical</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Assign to Unit</label>
                            <select name="assigned_unit" class="form-select">
                                <option value="OSA Main Desk" <?php echo $concern['assigned_unit'] == 'OSA Main Desk' ? 'selected' : ''; ?>>OSA Main Desk</option>
                                <option value="Guidance Office" <?php echo $concern['assigned_unit'] == 'Guidance Office' ? 'selected' : ''; ?>>Guidance Office</option>
                                <option value="Academic Affairs" <?php echo $concern['assigned_unit'] == 'Academic Affairs' ? 'selected' : ''; ?>>Academic Affairs</option>
                                <option value="Scholarship Office" <?php echo $concern['assigned_unit'] == 'Scholarship Office' ? 'selected' : ''; ?>>Scholarship Office</option>
                                <option value="Discipline Office" <?php echo $concern['assigned_unit'] == 'Discipline Office' ? 'selected' : ''; ?>>Discipline Office</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label class="form-label">Current Status</label>
                    <select name="status" class="form-select" style="font-weight: 600;">
                        <option value="received" <?php echo $concern['status'] == 'received' ? 'selected' : ''; ?>>Received</option>
                        <option value="in_review" <?php echo $concern['status'] == 'in_review' ? 'selected' : ''; ?>>In Review</option>
                        <option value="resolved" <?php echo $concern['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="dismissed" <?php echo $concern['status'] == 'dismissed' ? 'selected' : ''; ?>>Dismissed</option>
                    </select>
                </div>

                <div style="margin-top: 2rem; text-align: right;">
                    <button type="submit" class="btn btn-primary">Update Status & Details</button>
                </div>
            </form>
        </div>
    </div>
    <script src="../script.js"></script>
</body>
</html>
