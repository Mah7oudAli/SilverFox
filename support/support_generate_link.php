<?php
session_start();
require '../includes/config.php'; // استدعاء ملف الاتصال

$baseURL = "http://localhost/support/reset_password.php"; // تعديل الرابط حسب البيئة المحلية

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchInput = trim($_POST['search_input']);

    if (empty($searchInput)) {
        $_SESSION['error'] = "يرجى إدخال اسم المستخدم أو رقم الهاتف.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // البحث عن المستخدم في كلا الجدولين
    $stmt = $pdo->prepare("
    SELECT id, username, mobile_phone, full_name, 'client' AS user_type 
    FROM clients 
    WHERE mobile_phone = :searchInputClient OR username LIKE :searchLikeClient
    UNION
    SELECT id, username, phone AS mobile_phone, full_name, 'employee' AS user_type 
    FROM employees 
    WHERE phone = :searchInputEmployee OR username LIKE :searchLikeEmployee
");

$searchLike = "%" . $searchInput . "%";
$stmt->execute([
    'searchInputClient' => $searchInput,
    'searchLikeClient' => $searchLike,
    'searchInputEmployee' => $searchInput,
    'searchLikeEmployee' => $searchLike
]);


    $user = $stmt->fetch();

    if ($user) {
        $userId = $user['id'];
        $full_name = $user['full_name'];
        $userType = $user['user_type'];
        $userPhone = "+963" . ltrim($user['mobile_phone'], '0'); // إضافة رمز الدولة +963 وإزالة الصفر في البداية

        // توليد رمز فريد وصلاحية 10 دقائق
        $token = bin2hex(random_bytes(16));
        $expiryTime = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        // إدخال الرابط في جدول إعادة التعيين
        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, user_type, reset_token, expires_at) VALUES (:userId, :userType, :token, :expiryTime)");
        $stmt->execute(['userId' => $userId, 'userType' => $userType, 'token' => $token, 'expiryTime' => $expiryTime]);

        $resetLink = "$baseURL?token=$token&user_id=$userId&user_type=$userType&full_name=$full_name";

        // رسالة مخصصة
        $message = "مرحبًا " . $user['full_name'] . "،\n\n"
            . "إليك رابط إعادة تعيين كلمة المرور الخاص بك. الرابط صالح لمدة 10 دقائق فقط:\n"
            . $resetLink . "\n\n"
            . "إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة.\n\n"
            . "  شكراً ، فريق دعم سلفر فوكس  ";

        $_SESSION['success'] = "تم إنشاء الرابط بنجاح.";
        $_SESSION['reset_link'] = $resetLink;
        $_SESSION['user_message'] = $message;
        $_SESSION['user_phone'] = $userPhone;

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = "لم يتم العثور على المستخدم.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة روابط إعادة تعيين كلمة المرور</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">نظام الدعم</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="#">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link" href="#">إدارة المستخدمين</a></li>
                <li class="nav-item"><a class="nav-link" href="#">سياسة الخصوصية</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">إدارة روابط إعادة تعيين كلمة المرور</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="search_input" class="form-label">اسم المستخدم أو رقم الهاتف:</label>
            <input type="text" id="search_input" name="search_input" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">إنشاء الرابط</button>
    </form>

    <?php if (isset($_SESSION['user_message'])): ?>
        <div class="mt-4">
            <label for="resetMessage" class="form-label">الرسالة:</label>
            <textarea id="resetMessage" class="form-control" rows="5" readonly><?php echo $_SESSION['user_message']; ?></textarea>
            <button onclick="copyMessage()" class="btn btn-secondary mt-3">نسخ الرسالة</button>
            <a href="https://wa.me/<?php echo $_SESSION['user_phone']; ?>" class="btn btn-success mt-3" target="_blank">إرسال عبر واتساب</a>
        </div>
        <?php unset($_SESSION['user_message'], $_SESSION['user_phone']); ?>
    <?php endif; ?>
</div>

<script>
function copyMessage() {
    var message = document.getElementById("resetMessage");
    message.select();
    document.execCommand("copy");
    alert("تم نسخ الرسالة.");
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
