<?php
require_once '../includes/session_header.php';

// التحقق من دور المستخدم
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'client') {
    header("Location: ../index.php");
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
<?php
// $stmt = $pdo->prepare("
// SELECT COUNT(*) AS unread_count 
// FROM messages 
// WHERE employee_id = ? 
//   AND client_id = ? 
//   AND sender_role = 'employee' 
//   AND is_read = FALSE
// ");

// $stmt->execute([$employeeId, $clientId]);
// $unreadCount = $stmt->fetchColumn();
// echo $unreadCount;
// $_SESSION['new_massege'] = $unreadCount;

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>واجهة العميل: <?php echo htmlspecialchars($client['full_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/client.css">
    <script src="https://kit.fontawesome.com/db5840f177.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/db5840f177.js" crossorigin="anonymous"></script>
    <!-- ملف CSS الخاص بالمكتبة -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty/lib/noty.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty/lib/themes/mint.css">

    <!-- ملف JavaScript الخاص بالمكتبة -->
    <script src="https://cdn.jsdelivr.net/npm/noty"></script>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script src="../public/js/onesignal.js"></script>



</head>
<style>
    .phone {
        text-decoration: none;
        background: linear-gradient(150deg, #04487c 32%, #000fe6 46%, #0d0d0d 74%);
        outline: 3px ridge #0a31a3;

        border-radius: 10px;
        border: none;
        padding: 0.2em 0.5rem;
        width: 50%;
        color: white;


    }
</style>
<?php
// جلب الإعدادات الحالية
$stmt = $pdo->query("SELECT disable_client_section FROM site_settings");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// تحقق مما إذا كان قسم العملاء معطلاً
if ($settings['disable_client_section']) {
    echo '
    <div style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
    ">
        <div style="
            background-color: #f8f9fa;
            padding: 30px 50px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            text-align: center;
        ">
            <h1 style="font-size: 1.8em; color: #343a40; margin-bottom: 20px; " class="alert alert-danger ">🚫 عذرًا، قسم العملاء غير متاح حاليًا!</h1>
            <p style="font-size: 1.1em; color: #6c757d;">
                يبدو أن قسم العملاء  قد تم تعطيله مؤقتًا. نعتذر عن الإزعاج، يُرجى المحاولة لاحقًا أو الاتصال بالدعم الفني لمزيد من المعلومات.
            </p>
            <a href="tel:+963962075261" class="phone"  > 
            <i class="fa fa-phone"></i>
            الدعم الفني  
            
            </a>
        </div>
    </div>
    ';
    echo "عذرًا، قسم العملاء غير متاح حاليًا.";
    exit(); // منع الوصول
}

?>


<body>
    <!-- ناف بار خاص بالعميل -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <h4 class="text-light navbar-brand btn btn-outline-info"><?php echo htmlspecialchars($client['full_name']); ?>!</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="chatButton">
                الدردشة
                <span class="position-relative top-0 start-50 translate-middle badge rounded-pill bg-danger" id="unreadCount">
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
    <!-- model for chate clienys -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">الدردشة مع الموظف </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <div id="imageModal" class="image-modal">
        <span class="close">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>
    <div class="container shadow-lg">
        <h1 class="alert alert-primary text-center">رفع تقرير جديد</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-warning text-center">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']); // مسح الرسالة بعد عرضها
                ?>
            </div>
            <?php endif; ?><?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-warning text-center">
                <?php
                                echo $_SESSION['message'];
                                unset($_SESSION['message']); // مسح الرسالة بعد عرضها
                ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col" id="report-upload-section">
                <form id="uploadForm" enctype="multipart/form-data" action="upload_process.php" method="POST">
                    <div class="mb-3">
                        <label for="report" class="form-label">اختر ملف التقرير (صورة فقط)</label>
                        <input class="form-control" type="file" id="report" name="report" accept="image/*" required>
                    </div>

                    <!-- منطقة عرض المعاينة -->
                    <div id="previewContainer" class="preview-container">
                        <img id="previewImage" src="" alt="معاينة الصورة" style="display: none;">
                    </div>

                    <div class="d-flex m-4 ">
                        <div class="form-groub col-md-1">
                            <input type="radio" name="transaction" id="sale" value="مبيع" required>
                            <label for="sale">مبيع</label>
                        </div>
                        <div class="form-groub col-md-1">
                            <input type="radio" name="transaction" id="purchase" value="شراء" required>
                            <label for="purchase">شراء</label>
                        </div>
                        <div class="form-groub col-md-1">
                            <input type="radio" name="transaction" id="daily" value="يومية" required>
                            <label for="daily">يومية</label>
                        </div>
                    </div>

                    <button type="submit" class="send_report">ارسال التقرير
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script>
        let currentEmployeeId = <?php echo json_encode($employee['id']); ?>;
        let currentClientId = <?php echo json_encode($client['id']); ?>;

        let isUpdatingUnreadCount = false;
        let isUpdatingMessages = false;

        let isChatOpen = false; // لتتبع حالة نافذة الدردشة

        // تحديث العد غير المقروء
        function updateUnreadCount() {
            $.ajax({
                url: '../includes/get_unread_messages_count.php',
                type: 'POST',
                data: {
                    client_id: currentClientId,
                    employee_id: currentEmployeeId
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    const unreadCount = data.unread_count || 0;

                    // تحديث العداد
                    if (unreadCount > 0) {
                        $('#unreadCount').text(unreadCount).show();
                    } else {
                        $('#unreadCount').text('0').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching unread messages:', textStatus, errorThrown);
                }
            });
        }

        function onNewMessageReceived() {
            // إظهار الإشعار باستخدام مكتبة Noty.js
            new Noty({
                text: 'تم استلام رسالة جديدة من الموظف ',
                type: 'info',
                layout: 'topRight',
                timeout: 3000
            }).show();

            // تحديث العداد بعد استلام الرسالة
            updateUnreadCount();
        }

        let previousMessages = '';

        // تحميل الرسائل
        function loadMessages() {
            if (isUpdatingMessages) return;
            isUpdatingMessages = true;

            $.ajax({
                url: '../includes/client_messages_display.php',
                type: 'POST',
                data: {
                    employee_id: currentEmployeeId,
                    client_id: currentClientId
                },
                success: function(data) {
                    if (data !== previousMessages) {
                        $('#chat-messages').html(data);
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

                        // عرض إشعار عند الوصول رسائل جديدة إذا كانت نافذة الدردشة غير مفتوحة
                        if (previousMessages !== '') {
                            new Noty({
                                text: 'رسالة جديدة وصلت من الموظف!',
                                type: 'success',
                                layout: 'topRight',
                                timeout: 3000
                            }).show();
                        }

                        previousMessages = data;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading messages:', textStatus, errorThrown);
                },
                complete: function() {
                    isUpdatingMessages = false;
                }
            });
        }

        // فتح نافذة الدردشة
        $('#chatButton').on('click', function() {
            isChatOpen = true;
            $('#unreadCount').text('0'); // إعادة تعيين العد
        });

        // إغلاق نافذة الدردشة
        $('.btn-close, .modal-footer button').on('click', function() {
            isChatOpen = false;
        });

        // تحديث دوري للرسائل وعدد الرسائل غير المقروءة
        setInterval(() => {
            updateUnreadCount();
            loadMessages();
        }, 2000);
    </script>


    <script src="../public/js/image_size_chate.js"></script>

    <!-- <script src="../public/js/alert_messiges.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>