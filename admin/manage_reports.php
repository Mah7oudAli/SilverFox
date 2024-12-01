<?php
include '../includes/session_header.php';

// تضمين الكلاسات المطلوبة
require_once '../includes/config.php';
require_once '../includes/ClientDistributor.php';

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
    header("Location: distribute_clients.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توزيع العملاء على الموظفين</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/sidebar_Admin.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
            <div class="container">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapsejustify-content-center" id="navbarNav">
                    <ul class="navbar-nav   text-center">
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-info active" href="dashboard.php"> لوحة التحكم </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php"> تسجيل الخروج </a>
                        </li>
                    </ul>
                </div>
            </div>
            <a class="navbar-brand btn btn-outline-info" href="../index.php">Silver Fox</a>
        </nav>
    </header>
    <!-- Hamburger Button -->
    <div class="menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../img/silver_bg.jpg" alt="logo" />
            <h2>Silver Fox</h2>
        </div>
        <ul class="sidebar-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-bars"></i> لوحة التحكم</a></li>
            <li><a href="manage_clients.php"><i class="fa-solid fa-user-gear"></i> إدارة المستخدمين</a></li>

            <li><a href="add_employee.php"><i class="fa-solid fa-user-plus"></i> موظف جديد </a></li>
            <li><a href="add_client.php"><i class="fa-solid fa-address-card"></i> عـمـيــل جديد</a></li>

            <li><a href="manage_tasks.php"><i class="fa-solid fa-list-check"></i> إدارة المهام</a></li>
            <li><a href="manage_reports.php"><i class="fa-solid fa-file-waveform"></i> إدارة التقارير</a></li>
            <li><a href="review_chat.php"><i class="fa-solid fa-comments"></i> دردشات العملاء</a></li>
            <li><a href="site_settings.php"><i class="fa-solid fa-gear"></i> اعدادات النظام </a></li>

            <li><a href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج</a></li>
        </ul>
    </aside>
    <div class="container justify-content-center mt-2">
        <h4 class="alert alert-primary text-center">توزيع العملاء على الموظفين</h4>

        <!-- عرض رسالة نجاح عند التوزيع -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">تم تحديث الموظف بنجاح!</div>
        <?php endif; ?>

        <table class="table table-dark table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>رقم العميل</th>
                    <th>اسم العميل</th>
                    <th>الموظف الحالي</th>
                    <th>توزيع على موظف</th>
                </tr>
            </thead>
            <tbody class="table-primary">
                <?php foreach ($clients as $client): ?>
                    <tr class="table-dark">
                        <td><?php echo $client['id']; ?></td>
                        <td><?php echo $client['full_name']; ?></td>
                        <td><?php echo $clientDistributor->getEmployeeById($client['employee_id']); ?></td>
                        <td>
                            <!-- فورم لتوزيع العميل على موظف -->
                            <form method="POST">
                                <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                <select name="employee_id" class="form-select" required>
                                    <option value="" disabled selected>اختر موظفاً</option>
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?php echo $employee['id']; ?>" <?php echo ($client['employee_id'] == $employee['id']) ? 'selected' : ''; ?>>
                                            <?php echo $employee['username']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2 w-100">تحديث</button>
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