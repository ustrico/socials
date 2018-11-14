<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');

if($login) {
    foreach($_POST as $k => $file){
        if (stristr($file, 'http')){
            $filear = explode('downloads', $file);
            if ( !empty($filear[1]) ){
                $file = '/downloads' . $filear[1];
            }
        }
        if (ftp_size($connect, 'downloads' . $file)<0){
            $_POST[$k] = 0;
        } else {
            $_POST[$k] = 1;
        }
    }

    echo json_encode($_POST);

} else {
    ftp_quit($connect);
    exit();
}
ftp_quit($connect);

