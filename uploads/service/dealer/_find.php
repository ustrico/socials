<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');

$stm  = $pdo->prepare('select 
  DownloadItem.*, DownloadSection.Name as SectionName
  from DownloadItem 
    left join DownloadSection on (DownloadItem.DownloadSectionID=DownloadSection.DownloadSectionID) 
  where DownloadItem.Name like :name 
  order by /*DownloadItem.Priority desc,*/ DownloadItem.DownloadItemID desc 
  limit 20');
$stm->execute(array(
    'name' => '%' . $_POST['name'] . '%'
));

$down = $stm->fetchAll();

if ( empty($down) ){
    $stm  = $pdo->prepare('select 
      DownloadItem.*, DownloadSection.Name as SectionName
      from DownloadItem 
        left join DownloadSection on (DownloadItem.DownloadSectionID=DownloadSection.DownloadSectionID) 
      where DownloadItem.Url like :name 
      order by DownloadItem.DownloadItemID desc 
      limit 20');
    $stm->execute(array(
        'name' => '%' . $_POST['name'] . '%'
    ));

    $down = $stm->fetchAll();
}

if ( empty($down) ){
    $stm  = $pdo->prepare('select 
      DownloadItem.*, DownloadSection.Name as SectionName
      from DownloadItem 
        left join DownloadSection on (DownloadItem.DownloadSectionID=DownloadSection.DownloadSectionID) 
      where DownloadItem.DownloadItemID like :name 
      order by DownloadItem.DownloadItemID desc 
      limit 20');
    $stm->execute(array(
        'name' => '%' . $_POST['name'] . '%'
    ));

    $down = $stm->fetchAll();
}

echo json_encode($down);
