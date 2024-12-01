<?php
require_once '../includes/session_header.php';

require_once '../includes/config.php';

// جلب إحصائيات مثل عدد المستخدمين، المهام، والتقارير
$totalClients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$totalEmployees = $pdo->query("SELECT COUNT(*) FROM Employees")->fetchColumn();
$totalTasks = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
$totalReports = $pdo->query("SELECT COUNT(*) FROM reports")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير العام</title>
    <script src="https://kit.fontawesome.com/db5840f177.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-jpg" href="../img/silver-index.jpg" style="border-radius:50%;">
    <link rel="stylesheet" href="../public/css/sidebar_Admin.css">
    <style>

    </style>
</head>

<body>

    <!-- Navbar with Menu Button -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container text-center">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav md-4">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">لوحة التحكم</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger" href="../logout.php">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                تسجيل الخروج
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                        <h3 id="time">00:00:00</h3>
                        </li> -->
                    </ul>
                </div>
                <a class="navbar-brand" href="../index.php">Silver Fox</a>

            </div>
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

    <!-- Main Content -->
    <div class="container mt-2">
        <h3 class="alert alert-info text-center  w-100  ">مرحباً بك، مدير عام!</h3>

        <div class="container ">
            <div class="row">

                <div class="col">
                    <div class="time-container brand">
                        <h3 id="time">00:00:00</h3>
                        <p id="day">اليوم</p>
                        <p id="date">التاريخ</p>
                    </div>
                </div>


            </div>

            <!-- إحصائيات -->
            <div class="row justify-content-center">
                <div class="col-md-2">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">عدد العملاء</h5>
                            <p class="card-text"><?php echo $totalClients; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">عدد المهام</h5>
                            <p class="card-text"><?php echo $totalTasks; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">عدد الموظفين</h5>
                            <p class="card-text"><?php echo $totalEmployees; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-dark bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">عدد التقارير</h5>
                            <p class="card-text"><?php echo $totalReports; ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-dark text-white py-3 mt-auto text-center">
        <p>© 2024 جميع الحقوق محفوظة - إدارة الموقع</p>
    </footer>

    <script>
        // JavaScript to toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('visible');
        }
    </script>
    <script>
        function updateTime() {
            const now = new Date();

            // استخراج الوقت بصيغة 12 ساعة
            let hours = now.getHours();
            const period = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // تحويل الساعة إلى 12 ساعة، مع التعامل مع الحالة 0 لتصبح 12

            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');

            // استخراج التاريخ واسم اليوم
            const days = ["الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت"];
            const months = ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"];

            let dayName = days[now.getDay()];
            let day = now.getDate();
            let monthName = months[now.getMonth()];
            let year = now.getFullYear();

            // تحديث النصوص في HTML
            document.getElementById('time').textContent = `${hours}:${minutes}:${seconds} ${period}`;
            document.getElementById('day').textContent = `اليوم: ${dayName}`;
            document.getElementById('date').textContent = `التاريخ: ${day} ${monthName} ${year}`;
        }

        // تحديث الوقت كل ثانية
        setInterval(updateTime, 1000);
        updateTime(); // تشغيل الدالة عند التحميل مباشرة
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>