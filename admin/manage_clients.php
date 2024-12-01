<?php
include '../includes/session_header.php';

if ($_SESSION['role'] != 'general_manager') {
    header("Location: ../index.php");
    exit();
}

require_once '../includes/config.php';

// جلب كافة الموظفين مع معلومات إضافية مثل العملاء المخصصين لكل موظف
$stmt = $pdo->query("SELECT u.*, 
       (SELECT COUNT(*) FROM clients c WHERE c.employee_id = u.id) AS client_count,
       u.path_qr_Employee
FROM employees u
WHERE u.role = 'accountant'");
$users = $stmt->fetchAll();

// جلب كافة العملاء
$clientsStmt = $pdo->query("SELECT c.*, u.username AS employee_name 
    FROM clients c
    LEFT JOIN employees u ON c.employee_id = u.id");
$clients = $clientsStmt->fetchAll();
?>
<?php

require_once '../includes/config.php';

if ($_SESSION['role'] != 'general_manager') {
    header("Location: ../public/index.php");
    exit();
}

// استلام وإزالة الرسالة من الجلسة بعد عرضها
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموظفين والعملاء</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <!-- روابط مكتبة Toastr.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }

        img {
            width: 50px;
            height: 50px;
            background-color: blueviolet;
        }



        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 10px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 100%;
            max-height: 100%;
            width: auto;
            /* يضمن أن عرض الصورة مناسب */
            height: auto;
            /* يضمن أن ارتفاع الصورة مناسب */
            object-fit: contain;
            /* يتناسب مع الأبعاد، مع الحفاظ على نسبة الطول إلى العرض */
            border-radius: 8px;
            /* يضيف لمسة جمالية للحدود */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
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
    <script>
        // عرض الرسالة إذا كانت موجودة
        <?php if ($message): ?>
            toastr.<?php echo $message['type']; ?>('<?php echo $message['text']; ?>');
        <?php endif; ?>
    </script>
    <div class="container mt-4">
        <div class="alert alert-info text-center text-dark">إدارة الموظفين</div>
        <table class="table table-dark table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>رقم المستخدم</th>
                    <th>اسم المستخدم</th>
                    <th>QR code</th>
                    <th>الدور</th>
                    <th>عدد العملاء المخصصين</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td ><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td>
                            <img src="<?php echo $user['path_qr_Employee']; ?>" alt="QR Code" class="qr-thumbnail" onclick="openModal('<?php echo $user['path_qr_Employee']; ?>')">

                            <!-- <img src="<?php echo $user['path_qr_Employee']; ?>" alt=""> -->
                        </td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo $user['client_count']; ?></td> <!-- عرض عدد العملاء المخصصين -->
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">تعديل</a>
                            <a href="delete_employee.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return deleteClient()">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="container mt-4">
    <div class="alert alert-info text-center text-dark">إدارة العملاء</div>
    <table class="table table-dark table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>رقم العميل</th>
                    <th>اسم العميل</th>
                    <th>رمز QR</th>
                    <th>الموظف المخصص</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['id']; ?></td>
                        <td><?php echo $client['full_name']; ?></td>
                        <td>
                            <img src="<?php echo $client['qr_code_path']; ?>" alt="QR Code" class="qr-thumbnail" onclick="openModal('<?php echo $client['qr_code_path']; ?>')">
                        </td>
                        <td><?php echo $client['employee_name'] ?: 'غير مخصص'; ?></td> <!-- عرض اسم الموظف المخصص -->
                        <td>
                            <a href="edit_client.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-warning">تعديل</a>
                            <a href="delete_client.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-danger"
                                onclick="return confirmDeletion()">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="qrModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        <div id="qr-reader-result"></div>
    </div>
    <?php include '../includes/footer.php'; ?>


    
    <script>
        function openModal(imageSrc) {
            var modal = document.getElementById("qrModal");
            var modalImage = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImage.src = imageSrc;
            readQRCode(imageSrc); // استدعاء وظيفة قراءة رمز QR
        }

        function closeModal() {
            var modal = document.getElementById("qrModal");
            modal.style.display = "none";
            document.getElementById("qr-reader-result").textContent = ''; // تفريغ نتيجة القراءة عند الإغلاق
        }

        window.onclick = function(event) {
            var modal = document.getElementById("qrModal");
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
    <script>
        function confirmDeletion() {
            return confirm("تحذير: هل أنت متأكد من أنك تريد حذف هذا العميل؟ سيتم حذف جميع التقارير المرتبطة به، ولن يمكنك استرجاع البيانات لاحقًا.");
        }

        function deleteClient(){
            return confirm ("تحذير انت الان تقوم بحذف بيانات احد الموظفين في  الشركة ! سيتم حذف جميع بياناته وسجلاته من النظام بشكل نهائي ")
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>