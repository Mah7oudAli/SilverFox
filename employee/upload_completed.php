<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// التأكد من أن متغير البيئة تم تعريفه
$env = 'local'; // أو 's3' حسب الحالة

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['completed_report'])) {
    $taskId = $_POST['task_id'];
    $fileTmp = $_FILES['completed_report']['tmp_name'];
    $fileName = $_FILES['completed_report']['name'];

    // رفع التقرير إلى S3 أو التخزين المحلي
    if ($env == 'local') {
        $completedReportLink = uploadFile($fileTmp, $fileName); // تأكد من أن الدالة هنا هي uploadFile
    } 

    // تحديث حالة المهمة في قاعدة البيانات
    if ($completedReportLink) { // تحقق من نجاح الرفع
        $stmt = $pdo->prepare("UPDATE tasks SET status = 'completed', completed_report = ? WHERE id = ?");
        $stmt->execute([$completedReportLink, $taskId]);
        header("Location: dashboard.php");
    } else {
        echo "Error uploading completed report.";
    }
}
?>
