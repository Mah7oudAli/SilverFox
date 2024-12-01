<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

// التحقق من صلاحية الوصول للموظف
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'accountant') {
    header("Location: ../index.php");
    exit();
}

// التحقق من وجود معرف الموظف في الجلسة
if (!isset($_SESSION['user_id'])) {
    echo 'لم يتم تسجيل الدخول بشكل صحيح.';
    exit();
}

// جلب بيانات الموظف من جدول المستخدمين
$stmt = $pdo->prepare("SELECT username FROM employees WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$employee = $stmt->fetch();

if (!$employee) {
    echo 'لا يوجد موظف بهذا المعرف.';
    exit();
}

// جلب المهام والتقارير اليومية الخاصة بالموظف مع اسم العميل
$stmt_reports = $pdo->prepare("
SELECT r.id, c.username AS username, r.file_name, r.transaction_type, r.created_at
FROM reports r
JOIN clients c ON r.client_id = c.id
WHERE c.employee_id = ?
");
$stmt_reports->execute([$_SESSION['user_id']]);
$reports = $stmt_reports->fetchAll();

// جلب قائمة العملاء المرتبطين بالموظف
$stmt_clients = $pdo->prepare("SELECT * FROM clients WHERE employee_id = ?");
$stmt_clients->execute([$_SESSION['user_id']]);
$clients = $stmt_clients->fetchAll();
?>


<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الموظف</title>
    <link rel="stylesheet" href="../public/css/chate_designe.css">
    <script src="https://kit.fontawesome.com/db5840f177.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({
        appId: "99e1b7d4-ffe5-42e3-934b-ec2e62cebbc9", // استبدل بـ App ID الخاص بك
    });

    // تعيين Tag لمعرف المستخدم
    const userId = "<?= $_SESSION['user_id']; ?>"; // معرف المستخدم من PHP
    OneSignal.sendTag("user_id", userId).then(function(tagsSent) {
        console.log("Tags were sent successfully:", tagsSent);
    });
});

