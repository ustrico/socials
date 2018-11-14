<?php
$dom = '../';
require($dom . 'auth.php');
$tmp = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/';

$file = ( !empty($_POST['File']) ) ? $tmp . $_POST['File'] . '.tmp' : false;

if($file) {
    $command = 'ffmpeg -i ' . $file . ' 2>&1';
    $output = shell_exec($command);

    $duration = explode('Duration: ', $output);
    $duration = strstr($duration[1], ',', true);

    $durationhou = (int) substr($duration,0,2);
    $durationmin = (int) substr($duration,4,2);
    $durationsec = (int) substr($duration,6,2);

    $duration = $durationhou*60*60 + $durationmin*60 + $durationsec;

    echo $duration;
}