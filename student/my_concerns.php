<?php
require_once '../auth.php';
require_once '../db.php'; // Correct path

requireRole('student');

$userId = $_SESSION['user_id'];

// Fetch All Concerns for User
$stmt = $pdo->prepare("SELECT * FROM concerns WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$concerns = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Concerns - OSA System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <div class="logo-text">OSA Student Intake</div>
            <nav class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="my_concerns.php" style="color: white; font-weight: bold;">My Concerns</a>
                <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.3);"></div>
                <a href="../logout.php" class="btn btn-sm" style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem;">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="margin-top: 2rem;">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2>My Concerns</h2>
                <?php if(isset($_GET['success'])): ?>
                    <div style="background: #dcfce7; color: #15803d; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.9rem;">
                        Concern submitted successfully!
                    </div>
                <?php endif; ?>
            </div>

            <?php if (count($concerns) > 0): ?>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($concerns as $concern): ?>
                <div style="border: 1px solid #f1f5f9; padding: 1.5rem; border-radius: 0.5rem; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                    <div>
                        <div style="margin-bottom: 0.25rem; font-weight: 600; font-size: 1.1rem;">
                            <?php echo htmlspecialchars($concern['subject'] ?: 'No Subject'); ?>
                        </div>
                        <div style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">
                            <?php echo htmlspecialchars($concern['category']); ?> • Submitted on <?php echo date('M d, Y', strtotime($concern['created_at'])); ?>
                        </div>
                         <?php if($concern['status'] == 'resolved'): ?>
                            <div style="font-size: 0.85rem; color: #059669;">
                                Resolution provided.
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div style="text-align: right;">
                        <span class="status-badge status-<?php echo $concern['status']; ?>" style="margin-bottom: 0.5rem;">
                            <?php echo ucfirst(str_replace('_', ' ', $concern['status'])); ?>
                        </span>
                        <br>
                        <button 
                            class="btn btn-primary" 
                            style="margin-top: 0.5rem; font-size: 0.8rem; padding: 0.4rem 0.8rem;"
                            onclick="openModal(<?php echo htmlspecialchars(json_encode([
                                'subject' => $concern['subject'],
                                'category' => $concern['category'],
                                'detailedCategory' => $concern['detailed_category'],
                                'date' => date('M d, Y', strtotime($concern['created_at'])),
                                'assignedUnit' => $concern['assigned_unit'],
                                'description' => $concern['description'],
                                'status' => $concern['status'],
                                'urgency' => $concern['urgency']
                            ]), ENT_QUOTES, 'UTF-8'); ?>)"
                        >
                            View Details
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-light); padding: 2rem;">You haven't submitted any concerns yet.</p>
                <div style="text-align: center;">
                    <a href="submit_concern.php" class="btn btn-primary">Submit New Concern</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="../script.js"></script>

    <!-- Concern Details Modal -->
    <div id="concernModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
        <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 2rem; border: 1px solid #888; width: 90%; max-width: 600px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                <h2 id="modalSubject" style="margin: 0; font-size: 1.5rem; color: #333;">Concern Details</h2>
                <span class="close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1;">&times;</span>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <span id="modalStatus" class="status-badge" style="font-size: 0.85rem;"></span>
                <span id="modalUrgency" style="margin-left: 0.5rem; font-size: 0.85rem; padding: 0.25rem 0.5rem; border-radius: 9999px; background: #fee2e2; color: #991b1b; text-transform: capitalize;"></span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; background: #f8fafc; padding: 1rem; border-radius: 0.5rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #64748b; margin-bottom: 0.25rem;">Category</label>
                    <div id="modalCategory" style="font-weight: 500;"></div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #64748b; margin-bottom: 0.25rem;">Detailed Category</label>
                    <div id="modalDetailedCategory" style="font-weight: 500;"></div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #64748b; margin-bottom: 0.25rem;">Submitted On</label>
                    <div id="modalDate" style="font-weight: 500;"></div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #64748b; margin-bottom: 0.25rem;">Assigned Unit</label>
                    <div id="modalAssignedUnit" style="font-weight: 500;"></div>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Description</label>
                <div id="modalDescription" style="background: #fff; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; color: #475569; line-height: 1.6;"></div>
            </div>

            <div style="text-align: right; margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <button class="btn btn-secondary close-btn" style="background: #f1f5f9; color: #475569;">Close</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById("concernModal");
        const closeBtn = document.querySelector(".close");
        const closeBtnBottom = document.querySelector(".close-btn");

        function openModal(data) {
            document.getElementById('modalSubject').textContent = data.subject || 'No Subject';
            document.getElementById('modalCategory').textContent = data.category;
            document.getElementById('modalDetailedCategory').textContent = data.detailedCategory;
            document.getElementById('modalDate').textContent = data.date;
            document.getElementById('modalAssignedUnit').textContent = data.assignedUnit || 'Pending Assignment';
            document.getElementById('modalDescription').textContent = data.description;
            
            const statusEl = document.getElementById('modalStatus');
            statusEl.textContent = data.status.replace('_', ' ').charAt(0).toUpperCase() + data.status.replace('_', ' ').slice(1);
            statusEl.className = 'status-badge status-' + data.status;
            
            const urgencyEl = document.getElementById('modalUrgency');
            urgencyEl.textContent = data.urgency + ' Urgency';
            // Simple color coding for urgency
            if(data.urgency === 'high' || data.urgency === 'critical') {
                urgencyEl.style.background = '#fee2e2';
                urgencyEl.style.color = '#991b1b';
            } else if (data.urgency === 'medium') {
                urgencyEl.style.background = '#ffedd5';
                urgencyEl.style.color = '#9a3412';
            } else {
                urgencyEl.style.background = '#dcfce7';
                urgencyEl.style.color = '#166534';
            }

            modal.style.display = "block";
            // Prevent scrolling on body
            document.body.style.overflow = "hidden";
        }

        function closeModal() {
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        }

        closeBtn.onclick = closeModal;
        closeBtnBottom.onclick = closeModal;

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>

