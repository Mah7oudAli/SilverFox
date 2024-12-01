<?php
include '../includes/session_header.php';
require_once '../includes/config.php';

if ($_SESSION['role'] != 'general_manager') {
    header("Location: ../public/index.php");
    exit();
}

// جلب بيانات العميل بناءً على المعرف
if (isset($_GET['id'])) {
    $clientId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$clientId]);
    $client = $stmt->fetch();

    if (!$client) {
        echo "العميل غير موجود.";
        exit();
    }
}

// تحديث بيانات العميل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['full_name'];
    $nationalId = $_POST['national_id'];
    $residence = $_POST['residence_or_work'];
    $mobilePhone = $_POST['mobile_phone'];
    $workPhone = $_POST['work_phone'];
    $personalPhone = $_POST['personal_phone'];
    $username = $_POST['username'];

    $stmt = $pdo->prepare("UPDATE clients SET full_name = ?, national_id = ?, residence_or_work = ?, mobile_phone = ?, work_phone = ?, personal_phone = ?, username = ? WHERE id = ?");
    if ($stmt->execute([$fullName, $nationalId, $residence, $mobilePhone, $workPhone, $personalPhone, $username, $clientId])) {
        echo "<div class='alert alert-success'>تم تحديث بيانات العميل بنجاح!</div>";
    } else {
        echo "<div class='alert alert-danger'>حدث خطأ أثناء تحديث البيانات.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/styles.css">

    <title>تعديل بيانات العميل</title>
</head>

<body>
<?php include_once 'nav_side-bar.php'; ?>
<div class="d-flex justify-content-center align-items-center mt-1 vh-100">
        <div class="container shadow-lg bg-dark text-light p-4 rounded-3 w-50">
            <div class="alert alert-info text-center">تحديث بيانات العميل</div>
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($client['full_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="national_id" class="form-label">الرقم الوطني</label>
                        <input type="text" class="form-control" id="national_id" name="national_id" value="<?php echo htmlspecialchars($client['national_id']); ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="residence_or_work" class="form-label">مكان السكن أو العمل</label>
                        <input type="text" class="form-control" id="residence_or_work" name="residence_or_work" value="<?php echo htmlspecialchars($client['residence_or_work']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="mobile_phone" class="form-label">الهاتف المحمول</label>
                        <input type="text" class="form-control" id="mobile_phone" name="mobile_phone" value="<?php echo htmlspecialchars($client['mobile_phone']); ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="work_phone" class="form-label">هاتف العمل</label>
                        <input type="text" class="form-control" id="work_phone" name="work_phone" value="<?php echo htmlspecialchars($client['work_phone']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="personal_phone" class="form-label">الهاتف الشخصي</label>
                        <input type="text" class="form-control" id="personal_phone" name="personal_phone" value="<?php echo htmlspecialchars($client['personal_phone']); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($client['username']); ?>" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-outline-info w-100">تحديث البيانات</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

