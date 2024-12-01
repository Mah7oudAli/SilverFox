<?php include 'login_process.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SilverFox Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/login_page.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Page Not Found || error 404</title>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  <title>Page Not Found || error 404</title> -->
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

    <script src="public/js/onesignal.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarNav">
                <ul class="navbar-nav ">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php"> الرئيسية </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"> عــنــا </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact_US.php"> اتـصـل بنا </a>
                    </li>
                </ul>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#policyModal">قراءة سياسة الخصوصية</button>

            </div>
            <h1 class="text-light navbar-brand btn btn-outline-info"> | سلفر فوكس</h1>
        </div>
    </nav>
    <!-- نافذة منبثقة (Modal) سياسة الخصوصية -->
<div class="modal fade text-end" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="policyModalLabel">سياسة الخصوصية وسياسة الاستخدام</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body ">
                <h5>سياسة الخصوصية</h5>
                <p>
                    نحن نهتم بحماية بياناتك الشخصية. باستخدام هذا الموقع، فإنك توافق على أننا قد نجمع ونستخدم معلوماتك وفقًا لهذه السياسة.
                </p>
                <h5>سياسة الاستخدام</h5>
                <p>
                    يمنع مشاركة الروابط المرسلة عبر الموقع مع أي شخص آخر. يجب عليك الحفاظ على سرية بياناتك وكلمات المرور الخاصة بك.
                </p>
                <p>
                    نحتفظ بحق تعديل هذه السياسة في أي وقت، وستكون أي تغييرات مستقبلية سارية عند نشرها.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-success" id="acceptPolicy">أوافق</button>
            </div>
        </div>
    </div>
</div>
    <div class="container">
        <input type="checkbox" id="flip">
        <div class="cover">
            <div class="front">
                <!-- <img src="/img/silver_bg.jpg" alt=""> -->
                <div class="text">
                    <h1 class=" text-light">Silver Fox</h1>
                    <h4 class=" text-light">
                        ســـلـــفر فـــــوكـس
                    </h4>

                    <div class="text-1">
                        للاستشارات المالية والحلول المحاسبية
                    </div>
                </div>
            </div>
            <div class="back">
                <!--<img class="backImg" src="images/backImg.jpg" alt="">-->
                <!-- <div class="text">
                    <span class="text-1">Complete miles of journey <br> with one step</span>
                    <span class="text-2">Let's get started</span>
                </div> -->
            </div>
        </div>
        <div class="forms mt-5">
            <div class="form-content">
                <div class="login-form">
                    <div class="title text-center">تسجيل الدخول</div>

                    <?php
                    // عرض الرسائل في الجلسة (إذا كانت موجودة)
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message']); // مسح الرسالة بعد عرضها
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']); // مسح الرسالة بعد عرضها
                    }
                    ?>

                    <form id="loginForm" method="POST" action="">
                        <div class="input-boxes">
                            <div class="input-box">
                                <i class="fas fa-user"></i>
                                <input type="text" class="form-control" id="username" name="username" placeholder="ادخل اسم المستخدم" required>
                            </div>
                            <div class="input-box">
                                <i class="fa fa-lock"></i>
                                <input type="password" class="form-control" id="password" name="password" placeholder="ادخل كلمة المرور" required>
                            </div>
                            <!-- خيار تذكرني -->
                            <div class="text-end">
                                <label for="remember">تذكرني</label>
                                <input type="checkbox" id="remember" name="remember">
                            </div>
                            <div class="text-end mt-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" class="nav-link" style="color:#7d2ae8;">
                                نسيت كلمة المرور؟
                            </a>
                        </div>
                            <div class="button input-box">
                                <button type="submit" class="btn btn-primary w-100"> تسجيل الدخول </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <script>
            // إذا كانت هناك رسالة وتحتاج إلى إعادة التوجيه
            <?php if (isset($_SESSION['redirect_url'])): ?>
                setTimeout(function() {
                    window.location.href = "<?php echo $_SESSION['redirect_url']; ?>";
                }, 3000); // إعادة التوجيه بعد 3 ثوانٍ
            <?php unset($_SESSION['redirect_url']);
            endif; ?>
        </script>


    </div>
    <!-- نافذة منبثقة لاسترجاع كلمة المرور -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">طلب تغيير كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="support/send_reset_request.php" method="POST">
                    <label>الاسم الكامل:</label>
                    <input type="text" name="full_name" class="form-control" required>

                    <label>الرقم الوطني:</label>
                    <input type="text" name="national_id" class="form-control" required>

                    <label>رقم الهاتف:</label>
                    <input type="text" name="phone" class="form-control" required>

                    <button type="submit" class="btn btn-primary mt-3">إرسال الطلب</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- نافذة إدخال رمز التحقق OTP -->

    <!-- Include JS Libraries -->



    <?php include 'includes/footer.php'; ?>
    <script>
document.getElementById("acceptPolicy").addEventListener("click", function() {
    alert("شكراً لموافقتك على سياسة الخصوصية وسياسة الاستخدام!");
    // يمكنك أيضًا إرسال الموافقة إلى الخادم عبر AJAX أو أي طريقة أخرى.
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>