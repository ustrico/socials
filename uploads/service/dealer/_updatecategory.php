<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');
$ins = '';
$data = $_POST;
unset($data['DownloadSectionID']);
foreach ($data as $key => $val){
    $ins .= $key . '=:' . $key . ', ';
}
$stm  = $pdo->prepare('update DownloadSection
  set ' . substr($ins, 0, -2) . ' where DownloadSectionID=:DownloadSectionID');
$down = $stm->execute($_POST);
echo $down;
