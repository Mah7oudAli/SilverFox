<?php
// صفحة تسجيل الخروج
session_start();
$_SESSION = [];
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');
?>

<script>
    // منع المستخدم من الرجوع للصفحة السابقة بعد تسجيل الخروج
    if (history.pushState) {
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    }
    window.location.href = "../index.php"; // توجيه المستخدم إلى صفحة تسجيل الدخول
</script>
