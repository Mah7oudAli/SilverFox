<?php
// ملف header.php

// بدء جلسة PHP
// session_start();

// استدعاء إعدادات الاتصال بقاعدة البيانات
require_once '../includes/config.php';

// تعيين اسم الصفحة
$pageTitle = isset($pageTitle) ? $pageTitle : 'Silver Fox - Home';
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>

    <!-- رابط ملف CSS الأساسي -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- إضافة ملف CSS مخصص -->
    <link rel="stylesheet" href="/public/css/styles.css">

    <!-- إضافة أي ملفات JavaScript ضرورية -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- إضافة أي أنماط مخصصة هنا -->

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
                            <a class="nav-link" href="dashboard.php"> لوحة التحكم </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_reports.php"> توزيع المهام </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_tasks.php"> التقارير المستلمة </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_employee.php"> اضافة موظف جديد </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">تسجيل الخروج</a>
                        </li>
                    </ul>
                </div>
            </div>
            <a class="navbar-brand" href="../index.php">Silver Fox</a>
        </nav>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>
    </html>