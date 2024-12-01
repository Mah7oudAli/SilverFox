<?php
function generate_qr_code_via_api($data, $username) {
    // عنوان API الخارجي لتوليد رمز QR
    $api_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($data);

    // تحميل الصورة باستخدام `file_get_contents`
    $image_data = @file_get_contents($api_url);
    if ($image_data === false) {
        die("فشل في الاتصال بـ API لتوليد رمز QR.");
    }

    $upload_dir = '../uploads/qrcodes/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // إنشاء المجلد مع أذونات الكتابة
    }

    // توليد اسم فريد للصورة باستخدام اسم المستخدم
    $file_name = $username . '_qr.png';

    // المسار الكامل لحفظ الصورة
    $file_path = $upload_dir . $file_name;

    // حفظ الصورة على السيرفر
    file_put_contents($file_path, $image_data);
    if (!file_exists($file_path)) {
        die("فشل في حفظ رمز QR إلى السيرفر.");
    }

    // إرجاع المسار النسبي للصورة (لحفظه في قاعدة البيانات)
    return $file_path;
}

function convertToLatin($text) {
    // خريطة تحويل الأحرف العربية إلى حروف لاتينية
    $arabic_to_latin = array(
        'أ' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'j', 'ح' => 'h', 'خ' => 'kh', 
        'د' => 'd', 'ذ' => 'dh', 'ر' => 'r', 'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 
        'ض' => 'd', 'ط' => 't', 'ظ' => 'th', 'ع' => 'a', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'q', 
        'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n', 'ه' => 'h', 'و' => 'w', 'ي' => 'y',
        'ة' => 'h', 'ى' => 'a', 'ئ' => 'e', 'ء' => '', 'ؤ' => 'w', 'إ' => 'E', 'آ' => 'a', 'ا' => 'a'
    );

    // تحويل النص باستخدام خريطة التحويل
    return strtr($text, $arabic_to_latin);
}

?>
