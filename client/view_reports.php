<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

$clientId = $_SESSION['user_id'];

// جلب تقارير العميل التي قام برفعها
$stmt = $pdo->prepare("SELECT * FROM reports WHERE client_id = ?");
$stmt->execute([$clientId]);
$clientReports = $stmt->fetchAll();

// جلب تقارير المشرف المكتملة والمخصصة لهذا العميل فقط
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE client_id = ? AND status = 'completed'");
$stmt->execute([$clientId]);
$supervisorReports = $stmt->fetchAll();

// جلب اسم العميل لعرضه في الصفحة
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$clientId]);
$client = $stmt->fetch();
?>
<?php


// التحقق من دور المستخدم
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'client') {
    header("Location: /public/index.php");
    exit();
}

require_once '../includes/config.php';

// الحصول على معرف العميل والموظف من الجلسة
$clientId = $_SESSION['user_id'];
$employeeId = $_SESSION['employee_id'];

// جلب بيانات العميل
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$clientId]);
$client = $stmt->fetch();

if (!$client) {
    echo "لا يوجد عميل بهذا المعرف.";
    exit();
}

// جلب الموظف المرتبط بالعميل
$stmt_employee = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt_employee->execute([$employeeId]);
$employee = $stmt_employee->fetch();

if (!$employee) {
    echo "لا يوجد موظف مرتبط بهذا العميل.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقارير العميل</title>
    <link rel="stylesheet" href="../public/css/client.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/db5840f177.js" crossorigin="anonymous"></script>


</head>
<!-- CSS للنافذة المنبثقة -->
<style>
    /* نمط النافذة */
    .modal {
        display: none;
        /* مخفية افتراضيًا */
        position: fixed;
        z-index: 1055;
        padding-top: 10px;
        left: 0;
        top: 0;
        width: 100%;
        height: auto;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }

    /* نمط الصورة داخل النافذة */
    .modal-content {
        margin: auto;
        display: block;
        max-width: 100%;
        width: 50%;
        max-height: 100%;
height: auto;
        border-radius: 8px;
        animation: zoom 0.3s;
    }

    /* زر الإغلاق */
    .close {
        position: absolute;
        top: 10px;
        right: 50px;
        color: #fff;
        font-size: 35px;
        font-weight: bold;
        cursor: pointer;
    }

    /* تأثير التكبير */
    @keyframes zoom {
        from {
            transform: scale(0.7)
        }

        to {
            transform: scale(1)
        }
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <h4 class="text-light navbar-brand btn btn-outline-info"><?php echo htmlspecialchars($client['full_name']); ?>!</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="chatButton">
                الدردشة
                <span class="position-relative top-150 start-0 translate-middle badge rounded-pill bg-danger" id="unreadCount">
                    0
                </span>
            </button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="upload_report.php">رفع تقرير</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_reports.php">التقارير الخاصة بك</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">تسجيل الخروج</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="staticBackdropLabel">الدردشة مع الموظف </h5>

                </div>
                <div class="modal-body">
                    <div class="col chat-container">
                        <!-- <div class="employee-info">
                    <h4>الموظف المرتبط بك</h4>
                    <p><strong>الاسم: </strong><?php echo htmlspecialchars($employee['username']); ?></p>
                    <p><strong>الدور: </strong><?php echo htmlspecialchars($employee['role']); ?></p>
                </div> -->

                        <div class="chat-box" id="chat-box">
                            <div class="messages" id="chat-messages">
                                <!-- رسائل الدردشة ستظهر هنا -->
                                <!-- نافذة عرض الصورة المنبثقة -->


                            </div>

                        </div>

                        <div class="typing-area">
                            <div class="input-area input-group">
                                <input type="text" id="message-input" class="form-control" placeholder="أدخل رسالتك هنا..." required>
                                <label for="image-input" class="image-icon input-group-text">
                                    <i class="fa-solid fa-image"></i>
                                </label>
                                <input type="file" id="image-input" accept="image/*" style="display: none;">
                                <button id="send-message" class="btn btn-primary">إرسال</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info w-100" data-bs-dismiss="modal">اغلاق الدردشة

                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="title">التقارير الخاصة بك</h2>
        <div class="row">
            <div class="col-md-6">
                <h3>التقارير التي قمت برفعها</h3>
                <!-- قائمة التقارير -->
                <ul class="list-group">
                    <?php foreach ($clientReports as $report): ?>
                        <li class="list-group-item">
                            <img
                                src="../uploads/<?php echo htmlspecialchars($report['file_name']); ?>"
                                width="75px"
                                height="75px"
                                alt="تقرير"
                                class="report-thumbnail"
                                onclick="openModal('../uploads/<?php echo htmlspecialchars($report['file_name']); ?>')">
                            <small class="text-muted ">تاريخ الرفع: <?php echo date('Y-m-d H:i:s', strtotime($report['created_at'])); ?></small>
                            <small class="text-muted fw-bold"> نوع التقرير : <?php echo htmlspecialchars($report['transaction_type']); ?></small>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($clientReports) === 0): ?>
                        <li class="list-group-item">لا توجد تقارير تم رفعها بعد.</li>
                    <?php endif; ?>
                </ul>

            </div>

            <div class="col-md-6">
                <h3>التقارير من المشرف</h3>
                <ul class="list-group">
                    <?php foreach ($supervisorReports as $report): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="../uploads/<?php echo htmlspecialchars($report['completed_report']); ?>" target="_blank">عرض التقرير <?php echo $report['id']; ?></a>
                                <small class="text-muted">تاريخ الإكمال: <?php echo date('Y-m-d H:i:s', strtotime($report['updated_at'])); ?></small>
                            </div>
                            <!-- زر التحميل -->
                            <a href="../uploads/<?php echo htmlspecialchars($report['completed_report']); ?>" download class="btn btn-outline-primary btn-sm ms-3">
                                <i class="fa fa-download"></i> تحميل
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($supervisorReports) === 0): ?>
                        <li class="list-group-item">لا توجد تقارير من المشرف بعد.</li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </div>
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>
    <?php include '../includes/footer.php'; ?>

    <script>
        let currentEmployeeId = <?php echo json_encode($employee['id']); ?>;
        let currentClientId = <?php echo json_encode($client['id']); ?>;
        let isMessageSending = false;

        function loadMessages() {
            if (currentEmployeeId && currentClientId && !isMessageSending) {
                $.ajax({
                    url: '../includes/client_messages_display.php',
                    type: 'POST',
                    data: {
                        employee_id: currentEmployeeId,
                        client_id: currentClientId
                    },
                    success: function(data) {
                        $('#chat-messages').html(data);
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error loading messages:', textStatus, errorThrown);
                    }
                });
            }
        }

        $('#send-message').on('click', function() {
            let message = $('#message-input').val();
            if (message && currentEmployeeId && currentClientId) {
                isMessageSending = true;
                $.ajax({
                    url: '../includes/client_send_message.php',
                    type: 'POST',
                    data: {
                        message: message,
                        employee_id: currentEmployeeId,
                        client_id: currentClientId
                    },
                    success: function() {
                        $('#message-input').val('');
                        loadMessages();
                    },
                    complete: function() {
                        isMessageSending = false;
                    }
                });
            }
        });

        setInterval(loadMessages, 3000);
    </script>
    <script src="../public/js/image_size_chate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>