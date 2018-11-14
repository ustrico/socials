<?php
$dom = '../';
require($dom . 'auth.php');
$dir = $dom . '../tmp/';
$res = array();
foreach ($_FILES as $files) {
    foreach ($files['name'] as $k => $file) {
        $name = $files['name'][$k];
        $tmp = time();
        $n = strripos($name, '.');
        $name = substr($name, 0, $n);
        $ext = substr($files['name'][$k], $n);
        move_uploaded_file($files['tmp_name'][$k], $dir . $tmp . '.tmp');
        $res[] = array(
            'name' => $name,
            'ext' => $ext,
            'tmp' => $tmp,
        );
    }
}
echo json_encode($res);
