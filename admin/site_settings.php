<?php
require_once '../includes/session_header.php';
// if ($_SESSION['role'] != 'general_manager') {
//     header("Location: ../public/index.php");
//     exit();
// }

require_once '../includes/config.php';

// جلب الإعدادات الحالية
$stmt = $pdo->query("SELECT setting_key, setting_value, is_site_active, track_employee_activity, disable_employee_section, disable_client_section FROM site_settings");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// إذا كانت النتيجة فارغة، يتم التعامل مع الوضع الافتراضي
if (!$settings) {
    $settings = [
        'site_name' => 'اسم الموقع الافتراضي',
        'admin_email' => 'admin@example.com',
        'is_site_active' => 1,
        'track_employee_activity' => 0,
        'disable_employee_section' => 0,
        'disable_client_section' => 0
    ];
}

// تحديث الإعدادات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'];
    $admin_email = $_POST['admin_email'];
    $is_site_active = isset($_POST['is_site_active']) ? 1 : 0;
    $track_employee_activity = isset($_POST['track_employee_activity']) ? 1 : 0;
    $disable_employee_section = isset($_POST['disable_employee_section']) ? 1 : 0;
    $disable_client_section = isset($_POST['disable_client_section']) ? 1 : 0;

    // تحديث إعدادات الموقع
    $updateStmt = $pdo->prepare("
        UPDATE site_settings 
        SET setting_value = CASE setting_key
            WHEN 'site_name' THEN ?
            WHEN 'admin_email' THEN ?
        END,
        is_site_active = ?, 
        track_employee_activity = ?,
        disable_employee_section = ?, 
        disable_client_section = ?
        WHERE setting_key IN ('site_name', 'admin_email')
    ");
    $updateStmt->execute([$site_name, $admin_email, $is_site_active, $track_employee_activity, $disable_employee_section, $disable_client_section]);

    header("Location: site_settings.php");
    exit();
}
$stmt = $pdo->query("SELECT * FROM login_tracking ORDER BY login_time DESC");
$logins = $stmt->fetchAll();
// جلب بيانات تسجيل الدخول للموظفين والعملاء
// $employeeLogins = $pdo->query("SELECT ip_address, login_time, employee_id FROM employees ORDER BY login_time DESC")->fetchAll();
// $clientLogins = $pdo->query("SELECT ip_address, login_time, client_id FROM clients ORDER BY login_time DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الموقع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Readex+Pro:wght@160..700&display=swap');

        body {
            direction: rtl;
            font-family: "Readex Pro", sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .titel {
            text-shadow: 5px 2px 5px blue;
            text-align: center;
            font-weight: bolder;
            padding-left: 10px;
            margin-right: 5px;
            justify-content: center;
            align-items: center;
        }

        .settings {
            background: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            flex: 1;
            filter: drop-shadow(0px 20px 14px #000000);
        }

        footer {
            background-color: #11102096;
            text-align: center;
            padding: 5px 0;
        }

        .form-check {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            transition: 0.3s ease;
        }

        .form-group:hover {
            background-color: #f1f1f1;
        }

        .form-group input[type="checkbox"] {
            margin-right: 12px;
            width: 20px;
            height: 20px;
            accent-color: #007bff;
            /* لون مخصص لمربع الاختيار */
        }

        .form-group label {
            font-weight: 600;
            font-size: 1.1em;
            color: #333;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include '../partials/header.php'; ?>

    <div class="container settings mt-4">
        <h5 class="alert alert-info text-center">إعدادات الموقع</h5>
        <form action="site_settings.php" method="POST">
            <!-- <div class="mb-3">
                <label for="site_name" class="form-label">اسم الموقع</label>
                <input type="text" name="site_name" id="site_name" class="form-control" value="<?php echo $settings['site_name'] ?? ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="admin_email" class="form-label">البريد الإلكتروني للإدارة</label>
                <input type="email" name="admin_email" id="admin_email" class="form-control" value="<?php echo $settings['admin_email'] ?? ''; ?>" required>
            </div> -->
            <div class="mb-3 form-group">

                <input type="checkbox" name="is_site_active" id="is_site_active" class="form-check-input"
                    <?php echo isset($settings['is_site_active']) && $settings['is_site_active'] ? 'checked' : ''; ?>>
                <label for="is_site_active" class="form-check-label">تشغيل الموقع</label>

            </div>
            <div class="mb-3 form-group">

                <input type="checkbox" name="track_employee_activity" id="track_employee_activity" class="form-check-input"
                    <?php echo isset($settings['track_employee_activity']) && $settings['track_employee_activity'] ? 'checked' : ''; ?>>
                <label for="track_employee_activity" class="form-check-label">تفعيل تتبع نشاط الموظفين</label>

            </div>
            <div class="mb-3 form-group">

                <input type="checkbox" name="disable_employee_section" id="disable_employee_section" class="form-check-input"
                    <?php echo isset($settings['disable_employee_section']) && $settings['disable_employee_section'] ? 'checked' : ''; ?>>
                <label for="disable_employee_section" class="form-check-label">تعطيل قسم الموظفين</label>

            </div>
            <div class="mb-3 form-group">

                <input type="checkbox" name="disable_client_section" id="disable_client_section" class="form-check-input"
                    <?php echo isset($settings['disable_client_section']) && $settings['disable_client_section'] ? 'checked' : ''; ?>>
                    <label for="disable_client_section" class="form-check-label">تعطيل قسم العملاء</label>
            </div>
            <button type="submit" class="btn btn-outline-primary mb-3 w-100">تحديث الإعدادات</button>
        </form>
    </div>

    <h5 class="alert alert-info text-center ">نشاطات تسجيل الدخول</h5>
    <table class="table table-dark table-striped table-hover text-center">
        <tr>
            <th>اسم المستخدم</th>
            <th>نوع المستخدم</th>
            <th>اسم الجهاز</th>
            <th>نوع الجهاز</th>
            <th>IP</th>
            <th>الموقع الجغرافي</th>
            <th>تاريخ التسجيل</th>
        </tr>
        <?php foreach ($logins as $login): ?>
            <tr>
                <td><?= htmlspecialchars($login['username']) ?></td>
                <td><?= htmlspecialchars($login['user_type']) ?></td>
                <td><?= htmlspecialchars($login['device_name']) ?></td>
                <td><?= htmlspecialchars($login['device_type']) ?></td>
                <td><?= htmlspecialchars($login['device_ip']) ?></td>
                <td><?= htmlspecialchars($login['location']) ?></td>
                <td><?= htmlspecialchars($login['login_time']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>