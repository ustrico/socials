<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');

$Where = array();
if ( !empty($_POST['Publish']) ) {
  $Where[] = 'Publish = 1';
}
if ( !empty($_POST['Archive']) ) {
  $Where[] = 'Archive = 0';
}

if (count($Where)){
  $Where = 'where ' . implode(' and ', $Where);
} else {
  $Where = '';
}

$ret = array();

$stm = $pdo->prepare('select
  DownloadSectionID, Name, Priority, ParentSection, Publish
  from DownloadSection order by Priority desc, DownloadSectionID desc');
$stm->execute();
$ret['Sections'] = $stm->fetchAll();

$stm = $pdo->prepare('select
  DownloadItemID, Name, DownloadSectionID, Priority, Publish, Archive
  from DownloadItem ' . $Where . ' order by Priority desc, DownloadItemID desc');
$stm->execute();
$ret['Items'] = $stm->fetchAll();

echo json_encode($ret);

