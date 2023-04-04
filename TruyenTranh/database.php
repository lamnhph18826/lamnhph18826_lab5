<?php

$db_host = 'localhost';
$db_name = 'truyen_apps';
$db_user = 'root';
$db_pass = '';
// phpinfo();
try {
   
    $objConn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $objConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo 'Kết nối CSDL Truyện APP thành công';
} catch (Exception $e) {
    
    die('Loi ket noi CSDL: '. $e->getMessage() );
}