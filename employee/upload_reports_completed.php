<?php
require_once '../includes/session_header.php';

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['note']) && isset($_FILES['completed_report'])) {
    $note = $_POST['note'];
    $fileName = $_FILES['completed_report']['name'];
    $fileTmpName = $_FILES['completed_report']['tmp_name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedExtensions = ['pdf', 'xlsx'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $uploadPath = '../uploads/'. $fileName;

        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            $stmt = $pdo->prepare("
                INSERT INTO tasks (employee_id, task_name, completed_report, note, status, created_at) 
                VALUES (?, ?, ?, ?, 'pending', NOW())
            ");
            $stmt->execute([$_SESSION['user_id'], "Report Submission", $uploadPath, $note]);

            $_SESSION['Done'] = 'تم رفع التقرير بنجاح للمراجعة.';
        } else {
            $_SESSION['error'] = 'فشل في رفع الملف. حاول مرة أخرى.';
        }
    } else {
        $_SESSION['error'] = 'الرجاء رفع ملف من نوع PDF أو Excel فقط.';
    }

    header("Location: manage_tasks.php");
    exit();
} else {
    $_SESSION['error'] = 'بيانات غير مكتملة.';
    header("Location: dashboard.php");
    exit();
}
