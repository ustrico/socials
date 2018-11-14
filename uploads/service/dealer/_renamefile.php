<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');

if($login) {

    $old = ( !empty($_POST['old']) ) ? $_POST['old'] : false;

    $new = ( !empty($_POST['new']) ) ? $_POST['new'] : false;

    $fields = array(
        'Url',
        'Image',
        'ImageBig',
        'Video',
        'Body'
    );

    if ($old && $new){

        if ( (ftp_size($connect, '/downloads/' . $new)>-1) && empty($_POST['replace']) ){
            echo 'New name alredy exist';
        } else {
            if (!empty($_POST['items'])){
                $stm = $pdo->prepare('select * from DownloadItem where DownloadItemID in (' . implode(',', $_POST['items']) . ')');
                $stm->execute();
                $down = $stm->fetchAll();
                if (count($down)) {
                    foreach ($down as $item){
                        $ins = '';
                        foreach ($fields as $field){
                            if ( stristr($item[$field], $old) ){
                                $ins .= $field . '="' . str_replace($old, $new, $item[$field]) . '", ';
                            }
                        }
                        $stm  = $pdo->prepare('update DownloadItem
                          set ' . substr($ins, 0, -2) . ' where DownloadItemID=' . $item['DownloadItemID']);
                        $down = $stm->execute();
                    }
                }
            }
            $rename = ftp_rename($connect, '/downloads/' . $old, '/downloads/' . $new);
            echo 1;
        }

    } else {

        echo 'Need 2 names';

    }

} else {
    ftp_quit($connect);
    exit();
}
ftp_quit($connect);

