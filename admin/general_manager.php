<?php
include '../includes/session_header.php';
require_once '../includes/config.php';

// التأكد من صلاحية الوصول للمشرف
if ( $_SESSION['role'] != 'supervisor') {
    header("Location: ../index.php");
    exit();
}

// جلب جميع التقارير المكتملة
$stmt = $pdo->query("SELECT * FROM tasks WHERE status = 'completed'");
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير المكتملة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>التقارير المكتملة</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>معرف المهمة</th>
                <th>اسم المهمة</th>
                <th>رابط التقرير المكتمل</th>
                <th>الحالة</th>
                <th>تاريخ الرفع</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?php echo $task['id']; ?></td>
                <td><?php echo $task['task_name']; ?></td>
                <td>
                    <a href="<?php echo $task['completed_report']; ?>" target="_blank">عرض التقرير</a>
                </td>
                <td><?php echo $task['status']; ?></td>
                <td><?php echo date('Y-m-d H:i:s', strtotime($task['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
