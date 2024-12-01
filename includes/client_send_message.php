<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التحقق من وجود employee_id و user_id في الجلسة
    if (!isset($_POST['employee_id']) || !isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'بيانات غير مكتملة']);
        exit;
    }

    $employee_id = $_POST['employee_id'];
    $client_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);
    $imagePath = null;

    // التحقق من وجود صورة ورفعها
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageDir = '../uploads/';
        
        // التأكد من وجود المجلد وإنشاؤه إن لم يكن موجودًا
        // if (!is_dir($imageDir)) {
        //     mkdir($imageDir, 0777, true);
        // }
        
        // توليد اسم فريد للصورة
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $imagePath = $imageDir . $imageName;

        // التحقق من رفع الصورة بنجاح
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $imagePath = 'uploads/' . $imageName; // حفظ المسار النسبي للصورة في قاعدة البيانات
        } else {
            echo json_encode(['status' => 'error', 'message' => 'فشل في تحميل الصورة']);
            exit;
        }
    }

    // التحقق من وجود محتوى في الرسالة أو صورة
    if (!empty($message) || $imagePath) {
        // تحضير استعلام الإدخال
        $stmt = $pdo->prepare("INSERT INTO messages (client_id, employee_id, message, image_path, sender_role, created_at, sent_at, is_read) VALUES (?, ?, ?, ?, 'client', NOW(), NOW(), 0)");
        
        // تنفيذ الاستعلام
        if ($stmt->execute([$client_id, $employee_id, $message, $imagePath])) {
            echo json_encode(['status' => 'success', 'message' => 'تم إرسال الرسالة بنجاح']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'حدث خطأ أثناء إرسال الرسالة']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'يجب أن تتضمن الرسالة نصاً أو صورة']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'طلب غير صحيح']);
}
// وظيفة إرسال الإشعارات
function sendNotificationToEmployee($employee_id, $message) {
    $app_id = "99e1b7d4-ffe5-42e3-934b-ec2e62cebbc9";
    $rest_api_key = "os_v2_app_thq3pvh74vbohe2l5qxgftv3zflrax6faozuhlupuevvdxijjwxgkwcby22sttz73bsbsmothq5taqygbktxl7rhi2dd6dnf7fccydi";

    $content = array(
        "en" => $message
    );

    $fields = array(
        'app_id' => $app_id,
        'filters' => array(array("field" => "tag", "key" => "user_id", "relation" => "=", "value" => $employee_id)),
        'contents' => $content
    );

    $fields = json_encode($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ' . $rest_api_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}