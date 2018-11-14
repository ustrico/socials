<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');
$ins = '';
$data = $_POST;
unset($data['DownloadItemID']);
foreach ($data as $key => $val){
    $ins .= $key . '=:' . $key . ', ';
}
$stm  = $pdo->prepare('update DownloadItem
  set ' . substr($ins, 0, -2) . ' where DownloadItemID=:DownloadItemID');
$down = $stm->execute($_POST);
echo $down;
