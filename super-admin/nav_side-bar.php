<?php

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

    <script src="https://kit.fontawesome.com/db5840f177.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-jpg" href="../img/silver-index.jpg" style="border-radius:50%;">
    <title>المدير الاداري || الرئيسية</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">


                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav ml-2 text-center">
                        <div class="row">
                            <div class="col">
                                <li class="nav-item md-2">
                                    <a class="navbar-brand btn btn-outline-info" href="#">
                                        <i class="fa-solid fa-user-gear"></i>

                                        <?php echo htmlspecialchars($employee['full_name']); ?>
                                    </a>
                                </li>
                            </div>
                            <div class="col">
                                <li class="nav-item">
                                    <a class="btn btn-outline-danger" href="../logout.php">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        تسجيل الخروج
                                    </a>
                                </li>
                            </div>
                        </div>



                    </ul>
                </div>

            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- <a class="navbar-brand btn btn-outline-info" href="../index.php">Silver Fox</a> -->
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
            <h2> المدير الاداري </h2>
        </div>
        <ul class="sidebar-links">
            <li><a href="index.php"><i class="fa-solid fa-bars"></i> لوحة التحكم</a></li>
            <li><a href="manage_clients.php"><i class="fa-solid fa-user-gear"></i> إدارة المستخدمين</a></li>

            <li><a href="add_employee.php"><i class="fa-solid fa-user-plus"></i> موظف جديد </a></li>
            <li><a href="add_client.php"><i class="fa-solid fa-address-card"></i> عـمـيــل جديد</a></li>

            <li><a href="manage_tasks.php"><i class="fa-solid fa-list-check"></i> إدارة المهام</a></li>
            <li><a href="manage_reports.php"><i class="fa-solid fa-file-waveform"></i> ادارة الربط C & E </a></li>
            <li><a href="review_chat.php"><i class="fa-solid fa-comments"></i> دردشات العملاء</a></li>
            <!-- <li><a href="site_settings.php"><i class="fa-solid fa-gear"></i> اعدادات النظام </a></li> -->

        </ul>
        
    </aside>

    <script>
        // JavaScript to toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('visible');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>