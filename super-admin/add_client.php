<?php
include '../includes/session_header.php';

require_once '../includes/config.php';

// التحقق من صلاحية الوصول
if ($_SESSION['role'] != 'supervisor') {
    header("Location: ../index.php");
    exit();
}
require '../includes/qr_generator.php';
// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $national_id = $_POST['national_id'];
    $residence_or_work = $_POST['residence_or_work'];
    $mobile_phone = $_POST['mobile_phone'];
    $work_phone = $_POST['work_phone'] ?? null;
    $personal_phone = $_POST['personal_phone'] ?? null;

    // توليد اسم المستخدم تلقائيًا: الاسم الأول + _ + مكان الإقامة + رقم عشوائي
    $first_name = explode(" ", $full_name)[0];
    $unique_number = rand(1000, 9999);

    // تحويل الاسم الأول ومكان الإقامة إلى نص لاتيني
    $first_name_latin = convertToLatin($first_name);
    $residence_or_work_latin = convertToLatin($residence_or_work);

    // إنشاء اسم المستخدم
    $username = strtolower($first_name_latin . '_' . $residence_or_work_latin . '_' . $unique_number);

    // جعل الرقم الوطني هو كلمة المرور تلقائيًا
    $password = $national_id;

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // التحقق من عدم وجود الرقم الوطني مسبقًا
    try {
        // التحقق من وجود الرقم الوطني في قاعدة البيانات
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE national_id = ?");
        $stmt->execute([$national_id]);
        $client_exists = $stmt->fetchColumn();

        if ($client_exists > 0) {
            // إذا كان الرقم الوطني موجودًا مسبقًا، عرض رسالة خطأ
            $error_msg = "العميل بهذا الرقم الوطني موجود مسبقًا.";
        } else {
            // توليد البيانات المطلوبة لرمز QR
            $qr_data = "الاسم: $full_name\nالرقم الوطني: $national_id\nرقم الهاتف: $mobile_phone";

            // استدعاء API لتوليد رمز QR وحفظه في مجلد
            $qr_code_path = generate_qr_code_via_api($qr_data, $username); // توليد اسم فريد للصورة بناءً على اسم المستخدم

            // إدخال البيانات في قاعدة البيانات
            $stmt = $pdo->prepare("INSERT INTO clients (full_name, national_id, residence_or_work, mobile_phone, work_phone, personal_phone, username, password, qr_code_path)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$full_name, $national_id, $residence_or_work, $mobile_phone, $work_phone, $personal_phone, $username, $hashed_password, $qr_code_path]);

            // نجاح الإدخال
            $error_msg = "تمت إضافة العميل بنجاح!";
        }
    } catch (PDOException $e) {
        // معالجة أخطاء قاعدة البيانات
        $error_msg = "حدث خطأ أثناء إدخال البيانات: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة عميل</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>
    <div class="container mt-5 shadow-lg bg-dark text-light">
        <br>
        <h5 class="alert alert-info text-center ">إضافة عميل جديد</h5>
        <?php if (!empty($error_msg)): ?>
            <div class="alert <?php echo strpos($error_msg, 'تمت') !== false ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $error_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="add_client.php">
            <div class="container ">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="national_id" class="form-label">الرقم الوطني</label>
                            <input type="text" class="form-control" id="national_id" name="national_id" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="residence_or_work" class="form-label">مكان السكن / العمل</label>
                            <input type="text" class="form-control" id="residence_or_work" name="residence_or_work" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="mobile_phone" class="form-label">رقم الهاتف المحمول</label>
                            <input type="text" class="form-control" id="mobile_phone" name="mobile_phone" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="work_phone" class="form-label">رقم هاتف العمل (اختياري)</label>
                            <input type="text" class="form-control" id="work_phone" name="work_phone">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="personal_phone" class="form-label">الرقم الشخصي (اختياري)</label>
                            <input type="text" class="form-control" id="personal_phone" name="personal_phone">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-light">
                <button type="submit" class="btn btn-outline-primary w-50 m-3">إضافة العميل</button>

            </div>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>