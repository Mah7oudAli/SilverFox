<?php
session_start();
require_once 'includes/config.php';

// دالة لجلب عنوان IP
function getUserIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : "Unknown IP";
}

// دالة للحصول على معلومات الجهاز
function getDeviceInfo() {
    $deviceName = $_SERVER['HTTP_USER_AGENT'];
    $deviceType = preg_match('/mobile/i', $deviceName) ? 'Mobile' : (preg_match('/tablet/i', $deviceName) ? 'Tablet' : 'Desktop');
    return [
        'device_name' => $deviceName,
        'device_type' => $deviceType,
        'device_ip' => getUserIp(),
    ];
}

// دالة للحصول على الموقع بناءً على IP
function getLocation($ip) {
    $url = "http://ip-api.com/json/$ip";
    $locationData = json_decode(file_get_contents($url));
    return isset($locationData->city) && isset($locationData->country) ? $locationData->city . ', ' . $locationData->country : "Unknown Location";
}

// التحقق من بيانات تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // البحث عن المستخدم في جدول الموظفين
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE username = ?");
    $stmt->execute([$username]);
    $employee = $stmt->fetch();

    if ($employee && password_verify($password, $employee['password'])) {
        // حفظ بيانات الجلسة للموظف
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $employee['id'];
        $_SESSION['username'] = $employee['username'];
        $_SESSION['role'] = $employee['role'];
        $_SESSION['user_type'] = 'employee';

        // إعداد كوكيز "تذكرني" عند الطلب
        if ($remember) {
            setcookie('user_id', $employee['id'], time() + (86400 * 30), "/");
            setcookie('role', $employee['role'], time() + (86400 * 30), "/");
        }

        // جمع بيانات الجهاز والموقع
        $deviceInfo = getDeviceInfo();
        $location = getLocation($deviceInfo['device_ip']);
        $login_time = date('Y-m-d H:i:s');

        // تسجيل بيانات الدخول في قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO login_tracking (employee_id, client_id, user_type, username, device_name, device_type, device_ip, location, login_time) VALUES (?, NULL, 'employee', ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$employee['id'], $employee['username'], $deviceInfo['device_name'], $deviceInfo['device_type'], $deviceInfo['device_ip'], $location, $login_time]);

        // إعداد عملية إعادة التوجيه حسب دور الموظف
        $redirectUrl = match($employee['role']) {
            'general_manager' => "admin/dashboard.php",
            'supervisor' => "super-admin/index.php",
            'accountant' => "employee/dashboard.php",
            'marketer' => "marketer/dashboard.php",
            'legal_accountant' => "legal/dashboard.php",
            'it_department' => "support/index.php",
            default => "index.php"
        };

        $_SESSION['success'] = "مرحباً بك، {$employee['username']}! يتم الآن تحويلك...";
        $_SESSION['redirect_url'] = $redirectUrl;

        header("Location: success.php");
        exit();

    } else {
        // التحقق من المستخدم في جدول العملاء
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE username = ?");
        $stmt->execute([$username]);
        $client = $stmt->fetch();

        if ($client && password_verify($password, $client['password'])) {
            // إعداد الجلسة للعميل
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $client['id'];
            $_SESSION['username'] = $client['username'];
            $_SESSION['role'] = 'client';
            $_SESSION['user_type'] = 'client';
            $_SESSION['employee_id'] = $client['employee_id'];

            // إعداد كوكيز "تذكرني" للعميل عند الطلب
            if ($remember) {
                setcookie('user_id', $client['id'], time() + (86400 * 30), "/");
                setcookie('role', 'client', time() + (86400 * 30), "/");
                setcookie('employee_id', $client['employee_id'], time() + (86400 * 30), "/");
            }

            // تسجيل بيانات الجهاز والموقع
            $deviceInfo = getDeviceInfo();
            $location = getLocation($deviceInfo['device_ip']);
            $login_time = date('Y-m-d H:i:s');

            // إدخال بيانات الدخول للعميل في جدول التتبع
            $stmt = $pdo->prepare("INSERT INTO login_tracking (employee_id, client_id, user_type, username, device_name, device_type, device_ip, location, login_time) VALUES (?, ?, 'client', ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$client['employee_id'], $client['id'], $client['username'], $deviceInfo['device_name'], $deviceInfo['device_type'], $deviceInfo['device_ip'], $location, $login_time]);

            $_SESSION['success'] = "مرحباً بك، {$client['username']}! يتم الآن تحويلك...";
            $_SESSION['redirect_url'] = "/client/upload_report.php";

            header("Location: success.php");
            exit();

        } else {
            // رسالة خطأ إذا كانت البيانات غير صحيحة
            $_SESSION['error'] = "اسم المستخدم أو كلمة المرور غير صحيحة.";
            header("Location: index.php");
            exit();
        }
    }
}
?>
