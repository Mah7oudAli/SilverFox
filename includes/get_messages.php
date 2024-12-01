<?php
session_start();
require_once '../includes/config.php';

// التحقق من صلاحية الوصول
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];

    // استرجاع الرسائل من قاعدة البيانات
    $stmt = $pdo->prepare("SELECT m.*, e.username AS sender_username FROM messages m JOIN employees e ON m.employee_id = e.id WHERE m.client_id = ? ORDER BY m.created_at ASC");
    $stmt->execute([$client_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $message) {
        // تمييز الرسالة بناءً على من أرسلها
        $messageClass = $message['sender_role'] == 'employee' ? 'employee-message' : 'client-message';
        echo '<div class="message ' . $messageClass . '">';

        // عرض "محاسب" إذا كانت الرسالة من الموظف، و "أنت" إذا كانت من العميل
        $senderLabel = $message['sender_role'] == 'employee' ? 'انت ' : 'العميل ';
        echo '<small >' . htmlspecialchars($senderLabel) . ':</small> ';
        
        echo htmlspecialchars($message['message']);
        if (!empty($message['image_path'])) {
            echo '<br><img src="../' . htmlspecialchars($message['image_path']) . '" alt="صورة" class="message-image" width="150px" height="150px" style="margin-top:5px;">';
        }
        echo '<br><small class="fs-9">' . htmlspecialchars($message['created_at']) . '</small>';
        echo '</div>';
    }
}
?>