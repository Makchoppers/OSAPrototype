<?php
require_once '../db.php';
require_once '../auth.php';
requireRole('student');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];

    if (empty($description)) {
        die("Description is required.");
    }

    // --- SIMULATED AI CLASSIFICATION ---
    // In a real app, we would call an LLM API here.
    // We will use simple keyword matching for the prototype.
    
    $ai_detailed_category = 'General Inquiry';
    $ai_urgency = 'low';
    $assigned_unit = 'OSA Main Desk';

    $descLen = strlen($description);
    $descLower = strtolower($description);

    // Keyword detection
    if (strpos($descLower, 'suicide') !== false || strpos($descLower, 'kill myself') !== false || strpos($descLower, 'harm') !== false) {
        $ai_urgency = 'critical';
        $category = 'Behavioral'; // Override
        $ai_detailed_category = 'Crisis Intervention';
        $assigned_unit = 'Guidance Office';
    } elseif (strpos($descLower, 'bully') !== false || strpos($descLower, 'harass') !== false) {
        $ai_urgency = 'high';
        $category = $category ?: 'Behavioral';
        $ai_detailed_category = 'Bullying/Harassment';
        $assigned_unit = 'Discipline Office';
    } elseif (strpos($descLower, 'money') !== false || strpos($descLower, 'tuition') !== false || strpos($descLower, 'scholarship') !== false) {
        $ai_urgency = 'medium';
        $category = $category ?: 'Financial';
        $ai_detailed_category = 'Financial Aid';
        $assigned_unit = 'Scholarship Office';
    } elseif (strpos($descLower, 'grade') !== false || strpos($descLower, 'exam') !== false || strpos($descLower, 'professor') !== false) {
        $ai_urgency = 'medium';
        $category = $category ?: 'Academic';
        $ai_detailed_category = 'Academic Concern';
        $assigned_unit = 'Academic Affairs';
    }

    // Default category if still empty
    if (empty($category)) {
        $category = 'Other';
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO concerns (user_id, subject, description, category, detailed_category, urgency, status, assigned_unit) VALUES (?, ?, ?, ?, ?, ?, 'received', ?)");
        $stmt->execute([$userId, $subject, $description, $category, $ai_detailed_category, $ai_urgency, $assigned_unit]);
        
        header("Location: my_concerns.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Error submitting concern: " . $e->getMessage());
    }

} else {
    header("Location: submit_concern.php");
    exit();
}
?>
