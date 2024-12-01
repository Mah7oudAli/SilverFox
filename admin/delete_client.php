<?php
session_start();
require_once '../includes/config.php';

if ($_SESSION['role'] != 'general_manager') {
    header("Location: ../public/index.php");
    exit();
}

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    // حذف التقارير المرتبطة بالعميل أولاً
    $stmt = $pdo->prepare("DELETE FROM reports WHERE client_id = ?");
    $stmt->execute([$clientId]);

    // الآن حذف العميل
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
    
    if ($stmt->execute([$clientId])) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'تم حذف العميل بنجاح!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'حدث خطأ أثناء حذف العميل.'];
    }
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'معرف العميل غير موجود.'];
}

header("Location: manage_clients.php");
exit();

