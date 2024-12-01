<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

// التحقق من صلاحية الوصول للمشرف
if ($_SESSION['role'] != 'general_manager') {
    header("Location: /public/index.php");
    exit();
}

// جلب المهام التي تحتاج للمراجعة
$stmt = $pdo->query("SELECT t.*, u.username as employee_name, r.report_link 
    FROM tasks t 
    LEFT JOIN users u ON t.employee_id = u.id 
    LEFT JOIN reports r ON t.report_id = r.id 
    WHERE t.status = 'review_pending'
");
$tasks = $stmt->fetchAll();

// تحديث حالة المهمة بعد المراجعة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $report_id = $_POST['report_id'];
    $new_status = $_POST['status']; // الحالة الجديدة

    // تحديث حالة المهمة
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $task_id]);

    // إذا تم تحديد "مكتمل"، نقوم بتحديث حالة التقرير المرتبط أيضًا
    if ($new_status == 'completed') {
        $stmt = $pdo->prepare("UPDATE reports SET status = 'completed' WHERE id = ?");
        $stmt->execute([$report_id]);
    }

    // توجيه إلى نفس الصفحة بعد التحديث لتحديث القائمة
    header("Location: review_tasks.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مراجعة المهام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>

<body>
<?php include_once 'nav_side-bar.php'; ?>

    <div class="container mt-4">
        <h1>مراجعة المهام</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>اسم الموظف</th>
                    <th>رابط التقرير</th>
                    <th>حالة المهمة</th>
                    <th>تحديث الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['employee_name']); ?></td>
                        <td>
                            <a href="../uploads/<?php echo htmlspecialchars($task['report_link']); ?>" target="_blank">عرض التقرير</a>
                        </td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td>
                            <form method="POST"> <!-- أزلت action -->
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <input type="hidden" name="report_id" value="<?php echo $task['report_id']; ?>">
                                <select name="status" required>
                                    <option value="review_pending" <?php echo $task['status'] == 'review_pending' ? 'selected' : ''; ?>>قيد المراجعة</option>
                                    <option value="review_completed" <?php echo $task['status'] == 'review_completed' ? 'selected' : ''; ?>>تمت المراجعة</option>
                                    <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                                </select>
                                <button type="submit" class="btn btn-success mt-2">تحديث الحالة</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer class="text-center text-lg-start mt-4">
        <div class="text-center text-light p-3">
            <b style="color:#0baff0;">سلفر فوكس</b> 2024 حقوق النشر محفوظة
            <a class="text-dark" href="https://MahmoudAli.Nadim.pro">
                <b style="color: #0b56ac;">by:</b>
                <strong class="text-light link-item">Mahmoud Ali Abu Shanab</strong>
            </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>