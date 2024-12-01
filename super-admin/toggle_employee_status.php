<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['task_id'];
    $status = $_POST['status'];

    // تحديث حالة المهمة إلى "تم الإنجاز بالكامل"
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->execute([$status, $taskId]);

    header("Location: completed_reports.php");
}
?>
