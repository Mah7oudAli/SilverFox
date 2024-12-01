<?php
session_start();
require_once 'config.php';
$clientId = $_SESSION['user_id'];
$employeeId = $_SESSION['employee_id'];
// التحقق من صلاحية الوصول
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];

    // استرجاع معرف الموظف المرتبط بالعميل
    $stmt = $pdo->prepare("SELECT DISTINCT employee_id FROM messages WHERE client_id = ?");
    $stmt->execute([$client_id]);
    $employee_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($employee_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'لا توجد رسائل متاحة']);
        exit();
    }

    // استرجاع الرسائل من قاعدة البيانات للموظف المرتبط بالعميل
    $placeholders = implode(',', array_fill(0, count($employee_ids), '?'));
    $stmt = $pdo->prepare("SELECT m.*, e.username AS sender_username FROM messages m JOIN employees e ON m.employee_id = e.id WHERE m.client_id = ? AND m.employee_id IN ($placeholders) ORDER BY m.created_at ASC");
    $params = array_merge([$client_id], $employee_ids);
    $stmt->execute($params);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
// تحديث حالة الرسائل عند عرضها
$stmt = $pdo->prepare("
    UPDATE messages 
    SET is_read = 1 
    WHERE employee_id = ? 
      AND client_id = ? 
      AND sender_role = 'employee'
");
$stmt->execute([$employeeId, $clientId]);

    // عرض الرسائل
    if (count($messages) > 0) {
        foreach ($messages as $message) {
            // تمييز الرسالة بناءً على من أرسلها
            $messageClass = $message['sender_role'] == 'employee' ? 'employee-message' : 'client-message';
            echo '<div class="message ' . $messageClass . '">';

            // عرض "محاسب" إذا كانت الرسالة من الموظف، و "أنت" إذا كانت من العميل
            $senderLabel = $message['sender_role'] == 'employee' ? 'محاسب' : 'أنت';
            echo '<small>' . htmlspecialchars($senderLabel) . ':</small> ';
            
            echo htmlspecialchars($message['message']);
            if (!empty($message['image_path'])) {
                echo '<br><img src="../' . htmlspecialchars($message['image_path']) . '" alt="صورة" class="message-image" width="150px" height="150px" style="margin-top:5px; cursor:pointer;">';
            }
            
            
            
            echo '<br><small class="fs-9">' . htmlspecialchars($message['created_at']) . '</small>';
            echo '</div>';
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'لا توجد رسائل متاحة']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'طلب غير صحيح']);
}

?>
