<?php 
// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "silverfox_db");
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// $conn = new mysqli("localhost", "u116586275_silverfxMahmod", "Mahmoud@silverfox7eng", "u116586275_Silverfox_db");
// if ($conn->connect_error) {
//     die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
// }
