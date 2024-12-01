<?php
require_once 'phpqrcode-git/lib/full//qrlib.php';  // تأكد أن المسار صحيح ويشير إلى مكتبة PHP QR Code

function generate_qr_code($data) {
    // تحديد نوع المحتوى ليكون صورة PNG
    header('Content-Type: image/png');  
    // توليد رمز QR كصورة وإرسالها مباشرةً إلى المتصفح
    QRcode::png($data);
}

// بيانات العميل المراد تضمينها في رمز QR
$clientData = 'اسم العميل: علي أحمد\nالهاتف: 123456789';
// توليد رمز QR وعرضه كصورة في المتصفح
generate_qr_code($clientData);
