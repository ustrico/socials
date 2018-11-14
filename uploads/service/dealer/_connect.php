<?php
function ftp_con($host = "ftp.selcdn.ru", $port = 21, $user = "47109_imarket", $password = "J81ljWX9MM"){
    global $connect, $login;
    $connect = ftp_connect($host, $port, 6000);
    if(!$connect)
    {
        exit();		
    }
    $login = ftp_login($connect, $user, $password);    
	ftp_pasv($connect, true);
}
ftp_con();

//$pdo = new PDO('mysql:host=95.213.197.202;dbname=gdn2013;charset=utf8','imarket','L8p3D6z3');
$pdo = new PDO('mysql:host=localhost;dbname=gdn2013;charset=utf8','root','');