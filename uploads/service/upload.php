<?php
require('auth.php');
$result = '../tmp/';
$img = array();
foreach ($_FILES as $files) {
    foreach ($files['name'] as $k => $file) {
        $name = preg_replace('/[^A-Za-z0-9_.]/', '', $file);
        $n = strripos($name, '.');
        $name1 = substr($name, 0, $n);
        $name2 = substr($name, $n);
        $name = $result . $name1 . date('YmdHis') . $name2;
        move_uploaded_file($files['tmp_name'][$k], $name);
        $size = getimagesize($name);
        $img [] = array(
            'img' => $name,
            'w' => $size[0],
            'h' => $size[1],
        );
    }
}
echo json_encode($img);
