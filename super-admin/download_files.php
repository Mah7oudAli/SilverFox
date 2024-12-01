<?php 
require_once '../includes/config.php';
require_once '../pclzip/pclzip.lib.php'; // تضمين مكتبة PclZip

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];
    
    // جلب اسم العميل باستخدام client_id
    $stmt = $pdo->prepare("SELECT full_name FROM clients WHERE id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // التأكد من أن اسم العميل موجود
    if ($client) {
        $client_name = $client['full_name']; // اسم العميل
        
        // جلب الملفات الخاصة بالعميل
        $stmt = $pdo->prepare("SELECT completed_report FROM tasks WHERE client_id = ?");
        $stmt->execute([$client_id]);
        $files = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($files) {
            // استخدام اسم العميل في اسم الملف
            $zipFileName = $client_name . "_files.zip"; // اسم الملف سيكون اسم العميل مع "_files.zip"
            $zip = new PclZip($zipFileName);

            // إضافة الملفات إلى الأرشيف
            $filePaths = [];
            foreach ($files as $file) {
                $filePath = "../uploads/" . $file;
                if (file_exists($filePath)) {
                    $filePaths[] = $filePath;
                }
            }

            if (count($filePaths) > 0) {
                $zip->create($filePaths, PCLZIP_OPT_REMOVE_PATH, "../uploads"); // يمكنك إضافة ملفاتك هنا

                // تهيئة رؤوس HTTP لتحميل الملف مباشرةً
                header("Content-Type: application/zip");
                header("Content-Disposition: attachment; filename=" . basename($zipFileName));
                header("Content-Length: " . filesize($zipFileName));

                readfile($zipFileName);
                unlink($zipFileName); // حذف الملف المضغوط بعد التنزيل
                exit;
            } else {
                echo "لا توجد ملفات للتحميل لهذا العميل.";
            }
        } else {
            echo "لا توجد ملفات للتحميل لهذا العميل.";
        }
    } else {
        echo "العميل غير موجود.";
    }
}
?>
