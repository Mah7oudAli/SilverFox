<?php
// employee/chat.php
require_once 'config.php';

// تحقق من أن الموظف مسجل الدخول
if (!isset($_SESSION['employee_id']) || !isset($_GET['user_id'])) {
    echo "الموارد المطلوبة غير متاحة.";
    exit();
}

$employee_id = $_SESSION['employee_id'];
$client_id = $_GET['client_id'];

// جلب بيانات العميل وحالة النشاط
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    echo "العميل غير موجود.";
    exit();
}

// جلب الرسائل بين الموظف والعميل
$stmt = $pdo->prepare("SELECT * FROM messages 
    WHERE (employee_id = ? AND client_id = ?)
    ORDER BY created_at ASC
");
$stmt->execute([$employee_id, $client_id]);
$messages = $stmt->fetchAll();
?>
