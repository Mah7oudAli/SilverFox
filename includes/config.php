<?php
// config.php: ملف الاتصال بقاعدة البيانات
$host = 'localhost';
$db   = 'silverfox_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

<head>
<meta property="og:title" content=" سلفر فوكس || Silver Fox ">
<meta property="og:description" content=" استشارات مالية وحلول محاسبية مبتكرة ">
<meta property="og:image" content="https://silverfox-consult.com/img/silver_bg.jpg">
<meta property="og:url" content="https://silverfox-consult.com/about.php">
<meta property="og:type" content="website">
<link rel="icon" type="image/jpg" href="../img/silver_bg.jpg">

</head>