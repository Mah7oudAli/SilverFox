<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الدعم الفني</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;

        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }

        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">الدعم الفني</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="support_generate_link.php"> طلبات تغير كلمةالمرور  </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">التقارير</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="setting_webSit.php">الإعدادات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-info" href="#">تسجيل الخروج</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">التذاكر المفتوحة</h5>
                        <p class="card-text display-4">25</p>
                        <a href="#" class="btn btn-custom">عرض التفاصيل</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">التذاكر المغلقة</h5>
                        <p class="card-text display-4">75</p>
                        <a href="#" class="btn btn-custom">عرض التفاصيل</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">عدد العملاء</h5>
                        <p class="card-text display-4">150</p>
                        <a href="#" class="btn btn-custom">عرض التفاصيل</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Actions -->
        <div class="row mt-5">
            <div class="col text-center">
                <a href="#" class="btn btn-lg btn-primary">إضافة تذكرة جديدة</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 إدارة الدعم الفني. جميع الحقوق محفوظة.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
