<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit();
}

// تحديث حالة جميع الرسائل غير المقروءة إلى مقروءة للمستخدم الحالي
$user_id = $_SESSION['user_id'];

// تحديث جميع الرسائل غير المقروءة إلى مقروءة
$stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE client_id = ? AND is_read = 0");
$stmt->execute([$user_id]);

echo json_encode(['status' => 'success']);
?>
