<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

// التحقق من صلاحيات المستخدم (للمدير فقط)
if ($_SESSION['role'] != 'general_manager') {
    header("Location: /public/index.php");
    exit();
}

// جلب بيانات المحادثات مع أسماء العملاء والموظفين
$stmt = $pdo->query("
    SELECT DISTINCT m.client_id, m.employee_id, c.username AS client_name, e.username AS employee_name
    FROM messages m
    JOIN clients c ON m.client_id = c.id
    JOIN employees e ON m.employee_id = e.id
");
$chats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة الدردشات</title>
    <link rel="stylesheet" href="../public/css/chate_designe.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            direction: rtl;
        }
    </style>
</head>

<body>
<?php include_once 'nav_side-bar.php'; ?>

    <div class="container mt-4">
        <h6 class="alert alert-danger text-center">إدارة الدردشات</h6>
        <table class="table table-dark table-striped table-hover text-center">
            <thead>
                <tr class="table table-info ">
                    <th>عنوان المحادثة</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chats as $chat): ?>
                    <tr>
                        <td>دردشة <?php echo htmlspecialchars($chat['client_name']); ?> مع الموظف <?php echo htmlspecialchars($chat['employee_name']); ?></td>
                        <td>
                            <a href="view_chat.php?employee_id=<?php echo $chat['employee_id']; ?>&client_id=<?php echo $chat['client_id']; ?>">عرض المحادثة</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>