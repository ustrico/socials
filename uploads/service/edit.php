<?php
require('auth.php');
$dir = substr($_SERVER['SCRIPT_FILENAME'], 0, -8);
$name = $_POST['name'];

$n = strripos($name, '.');
$name1 = substr($name, 0, $n);
$name2 = substr($name, $n);
$name = $name1 . 'v' . $_POST['version'] . $name2;

$im = new Imagick($dir . $_POST['name']);

switch ($_POST['edit']){
    case 'mirror':
        $im->flopImage();
        break;
    case 'rotate':
        $im->rotateimage(new ImagickPixel('#ffffff'), 90);
        break;
    case 'brightplus':
        $im->modulateImage(120, 100, 100);
        break;
    case 'brightminus':
        $im->modulateImage(80, 100, 100);
        break;
    case 'contrplus':
        $im->modulateImage(100, 120, 100);
        break;
    case 'contrminus':
        $im->modulateImage(100, 80, 100);
        break;
}

$im->writeImage($dir . $name);
echo $name;
