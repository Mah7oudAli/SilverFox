<?php
require_once '../includes/session_header.php';
require_once '../includes/config.php';

// التحقق من صلاحيات المستخدم (للمدير فقط)
if ($_SESSION['role'] != 'general_manager') {
    header("Location: ../public/index.php");
    exit();
}

// التحقق من القيم المطلوبة من الرابط
if (!isset($_GET['client_id']) || !isset($_GET['employee_id'])) {
    die("Invalid chat selection.");
}

$client_id = $_GET['client_id'];
$employee_id = $_GET['employee_id'];

// جلب المحادثة بين العميل والموظف
$stmt = $pdo->prepare("
    SELECT m.*, c.username AS client_name, e.username AS employee_name
    FROM messages m
    JOIN clients c ON m.client_id = c.id
    JOIN employees e ON m.employee_id = e.id
    WHERE m.client_id = :client_id AND m.employee_id = :employee_id
    ORDER BY m.sent_at
");
$stmt->execute(['client_id' => $client_id, 'employee_id' => $employee_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض المحادثة</title>
    <link rel="stylesheet" href="../public/css/chate_designe.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            direction: rtl;
            background: linear-gradient(0deg, #000000 10%, #ffffff 100%);    text-align: center;
        }
        .chat-container {
            background: linear-gradient(0deg, #fff 10%, #ffffff 100%);    text-align: center;
            max-width: 800px;
            height: 90%;
            margin: auto;
            padding-top: 20px;
        }
        
        .message {
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 60%;
        }
        .sent {
            background-color: #d1e7dd;
            margin-left: auto;
            text-align: right;
        }
        .received {
            background-color: #f8d7da;
            margin-right: auto;
            text-align: left;
        }
        .message img {
            max-width: 100%;
            height: auto;
            margin-top: 5px;
        }
    </style>
</head>
<body>
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
   

<div class="container shadow-lg p-3 chat-container">
    <h5 class="alert alert-warning  text-center">عرض المحادثة بين <?php echo $messages[0]['client_name'] ?? 'العميل'; ?> و <?php echo $messages[0]['employee_name'] ?? 'الموظف'; ?></h5>

    <div class="chat-box mt-4">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message <?php echo $message['sender_role'] === 'client' ? 'sent' : 'received'; ?>">
                    <strong><?php echo $message['sender_role'] === 'client' ? $message['client_name'] : $message['employee_name']; ?>:</strong>
                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                    
                    <?php if (!empty($message['image_path'])): ?>
                        <img src="../<?php echo htmlspecialchars($message['image_path']); ?>" alt="صورة مرسلة">
                    <?php endif; ?>
                    
                    <span class="text-muted" style="font-size: 0.85em;"><?php echo $message['sent_at']; ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">لا توجد رسائل في هذه المحادثة.</p>
        <?php endif; ?>
    </div>

    <div class="text-center mt-4">
        <a href="review_chat.php" class="btn btn-secondary">العودة إلى قائمة الدردشات</a>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chatBox = document.querySelector(".chat-box");
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>

</body>
</html>
