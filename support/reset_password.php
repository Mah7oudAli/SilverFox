<?php
session_start();
require '../includes/config.php'; // استدعاء ملف الاتصال

if (!isset($_GET['token']) || !isset($_GET['user_id']) || !isset($_GET['user_type'])) {
    $_SESSION['error'] = "رابط غير صالح.";
    header("Location: ../index.php");
    exit();
}

$token = $_GET['token'];
$userId = (int)$_GET['user_id'];
$userType = $_GET['user_type'];
$full_name = $_GET['full_name'];

// حذف التوكنات منتهية الصلاحية
$current_time = date('Y-m-d H:i:s');
$stmt = $pdo->prepare("DELETE FROM password_resets WHERE expires_at <= :current_time");
$stmt->execute(['current_time' => $current_time]);

// التحقق من صلاحية التوكن
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE reset_token = :token AND user_id = :userId AND user_type = :userType AND expires_at > :current_time");
$stmt->execute(['token' => $token, 'userId' => $userId, 'userType' => $userType, 'current_time' => $current_time]);

$userReset = $stmt->fetch();

if (!$userReset) {
    $_SESSION['error'] = "رابط غير صالح أو منتهي.";
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "يرجى إدخال كلمات المرور.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "كلمات المرور غير متطابقة.";
    } else {
        // تشفير كلمة المرور الجديدة
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // تحديد الجدول بناءً على نوع المستخدم
        $table = $userType === 'client' ? 'clients' : 'employees';

        // تحديث كلمة المرور
        $stmt = $pdo->prepare("UPDATE $table SET password = :hashedPassword WHERE id = :userId");
        if ($stmt->execute(['hashedPassword' => $hashedPassword, 'userId' => $userId])) {
            // حذف التوكن بعد الاستخدام
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE reset_token = :token");
            $stmt->execute(['token' => $token]);

            $_SESSION['success'] = "تم تحديث كلمة المرور بنجاح.";
            header("Location: ../index.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء تحديث كلمة المرور. حاول مرة أخرى.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../index.php">الرئيسية</a></li>
                    <div class="text-center">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#policyModal">قراءة سياسة الخصوصية</button>
                    </div>
                </ul>
            </div>
            <a class="navbar-brand" href="#">نظام الدعم</a>
        </div>
    </nav>

    <div class="container-sm shadow-lg bg-dark text-light mt-2">
        <br>
        <h4 class="alert alert-info text-center md-2 ">إعادة تعيين كلمة المرور</h4>
        <div class="alert alert-warning text-center">
            <strong>تحذير:</strong> لا تشارك هذا الرابط مع أي شخص. رابط إعادة التعيين صالح لفترة قصيرة فقط.
        </div>


        <p>
            <b>المعرف الخاص بك هو :</b>
            <?php echo $userId; ?>

        </p>
        <p>
            <b> دورك:</b>
            <?php
            if ($userType == 'client') {
                echo " عميل لدى شركة سلفر فوكس ";
            } else {
                echo "انت موظف لدى شركة سلفر فوكس ";
            }
            ?>

        </p>
        <p>
            <b> اسمك الثلاثي :</b>
            <?php echo $full_name; ?>

        </p>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="new_password" class="form-label">كلمة المرور الجديدة:</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">تأكيد كلمة المرور:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-outline-warning w-100 mb-3">تحديث كلمة المرور</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>