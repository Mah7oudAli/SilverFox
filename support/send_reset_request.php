<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
// التحقق من المدخلات
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['full_name']);
    $nationalId = trim($_POST['national_id']);
    $phone = trim($_POST['phone']);

    if (empty($fullName) || empty($nationalId) || empty($phone)) {
        $_SESSION['error'] = "جميع الحقول مطلوبة.";
        header("Location: ../index.php");
        exit();
    }

    // استيراد PHPMailer
    

    require '../phpmailer/src/Exception.php';
    require '../phpmailer/src/PHPMailer.php';
    require '../phpmailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        // إعداد SMTP الخاص بـ Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // خادم SMTP لـ Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'tccpro24@gmail.com'; // بريدك الإلكتروني في Gmail
        $mail->Password = 'lzuj lyqs gfzy hhio'; // كلمة مرور التطبيق الخاصة بك
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // إعدادات البريد الإلكتروني
        $mail->setFrom('tccpro24@gmail.com', $full_name);
        $mail->addAddress(''); // البريد الموجه إليه
        $mail->Subject = 'طلب تغيير كلمة المرور';
        $mail->SetLanguage("ar", "phpmailer/language");
        $mail->Encoding = "base64";
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        // محتوى البريد الإلكتروني بصيغة HTML
        $mail->Body = "
        <html>
        <head>
            <title>طلب تغيير كلمة المرور</title>
        </head>
        <body>
            <h2>طلب تغيير كلمة المرور</h2>
            <p>تم تقديم طلب تغيير كلمة المرور من قبل العميل التالي:</p>
            <ul>
                <li><strong>الاسم الكامل:</strong> $fullName</li>
                <li><strong>الرقم الوطني:</strong> $nationalId</li>
                <li><strong>رقم الهاتف:</strong> $phone</li>
            </ul>
            <p>يرجى التحقق من صحة البيانات والرد بالإجراء المطلوب.</p>
        </body>
        </html>";

        // إرسال البريد الإلكتروني
        if ($mail->send()) {
            $_SESSION['message'] = "تم إرسال طلبك بنجاح إلى الدعم الفني.";
        } else {
            $_SESSION['error'] = "حدث خطأ أثناء إرسال الطلب.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "حدث خطأ أثناء إرسال الطلب. تفاصيل الخطأ: " . $mail->ErrorInfo;
    }

    // إعادة التوجيه إلى الصفحة الرئيسية أو صفحة تأكيد الإرسال
    header("Location: ../index.php");
    exit();
}
?>
