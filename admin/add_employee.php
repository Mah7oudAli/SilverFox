<?php
include '../includes/session_header.php';
// if ($_SESSION['role'] != 'supervisor') {
//     header("Location: ../public/index.php");
//     exit();
// }

require_once '../includes/config.php';

// دالة لتحويل الاسم إلى الأحرف اللاتينية
require '../includes/qr_generator.php';


$errors = []; // لتخزين الأخطاء
$success_message = ''; // لتخزين رسالة النجاح

// معالجة إضافة الموظف
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $national_id = $_POST['national_id'];
    $residence = $_POST['residence'];
    $role = $_POST['role'];
    $shift_start = $_POST['shift_start'];
    $shift_end = $_POST['shift_end'];
    $start_date = $_POST['start_date'];

    // التحقق من صحة الرقم الوطني: يجب أن يكون رقميًا وأن يتكون من 9 أرقام
    if (!preg_match('/^[0-9]{11}$/', $national_id)) {
        $errors[] = 'الرقم الوطني يجب أن يكون مكونًا من 11 رقم فقط.';
    }

    // التحقق من أن الرقم الوطني فريد
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE national_id = ?");
    $stmt->execute([$national_id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'الرقم الوطني موجود بالفعل في النظام.';
    }

    // التحقق من أن الحقول الأخرى ليست فارغة
    if (empty($full_name) || empty($residence) || empty($role) || empty($shift_start) || empty($shift_end) || empty($start_date)) {
        $errors[] = 'جميع الحقول مطلوبة.';
    }

    // إذا لم يكن هناك أخطاء، نستمر في إضافة الموظف
    if (empty($errors)) {
        // توليد اسم المستخدم: الاسم الأول بالإنجليزية + رقم عشوائي
        $first_name = explode(" ", $full_name)[0]; // الحصول على الاسم الأول
        $first_name_latin = convertToLatin($first_name); // تحويل الاسم الأول إلى الأحرف اللاتينية
        $username = strtolower($first_name_latin . rand(1000, 9999)); // توليد اسم مستخدم فريد

        // توليد كلمة المرور: الاسم الأول بالإنجليزية + آخر 5 أرقام من الرقم الوطني
        $password = $first_name_latin . substr($national_id, -5);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $qr_data = "الاسم: $full_name\nالرقم الوطني: $national_id\n مكان الاقامة: $residence";

        // استدعاء API لتوليد رمز QR وحفظه في مجلد
        $qr_code_path = generate_qr_code_via_api($qr_data, $username);
        // إضافة الموظف إلى قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO employees (full_name, national_id, residence, role, shift_start, shift_end, start_date, username, password,path_qr_Employee) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->execute([$full_name, $national_id, $residence, $role, $shift_start, $shift_end, $start_date, $username, $hashed_password, $path_qr_Employee]);

        // رسالة نجاح
        $success_message = 'تم إضافة الموظف بنجاح! اسم المستخدم هو: ' . $username . ' وكلمة المرور هي: ' . $password;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة موظف جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
<header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-info active"  href="dashboard.php"> لوحة التحكم </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php"> تسجيل الخروج  </a>
                        </li>
                    </ul>
                </div>
            </div>
            <a class="navbar-brand btn btn-outline-info" href="../index.php">Silver Fox</a>
        </nav>
    </header>
    <h6 class="alert alert-info text-center">إضافة موظف جديد</h6>
    <div class="container mt-4 shadow-lg">


        <!-- عرض الرسائل -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="add_employee.php" method="POST">
            <div class="container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">الاسم الكامل</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" required>
                            </div>

                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="national_id" class="form-label">الرقم الوطني</label>
                                <input type="text" name="national_id" id="national_id" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="residence" class="form-label">مكان الإقامة</label>
                                <input type="text" name="residence" id="residence" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="role" class="form-label">الدور</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="general_manager">مدير عام</option>
                                    <option value="supervisor">مشرف</option>
                                    <option value="super-accountant">رئيس قسم </option>

                                    <option value="accountant">محاسب</option>

                                    <option value="marketer">مسوق</option>
                                    <option value="legal_accountant">محاسب قانوني (محامي)</option>
                                    <option value="it_department">قسم IT</option>
                                    <option value="programming_department">قسم البرمجة</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                    <div class="col">
                            <div class="mb-3">
                                <label for="shift_start" class="form-label">بداية الشفت</label>
                                <input type="time" name="shift_start" id="shift_start" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                        <div class="mb-3">
                            <label for="shift_end" class="form-label">نهاية الشفت</label>
                            <input type="time" name="shift_end" id="shift_end" class="form-control" required>
                        </div>
                        </div>
                    </div>
                </div>



                
                <div class="row">
                    <div class="col">

                        
                    </div>
                </div>





                <div class="mb-3">
                    <label for="start_date" class="form-label">تاريخ بداية العمل</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-outline-primary w-100">إضافة الموظف</button>
            </div>

        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>