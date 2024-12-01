<!DOCTYPE html>
<!-- Created By Mahmoud Ali Abu Shanab -->
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title> Silver Fox</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link rel="stylesheet" href="../public/css/site_status.css">
</head>

<body>
  <div id="error-page">
    <div class="content">
      <h2 class="header error-404" data-text="404">
        404
      </h2>

      <p>
        <?php
        require_once '../includes/config.php';
        // جلب حالة الموقع من الإعدادات
        $stmt = $pdo->query("SELECT is_site_active FROM site_settings");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        // تحقق مما إذا كان الموقع معطلاً
        if (!$settings['is_site_active']) {
          echo ' <h4  data-text="Opps! Page not found">
          عذرًا، الموقع غير متاح حاليًا للصيانة يرجى المحاولة لاحقاً وشكراً
           </h4>';
          echo ' <a href="tel:+963962075261" class="btn btn-outline-info"  > 
            <i class="fa fa-phone"></i>
            الدعم الفني  
            </a>';
          exit();
           // منع الوصول
        }

        // متابعة الكود عند تمكين الموقع
        ?> </p>


    </div>
  </div>
</body>

</html>