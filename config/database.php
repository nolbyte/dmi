<?php
//untuk kebutuhan reset password
define('DIR', 'https://sipmb.akfarmahadhika.ac.id/');
define('SITEEMAIL', 'noreply@mahadhika.sch.id');
include('phpmailer/mail.php');

//online
$host     = "localhost"; // Host name
$username = "localhost"; // Mysql username
$password = "123654"; // Mysql password
$db_name  = "userna"; // Database name
$charset  = 'utf8mb4';

// Connect to server and select databse.
$dsn     = "mysql:host=$host;dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
$db->exec('SET FOREIGN_KEY_CHECKS = 0');

date_default_timezone_set('Asia/Jakarta');
