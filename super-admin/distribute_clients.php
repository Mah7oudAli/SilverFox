<?php
include '../includes/session_header.php';
// if ($_SESSION['role'] != 'supervisor') {
//     header("Location: ../public/index.php");
//     exit();
// }

require_once '../includes/config.php';
require_once '../includes/ClientDistributor.php'; // تضمين الكلاس

// إنشاء كائن من كلاس ClientDistributor
$clientDistributor = new ClientDistributor($pdo);

// جلب جميع العملاء والموظفين
$clients = $clientDistributor->getAllClients();
$employees = $clientDistributor->getAllEmployees();

// معالجة الطلب عند إرسال الفورم
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientId = $_POST['client_id'];
    $employeeId = $_POST['employee_id'];

    // تعيين الموظف للعميل
    $clientDistributor->assignEmployeeToClient($clientId, $employeeId);

    // إعادة التوجيه بعد التحديث
    header("Location: manage_reports?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توزيع العملاء على الموظفين</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="../index.php">Silver Fox</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="manage_reports.php"> توزيع المهام </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_tasks.php"> التقارير المستلمة </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_employee.php"> اضافة موظف جديد </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">تسجيل الخروج</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container mt-4">
        <h1>توزيع العملاء على الموظفين</h1>

        <!-- عرض رسالة نجاح عند التوزيع -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">تم تحديث الموظف بنجاح!</div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم العميل</th>
                    <th>اسم العميل</th>
                    <th>الموظف الحالي</th>
                    <th>توزيع على موظف</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['id']; ?></td>
                        <td><?php echo $client['full_name']; ?></td>
                        <td><?php echo $clientDistributor->getEmployeeById($client['employee_id']); ?></td> <!-- استخدام كلاس للحصول على اسم الموظف -->
                        <td>
                            <!-- فورم لتوزيع العميل على موظف -->
                            <form action="distribute_clients.php" method="POST">
                                <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                <select name="employee_id" class="form-select" required>
                                    <option value="" disabled selected>اختر موظفاً</option>
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?php echo $employee['id']; ?>" <?php echo ($client['employee_id'] == $employee['id']) ? 'selected' : ''; ?>>
                                            <?php echo $employee['username']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">تحديث</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
