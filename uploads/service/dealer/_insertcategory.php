<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');
$ins = '';
foreach ($_POST as $key => $val){
    $ins .= $key . '=:' . $key . ', ';
}
$stm  = $pdo->prepare('insert into DownloadSection set ' . substr($ins, 0, -2));
$stm->execute($_POST);
$down = $pdo->lastInsertId();
echo $down;
