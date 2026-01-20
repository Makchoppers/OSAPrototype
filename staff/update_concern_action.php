<?php
require_once '../db.php';
require_once '../auth.php';
requireRole('staff');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $detailed_category = $_POST['detailed_category'];
    $urgency = $_POST['urgency'];
    $assigned_unit = $_POST['assigned_unit'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE concerns SET category = ?, detailed_category = ?, urgency = ?, assigned_unit = ?, status = ? WHERE id = ?");
        $stmt->execute([$category, $detailed_category, $urgency, $assigned_unit, $status, $id]);

        header("Location: dashboard.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Error updating concern: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
