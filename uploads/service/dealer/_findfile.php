<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');

$name1 = '"%' . $_POST['name'] . '"';
$name2 = '"%' . $_POST['name'] . '%"';

$stm = $pdo->prepare('select DownloadItemID, Name from DownloadItem 
  where Url like ' . $name1 . '
  or Image like ' . $name1 . '
  or ImageBig like ' . $name1 . '
  or Video like ' . $name1 . ' 
  or Body like ' . $name2 . '
  order by DownloadItemID desc');
$stm->execute(array());
$down = $stm->fetchAll();

echo json_encode($down);
