<?php
// employee/chat.php
require_once 'config.php';

// تحقق من أن الموظف مسجل الدخول
if (!isset($_SESSION['employee_id']) || !isset($_GET['client_id'])) {
    echo "الموارد المطلوبة غير متاحة.";
    exit();
}

$employee_id = $_SESSION['employee_id'];
$client_id = $_GET['client_id'];

// جلب بيانات العميل وآخر نشاط
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    echo "العميل غير موجود.";
    exit();
}

// تحديث last_active عند فتح صفحة الدردشة
$stmt = $pdo->prepare("UPDATE clients SET last_active = NOW() WHERE id = ?");
$stmt->execute([$client_id]);

// حساب حالة العميل بناءً على last_active
$now = new DateTime();
$last_active = new DateTime($client['last_active']);
$interval = $now->getTimestamp() - $last_active->getTimestamp();
$is_active = ($interval <= 300) ? 'نشط الآن' : 'غير نشط';

// جلب الرسائل بين الموظف والعميل
$stmt = $pdo->prepare("SELECT * FROM messages WHERE (employee_id = ? AND client_id = ?) ORDER BY created_at ASC");
$stmt->execute([$employee_id, $client_id]);
$messages = $stmt->fetchAll();
