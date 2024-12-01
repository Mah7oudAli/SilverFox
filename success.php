<?php
session_start();

if (!isset($_SESSION['success']) || !isset($_SESSION['redirect_url'])) {
    header("Location: index.php");
    exit();
}

$message = $_SESSION['success'];
$redirectUrl = $_SESSION['redirect_url'];

// إزالة الرسائل بعد العرض
unset($_SESSION['success'], $_SESSION['redirect_url']);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول ناجح</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        setTimeout(function() {
            window.location.href = "<?php echo htmlspecialchars($redirectUrl); ?>";
        }, 3000);
    </script>
</head>
<style>
   
</style>
<body>
    <!-- نافذة منبثقة -->
    <div class="modal fade bg-dark" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="successModalLabel">تسجيل الدخول ناجح</h5>
                </div>
                <div class="modal-body bg-dark text-light text-center">
                    <p><?php echo htmlspecialchars($message); ?></p>
                    <p>ستتم إعادة توجيهك خلال لحظات </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="redirectNow()">الانتقال الآن</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script لإظهار النافذة -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // عرض النافذة تلقائيًا عند تحميل الصفحة
        const successModal = new bootstrap.Modal(document.getElementById('successModal'), {
            backdrop: 'static', // منع إغلاق النافذة عند النقر خارجها
            keyboard: false     // منع الإغلاق عند الضغط على Escape
        });
        successModal.show();

        // الدالة لإعادة التوجيه الفوري
        function redirectNow() {
            window.location.href = "<?php echo htmlspecialchars($redirectUrl); ?>";
        }
    </script>
</body>
</html>
