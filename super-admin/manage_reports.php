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
    header("Location: manage_reports.php?success=1");
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
<?php include_once 'navbar.php'; ?>

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