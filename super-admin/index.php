<?php
include '../includes/session_header.php';
require_once '../includes/config.php';

// التأكد من صلاحية الوصول للمشرف
if ( $_SESSION['role'] != 'supervisor') {
    header("Location: ../index.php");
    exit();
}
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$employee = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/sidebar_Admin.css">
    <title>المدير الاداري || الرئيسية</title>
</head>

<body>
<?php include_once 'nav_side-bar.php'; ?>
<div class="container d-flex justify-content-center align-items-center position-relative" style="height: 100vh;">
    
    <?php
    // قائمة أسماء الموظفين
    echo htmlspecialchars($employee['full_name']);


   
    ?>
</div>

<script>
    // تحديث مواقع الفقاعات بشكل دوري
    const bubbles = document.querySelectorAll('.bubble');
    setInterval(() => {
        bubbles.forEach(bubble => {
            const top = Math.random() * 80; // ارتفاع عشوائي
            const left = Math.random() * 80; // عرض عشوائي
            bubble.style.top = top + '%';
            bubble.style.left = left + '%';
            bubble.style.right = right + '%';
        });
    }, 3000); // التحديث كل 3 ثوانٍ
</script>
</body>
</html>