<?php
// include '../includes/session_header.php';
require_once '../includes/config.php';

// السماح بالدخول للمشرف والمدير العام فقط
// if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['supervisor', 'general_manager'])) {
//     header("Location: ../index.php");
//     exit();
// }

// جلب تفاصيل العملاء مع الملفات المرتبطة
$stmt = $pdo->query("
    SELECT c.id AS client_id, c.full_name, c.national_id, c.residence_or_work, c.mobile_phone, 
           c.work_phone, c.personal_phone, c.username, c.qr_code_path, r.*
    FROM clients c
    LEFT JOIN tasks r ON c.id = r.client_id
    WHERE r.status = 'pending'
    ORDER BY c.full_name
");
$clients = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC); // تصنيف حسب العميل

?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة تقارير العملاء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
        .container { flex: 1; }
        .client-section {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .client-section h3 {
            color: #0d6efd;
        }
        .file-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .file-item:last-child {
            border-bottom: none;
        }
        .file-icon {
            margin-left: 10px;
            color: #198754;
        }
        footer { background-color: #11102096; text-align: center; padding: 5px 0; }
    </style>
</head>

<body>
<?php include_once 'navbar.php'; ?>

    <div class="container mt-4">
        <h5 class="alert alert-success text-center">إدارة تقارير العملاء</h5>

        <?php foreach ($clients as $client_id => $client_reports): ?>
            <?php $client = $client_reports[0]; // معلومات العميل ?>
            <div class="client-section">
                <h3>عميل: <?php echo htmlspecialchars($client['full_name']); ?></h3>
                <p><strong>رقم الهوية:</strong> <?php echo htmlspecialchars($client['national_id']); ?></p>
                <p><strong>هاتف الجوال:</strong> <?php echo htmlspecialchars($client['mobile_phone']); ?></p>
                <p><strong>اسم المستخدم:</strong> <?php echo htmlspecialchars($client['username']); ?></p>
                
                <?php if (!empty($client_reports[0]['id'])): ?>
                    <h5>التقارير المرتبطة:</h5>
                    <?php foreach ($client_reports as $report): ?>
                        <div class="file-item">
                            <span class="file-icon">📄</span>
                            <div>
                                <p class="mb-0"><strong>اسم الملف:</strong> <?php echo htmlspecialchars($report['completed_report']); ?></p>
                                <p class="mb-0"><strong>تاريخ الإنشاء:</strong> <?php echo htmlspecialchars($report['created_at']); ?></p>
                                <a href="../uploads/<?php echo htmlspecialchars($report['completed_report']); ?>" target="_blank" class="btn btn-sm btn-outline-success mt-1">عرض التقرير</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>لا توجد تقارير مرتبطة لهذا العميل.</p>
                <?php endif; ?>

                <!-- زر لتحميل جميع الملفات الخاصة بالعميل -->
                <form action="download_files.php" method="POST" class="mt-3">
                    <input type="hidden" name="client_id" value="<?php echo htmlspecialchars($client['client_id']); ?>">
                    <button type="submit" class="btn btn-success">تحميل جميع ملفات <?php echo htmlspecialchars($client['full_name']); ?></button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
