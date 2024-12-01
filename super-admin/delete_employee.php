<?php
session_start();
require_once '../includes/config.php';

if ($_SESSION['role'] != 'general_manager') {
    header("Location: ../public/index.php");
    exit();
}

// حذف الموظف بناءً على المعرف
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    
    if ($stmt->execute([$employeeId])) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'تم حذف الموظف بنجاح!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'حدث خطأ أثناء الحذف.'];
    }
    header("Location: manage_clients.php"); // توجيه المستخدم بعد الحذف
    exit();
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'معرف الموظف غير موجود.'];
    header("Location: manage_clients.php");
    exit();
}
?>