</script>
</head>
<style>
    .phone {
        text-decoration: none;
        background: linear-gradient(150deg, #04487c 32%, #000fe6 46%, #0d0d0d 74%);
        outline: 3px ridge #0a31a3;
        border: none;
        border-radius: 10px;
        padding: 0.2em 0.5em;
        width: 50%;
        color: white;
    }
</style>

<?php
// جلب الإعدادات الحالية
$stmt = $pdo->query("SELECT disable_employee_section FROM site_settings");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// تحقق مما إذا كان قسم الموظفين معطلاً
if ($settings['disable_employee_section']) {
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
            max-width: 500px;
            text-align: center;
        ">
            <h1 style="font-size: 1.8em; color: #343a40; margin-bottom: 20px;">🚫 عذرًا، قسم الموظفين غير متاح حاليًا</h1>
            <p style="font-size: 1.1em; color: #6c757d;">
                يبدو أن قسم الموظفين قد تم تعطيله مؤقتًا. نعتذر عن الإزعاج، يُرجى المحاولة لاحقًا أو الاتصال بالدعم الفني لمزيد من المعلومات.
            </p>
            <a href="tel:+963962075261" class="phone"  > 
            <i class="fa fa-phone"></i>
            الدعم الفني  
            
            </a>
        </div>
    </div>
    ';
    exit(); // منع الوصول
}


?>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">الصفحة الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_tasks.php">تسليم تقرير </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">تسجيل الخروج</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">مرحباً، <?php echo htmlspecialchars($employee['username']); ?></a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    دردشات العملاء
                </button>
                <div class="dropdown-menu  dropdown-menu-dark p-4" aria-labelledby="dropdownMenuButton2">

                    <?php foreach ($clients as $client): ?>
                        <a href="#" class="client-link btn btn-info position-relative m-3 dropdown-item active"
                            data-client-id="<?php echo $client['id']; ?>"
                            data-client-username="<?php echo htmlspecialchars($client['username']); ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">
                            <?php echo htmlspecialchars($client['username']); ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                99+
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </nav>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <h5 class="modal-title" id="staticBackdropLabel">
                        Client Chat: <span id="modal-client-name"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="chat-box" id="chat-box">
                        <div class="messages" id="chat-messages">
                            <!-- رسائل الدردشة ستظهر هنا -->
                        </div>
                    </div>

                    <div class="typing-area">
                        <div class="input-area form-group ">
                            <input type="text" id="message-input" class="form-control" placeholder="أدخل رسالتك هنا..." required>
                            <label for="image-input" class="image-icon input-group-text">
                                <i class="fa-solid fa-image"></i>
                            </label>
                            <input type="file" id="image-input" class="form-group" accept="image/*" style="display: none;">
                            <button id="send-message" class="btn btn-primary">إرسال</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark w-100" data-bs-dismiss="modal">إغلاق الدردشة</button>

                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <h1 class="alert alert-info text-center"> الموظف: <?php echo htmlspecialchars($employee['username']); ?></h1>

        <div class="title text-center">التقارير اليومية الخاصة بك</div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>اسم العميل</th>
                    <th> صورة التقرير</th>
                    <th>نوع المهمة</th>
                    <th>تاريخ الإنشاء</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reports) > 0): ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['username']); ?></td>
                            <td>
                                <img src="../uploads/<?php echo htmlspecialchars($report['file_name']); ?>" width="50px" height="50px" alt=""
                                    class="thumbnail" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-url="../uploads/<?php echo htmlspecialchars($report['file_name']); ?>" />
                            </td>
                            <td><?php echo htmlspecialchars($report['transaction_type']); ?></td>
                            <td><?php echo htmlspecialchars($report['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">لا توجد تقارير حالياً.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="imageModalLabel">عرض تقرير</h5>

                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="صورة التقرير" class="img-fluid" width="400px" height="400px" />
                    </div>
                    <div class="modal-footer">
                        <!-- زر التحميل -->
                        <a id="downloadButton" href="" download="report_image.jpg" class="btn btn-outline-primary w-100 fs-3">
                            تحميل الصورة
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- <div class="col h-100 chat-container">
        <div class="employee-info">
            <h4>العملاء المرتبطين</h4>
            <?php foreach ($clients as $client): ?>
                <a href="#" class="client-link" data-client-id="<?php echo $client['id']; ?>">
                    <?php echo htmlspecialchars($client['username']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div> -->




    <footer class="text-center text-lg-start ">
        <div class="text-center p-3">
            <b>سلفر فوكس</b>
            <span> 2024 حقوق النشر محفوظة &copy; </span>
            <a class="text-light" href="https://MahmoudAli.Nadim.pro">

                <b> <i>By:</i> </b>

                <strong>Mahmoud Ali Abu Shanab</strong>
            </a>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // عند تحميل الصفحة
        document.addEventListener("DOMContentLoaded", function() {
            // اختيار جميع الروابط الخاصة بالعملاء
            document.querySelectorAll('.client-link').forEach(link => {
                link.addEventListener('click', function() {
                    // جلب اسم العميل من data attribute
                    const clientName = this.getAttribute('data-client-username');
                    // تحديث العنوان داخل الـ Modal
                    document.getElementById('modal-client-name').textContent = clientName;
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // إدارة الصور في المودال
            $('.thumbnail').on('click', function() {
                var imageUrl = $(this).data('image-url');
                $('#modalImage').attr('src', imageUrl);
            });

            let currentClientId = null;

            // عند النقر على اسم العميل
            $('.client-link').on('click', function() {
                currentClientId = $(this).data('client-id');
                $('.chat-box').show(); // إظهار واجهة الدردشة
                $('.clients-list').hide(); // إخفاء قائمة العملاء
                loadMessages(); // تحميل الرسائل
            });

            // إغلاق الدردشة والعودة إلى قائمة العملاء
            $('#close-chat').on('click', function() {
                $('.chat-box').hide();
                $('.clients-list').show();
                $('#chat-messages').html(''); // تفريغ الرسائل
            });

            // تحميل الرسائل الخاصة بالعميل المختار
            function loadMessages() {
                if (currentClientId) {
                    $.ajax({
                        url: '../includes/get_messages.php',
                        type: 'POST',
                        data: {
                            client_id: currentClientId
                        },
                        success: function(data) {
                            $('#chat-messages').html(data);
                        }
                    });
                }
            }

            // إرسال رسالة جديدة
            $('#send-message').on('click', function() {
                let message = $('#message-input').val();
                let imageFile = $('#image-input')[0].files[0];

                if ((message || imageFile) && currentClientId) {
                    let formData = new FormData();
                    formData.append('message', message);
                    formData.append('client_id', currentClientId);

                    if (imageFile) {
                        formData.append('image', imageFile);
                    }

                    // تعطيل زر الإرسال حتى يتم الانتهاء من الإرسال
                    $(this).prop('disabled', true);

                    $.ajax({
                        url: '../includes/send_message.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            let result = JSON.parse(response);
                            if (result.status === 'success') {
                                $('#message-input').val(''); // تفريغ حقل الرسالة بعد الإرسال
                                $('#image-input').val(''); // تفريغ حقل الصورة بعد الإرسال
                                loadMessages(); // إعادة تحميل الرسائل بعد الإرسال
                            } else {
                                alert(result.message); // عرض رسالة الخطأ
                            }
                        },
                        complete: function() {
                            // إعادة تفعيل زر الإرسال بعد الانتهاء
                            $('#send-message').prop('disabled', false);
                        }
                    });
                }
            });

            // تحديث تلقائي للرسائل
            setInterval(function() {
                if (currentClientId) { // تأكد من أن العميل المحدد موجود
                    loadMessages();
                }
            }, 3000);
        });
    </script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatBox = document.querySelector(".chat-box");
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>