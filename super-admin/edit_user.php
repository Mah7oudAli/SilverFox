<?php
include '../includes/session_header.php';
require_once '../includes/config.php';

// تحقق من أن المستخدم لديه صلاحية الوصول
if ($_SESSION['role'] != 'supervisor') {
    header("Location: ../index.php");
    exit();
}

// جلب بيانات الموظف بناءً على المعرف
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$employeeId]);
    $employee = $stmt->fetch();

    if (!$employee) {
        echo "الموظف غير موجود.";
        exit();
    }
}

// تحديث بيانات الموظف
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['full_name'];
    $nationalId = $_POST['national_id'];
    $residence = $_POST['residence'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $shiftStart = $_POST['shift_start'];
    $shiftEnd = $_POST['shift_end'];
    $startDate = $_POST['start_date'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("UPDATE employees SET full_name = ?, national_id = ?, residence = ?, phone = ?, role = ?, shift_start = ?, shift_end = ?, start_date = ?, username = ?, password = ? WHERE id = ?");
    if ($stmt->execute([$fullName, $nationalId, $residence, $phone, $role, $shiftStart, $shiftEnd, $startDate, $username, $password, $employeeId])) {
        echo "تم تحديث الموظف بنجاح!";
    } else {
        echo "حدث خطأ أثناء التحديث.";
    }
}
?>


<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../public/css/styles.css">
    <title>تعديل الموظف</title>
</head>

<body>

<?php include_once 'navbar.php'; ?>

    <div class="container-lg shadow-lg bg-dark text-light mt-1 p-1 rounded-3 vh-100" >
    <div class="alert alert-info text-center">تحديث بيانات الموظف</div>
    <form method="POST">
        <div class="row">
        <div class="mb-3  col-md-6">
            <label for="full_name" class="form-label">الاسم الكامل</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($employee['full_name']); ?>" required>
        </div>
        <div class="mb-3 col-md-6">
            <label for="national_id" class="form-label">الرقم الوطني</label>
            <input type="text" class="form-control" id="national_id" name="national_id" value="<?php echo htmlspecialchars($employee['national_id']); ?>" required>
        </div>
        </div>
        <div class="row">
        <div class="mb-3 col-md-6">
            <label for="residence" class="form-label">العنوان</label>
            <input type="text" class="form-control" id="residence" name="residence" value="<?php echo htmlspecialchars($employee['residence']); ?>" required>
        </div>
        <div class="mb-3 col-md-6">
            <label for="phone" class="form-label">الهاتف</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
        </div>
        </div>
        <div class="row">
        <div class="mb-3">
            <label for="role" class="form-label">الدور</label>
            <select class="form-control" id="role" name="role">
                <option value="accountant" <?php if ($employee['role'] == 'accountant') echo 'selected'; ?>>محاسب</option>
                <option value="general_manager" <?php if ($employee['role'] == 'general_manager') echo 'selected'; ?> disabled>مدير عام</option>
                <option value="supervisor" <?php if ($employee['role'] == 'supervisor') echo 'selected'; ?>>مشرف</option>
                <option value="marketer" <?php if ($employee['role'] == 'marketer') echo 'selected'; ?>>مسوق</option>
            </select>
        </div>
        </div>
        <div class="row">
        <div class="mb-3 col-md-6">
            <label for="shift_start" class="form-label">بداية الدوام</label>
            <input type="time" class="form-control" id="shift_start" name="shift_start" value="<?php echo htmlspecialchars($employee['shift_start']); ?>" required>
        </div>
        <div class="mb-3 col-md-6">
            <label for="shift_end" class="form-label">نهاية الدوام</label>
            <input type="time" class="form-control" id="shift_end" name="shift_end" value="<?php echo htmlspecialchars($employee['shift_end']); ?>" required>
        </div>
        </div>
        <div class="row">
        <div class="mb-3 col-md-6">
            <label for="start_date" class="form-label">تاريخ البدء</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($employee['start_date']); ?>" required>
        </div>
        <div class="mb-3 col-md-6">
            <label for="username" class="form-label">اسم المستخدم</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($employee['username']); ?>" required>
        </div>
        </div>
       <div class="row">
        <div class="mb-3 ">
        <button type="submit" class="btn btn-outline-info w-100">تحديث</button>

        </div>

       </div>
      
       
        
       
        <!-- <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div> -->
    </form>
</div>



    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>