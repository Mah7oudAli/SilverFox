<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

// تأكد من وجود مستخدم مسجل
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'يجب تسجيل الدخول لرفع التقرير.';
    header("Location: upload_report_page.php"); // اذهب إلى صفحة الرفع بعد تعيين الرسالة
    exit();
}

// الحصول على معرف العميل من الجلسة
$clientId = $_SESSION['user_id'];

// التحقق من وجود ملف
if (isset($_FILES['report']) && $_FILES['report']['error'] == 0) {
    $report = $_FILES['report'];
    
    // الحصول على نوع المعاملة
    $transactionType = $_POST['transaction'];
    
    // التأكد من أن الملف هو صورة
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($report['type'], $allowedTypes)) {
        $_SESSION['message'] = 'الرجاء تحميل صورة فقط (JPEG, PNG, GIF).';
        header("Location: upload_report_page.php");
        exit();
    }

    // تغيير اسم الصورة ليتضمن اسم العميل
    $clientName = htmlspecialchars($_SESSION['username']);
    $newFileName = $clientName . '_' . time() . '.' . pathinfo($report['name'], PATHINFO_EXTENSION);
    
    // مسار المجلد حيث سيتم تخزين الصور
    $uploadDir = '../uploads/';
    $uploadFilePath = $uploadDir . basename($newFileName);

    // تحريك الملف إلى المسار المحدد
    if (move_uploaded_file($report['tmp_name'], $uploadFilePath)) {
        // إدخال بيانات الصورة في قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO reports (client_id, file_name, transaction_type, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$clientId, $newFileName, $transactionType])) {
            $_SESSION['message'] = 'تم رفع التقرير بنجاح!';
        } else {
            $_SESSION['message'] = 'فشل في تخزين المعلومات في قاعدة البيانات.';
        }
    } else {
        $_SESSION['message'] = 'فشل في رفع الملف.';
    }
} else {
    $_SESSION['message'] = 'لم يتم رفع أي ملف.';
}

header("Location: upload_report.php"); // إعادة التوجيه إلى صفحة رفع التقارير
exit();
?>
