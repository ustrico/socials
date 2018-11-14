<?php
$dom = (!empty($dom)) ? $dom : '';
session_start();
if (!$_SESSION['user']) {
    header('Location:' . $dom . 'login.php');
    exit;
} else {
    require('_permis.php');
    if ( empty($menua[$menu]) ){
        header('Location:' . $dom);
        exit;
    }
}