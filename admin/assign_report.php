<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reportId = $_POST['report_id'];
    $employeeId = $_POST['employee_id'];

    // تعيين التقرير إلى الموظف
    $stmt = $pdo->prepare("INSERT INTO tasks (employee_id, task_name, report_id, status) VALUES (?, 'Review Report', ?, 'pending')");
    $stmt->execute([$employeeId, $reportId]);

    // تحديث حالة التقرير إلى "قيد العمل"
    $stmt = $pdo->prepare("UPDATE reports SET status = 'in_progress' WHERE id = ?");
    $stmt->execute([$reportId]);

    header("Location: manage_reports.php");
}
?>
