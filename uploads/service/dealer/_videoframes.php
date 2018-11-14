<?php
$dom = '../';
require($dom . 'auth.php');
$tmp = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/';

$filename = ( !empty($_POST['File']) ) ? $_POST['File'] : '';
$file = ( !empty($_POST['File']) ) ? $tmp . $_POST['File'] . '.tmp' : false;
$time = ( !empty($_POST['Time']) ) ? $_POST['Time'] : false;

$command = 'ffmpeg -i ' . $file . ' 2>&1';
$output = shell_exec($command);

preg_match("/Duration: (.*?), start:/", $output, $matches);
$rawDuration = $matches[1];
$ar = array_reverse(explode(":", $rawDuration));
$duration = floatval($ar[0]);
if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

if ($time=='end') {
    $time = $duration - .1;
} else if ($time==0) {
    $time = 0.1;
} else if ($time>=$duration) {
    $time = $duration - .1;
}

$img = $filename . $time . '.jpg';

$command = 'ffmpeg -ss ' . $time . ' -i ' . $file . ' -an -vframes 1 -y ' . $tmp . $img . ' 2>&1';
$output = shell_exec($command);

echo $img;