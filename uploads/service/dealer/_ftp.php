<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');
$tmp = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/';
/*
$_POST = array(
    'filetmp' => '1493104826',
    'fileext' => '.pdf',
    'url' => 'p',
    'dir' => '/downloads/2017/04/',
    'type' => 'upload',
    'max' => 1200,
    'pdf' => 2,
);
*/

if($login) {

    $max = ( !empty($_POST['max']) ) ? $_POST['max'] : false;
    
    $dir = ( !empty($_POST['dir']) ) ? $_POST['dir'] : '';

    $type = ( !empty($_POST['type']) ) ? $_POST['type'] : '';

    $ext = ( !empty($_POST['fileext']) ) ? $_POST['fileext'] : '';

    $pdf = false;
    if ($max && ($ext==='.pdf') ) {
        $ext = '.jpg';
        $pdf = true;
    }

    $dirfull = explode('static.marya.ru', $dir);
    if (!empty($dirfull[1])){
        $dir = $dirfull[1];
    }

    $tmpext = '.tmp';

    if (!$ext) {
        $arr = explode('.', $_POST['filetmp']);
        $ext = '.' . array_pop($arr);
        $tmpext = $ext;
        $_POST['filetmp'] = implode('.', $arr);
    }
    
    $remote = '/downloads/' . $dir . '/' . $_POST['url'] . $ext;
    $i=0;
    $remote1 = str_replace('//', '/', $remote);
    $remote2 = str_replace('//', '/', '/downloads/'.$_POST['filetmp']);
    if ( $remote1 != $remote2 ){
        while (ftp_size($connect, $remote)>-1){
            $i++;
            $remote = '/downloads/' . $dir . '/' . $_POST['url'] . '-' . $i . $ext;
        }
        if ( $i>0 ) {
            $_POST['url'] = $_POST['url'] . '-' . $i;
        }
    }

    $local = $tmp . $_POST['url'] . $ext;
    $tmpfile = $tmp . $_POST['filetmp'] . $tmpext;

    if ($max) {
        if ($type==='upload') {
            rename($tmpfile, $local);
        } else {
            $upload = ftp_get($connect, $local, '/downloads/' . $_POST['filetmp'], FTP_BINARY);
        }

        $im = new Imagick();
        if ( $pdf ) {
            $im->setResolution(300,300);
            $im->readImage($local);
            $im->setIteratorIndex(0);
            $im->setImageBackgroundColor('white');
            $im->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
            $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            $im->setImageFormat('jpg');
            if ( !empty($_POST['pdf']) && ($_POST['pdf']>1) && ($im->getNumberImages()>1) ) {
                $imw = $im->getImageWidth();
                $im->extentImage($imw*2, $im->getImageHeight(), 0, 0);
                $im1 = clone $im;
                $im1->setIteratorIndex(1);
                $im->compositeImage($im1, Imagick::COMPOSITE_DEFAULT, $imw, 0);
            }
            $local = $tmp . $_POST['url'] . $ext;
        } else {
            $im->readImage($local);
        }
        if ( $pdf || ($im->getImageWidth()>$max) || ($im->getImageHeight()>$max) ){
            $im->scaleImage($max, $max, true);
            $im->writeImage($local);
        }
        ftp_con();
        $upload = ftp_put($connect, $remote, $local, FTP_BINARY);

    } else {

        if ($type==='upload') {
            $upload = ftp_put($connect, $remote, $tmpfile, FTP_BINARY);
        } else if ( $remote1 != $remote2 ) {
            $upload = ftp_rename($connect, '/downloads/' . $_POST['filetmp'], $remote);
        }

    }

    echo str_replace('//', '/', $dir . '/' . $_POST['url'] . $ext);

} else {
    ftp_quit($connect);
    exit();
}
ftp_quit($connect);