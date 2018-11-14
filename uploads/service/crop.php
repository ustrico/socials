<?php
require('auth.php');
$dir = substr($_SERVER['SCRIPT_FILENAME'], 0, -8);
$name = '../tmp/' . $_POST['name'] . '-' . date('YmdHis') . '.png';
$pixel = new ImagickPixel('transparent');
$colorT = '#ffffff';

if (!empty($_POST['collage'])) {
    $im1 = new Imagick();
    $im1->newImage($_POST['w'], $_POST['h'], new ImagickPixel('white'));
    foreach ($_POST['collage'] as $collage){
        $im = new Imagick($dir . $collage['img']);
        $im->cropImage($collage['x2'] - $collage['x1'], $collage['y2'] - $collage['y1'], $collage['x1'], $collage['y1']);
        $im->scaleImage($collage['w'], $collage['h']);
        $im1->compositeImage($im, Imagick::COMPOSITE_DEFAULT, $collage['x'], $collage['y']);
    }
    $im2 = new Imagick($dir . $_POST['tile']);
    $im1->compositeImage($im2, Imagick::COMPOSITE_DEFAULT, 0, 0);
    //$im->newImage($_POST['w'], $_POST['h'], new ImagickPixel('white'));
    //$im->compositeImage($im1, Imagick::COMPOSITE_DEFAULT, 0, 0);
    $im = $im1;
} else {
    $im = new Imagick($dir . $_POST['img']);
    $im->cropImage($_POST['x2'] - $_POST['x1'], $_POST['y2'] - $_POST['y1'], $_POST['x1'], $_POST['y1']);
    $im->scaleImage($_POST['w'], $_POST['h']);
    $im2 = new Imagick($dir . $_POST['tile']);
    $im->compositeImage($im2, Imagick::COMPOSITE_DEFAULT, 0, 0);
}

if (!empty($_POST['digittop'])) {
    $im2 = new Imagick();
    $im2->newImage($_POST['w'], $_POST['h'], $pixel);
    $draw = new ImagickDraw();
    $color = new ImagickPixel($colorT);
    $draw->setFillColor($color);
    $draw->setFont($dir . $_POST['digitfont']);
    $draw->setFontSize($_POST['digitsize']);
    if (!empty($_POST['align'])) {
        if ($_POST['align'] == 'center') {
            $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        }
    }
    if (stristr($_POST['digitfont'], 'Chianti')) {
        $draw->setTextInterlineSpacing(-$_POST['digitsize'] / 3);
    }
    $titleA = nl2br($_POST['title']);
    $titleA = explode('<br />', $titleA);
    $Digit = trim($titleA[0]);
    unset($titleA[0]);
    $_POST['title'] = trim(implode('', $titleA));
    $im2->annotateImage($draw, $_POST['digitleft'], $_POST['digittop'], 0, $Digit);
    $im->compositeImage($im2, Imagick::COMPOSITE_DEFAULT, 0, 0);
}

if (!empty($_POST['title'])) {
    $im1 = new Imagick();
    $im1->newImage($_POST['w'], $_POST['h'], $pixel);
    $draw = new ImagickDraw();
    if (!empty($_POST['color'])) {
        $colorT = $_POST['color'];
    }
    $color = new ImagickPixel($colorT);
    $draw->setFillColor($color);
    $draw->setFont($dir . $_POST['font']);
    $draw->setFontSize($_POST['size']);
    if (stristr($_POST['font'], 'Chianti')) {
        $draw->setTextInterlineSpacing(-$_POST['size'] / 3);
    }
    $top = $_POST['top'];
    if (!empty($_POST['align'])) {
        if ($_POST['align'] == 'center') {
            $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        } else if ($_POST['align'] == 'middle') {
            $draw->setTextInterlineSpacing(-$_POST['size'] / 5);
            $text = $im1->queryFontMetrics($draw, $_POST['title']);
            $lines = floor($text['textHeight'] / $text['characterHeight']);
            $top = $_POST['top'] - ($text['textHeight'])/2  + $text['characterHeight'];
        }
    }
    $im1->annotateImage($draw, $_POST['left'], $top, 0, $_POST['title']);
    $im->compositeImage($im1, Imagick::COMPOSITE_DEFAULT, 0, 0);
}
//$im->setImageCompression(imagick::COMPRESSION_JPEG );
//$im->setimagecompressionquality( 100 );
$im->writeImage($dir . $name);
echo $name;

?>
