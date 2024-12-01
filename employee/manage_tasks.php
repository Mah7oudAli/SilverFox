<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

// التحقق من صلاحية الوصول للموظف
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'accountant') {
    header("Location: /public/index.php");
    exit();
}

// التحقق من وجود معرف الموظف في الجلسة
if (!isset($_SESSION['user_id'])) {
    echo 'لم يتم تسجيل الدخول بشكل صحيح.';
    exit();
}

// جلب بيانات الموظف من جدول المستخدمين
$stmt = $pdo->prepare("SELECT username FROM employees WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$employee = $stmt->fetch();

if (!$employee) {
    echo 'لا يوجد موظف بهذا المعرف.';
    exit();
}

// جلب المهام والتقارير اليومية الخاصة بالموظف مع اسم العميل
$stmt_reports = $pdo->prepare("
SELECT r.id, c.username AS username, r.file_name, r.transaction_type, r.created_at
FROM reports r
JOIN clients c ON r.client_id = c.id
WHERE c.employee_id = ?
");
$stmt_reports->execute([$_SESSION['user_id']]);
$reports = $stmt_reports->fetchAll();


?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الموظف</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/chate_designe.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">الصفحة الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_tasks.php">تسليم تقرير </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">تسجيل الخروج</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">مرحباً، <?php echo htmlspecialchars($employee['username']); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4 shadow-lg p-5">
        <?php
        // عرض الرسائل في الجلسة (إذا كانت موجودة)

        if (isset($_SESSION['Done'])) {
            echo '<div class="alert alert-info text-center">' . $_SESSION['Done'] . '</div>';
            unset($_SESSION['Done']); 
            // مسح الرسالة بعد عرضها
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger text-center">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
             // مسح الرسالة بعد عرضها
        }
        ?>
        <form action="upload_reports_completed.php" method="POST" enctype="multipart/form-data" class="mt-3">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
            <div class="mb-3">
                <label for="completed_report" class="form-label">رفع التقرير المكتمل (PDF أو Excel)</label>
                <input type="file" name="completed_report" accept=".pdf, .xlsx" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">إضافة ملاحظة</label>
                <textarea name="note" class="form-control" placeholder="أضف ملاحظة حول التقرير" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-outline-info text-dark w-100">إرسال التقرير المكتمل</button>
        </form>
    </div>
    <footer class="text-center text-lg-start ">
        <div class="text-center p-3">
            <b>سلفر فوكس</b>
            <span> 2024 حقوق النشر محفوظة &copy; </span>
            <a class="text-light" href="https://MahmoudAli.Nadim.pro">

                <b> <i>By:</i> </b>

                <strong>Mahmoud Ali Abu Shanab</strong>
            </a>
        </div>
    </footer>
</body>

</html>