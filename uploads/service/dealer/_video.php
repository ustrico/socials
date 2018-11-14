<?php
$dom = '../';
require($dom . 'auth.php');
$tmp = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/';
$uploads = 'http://' . $_SERVER['SERVER_NAME'] . '/uploads/';
/*
$_POST = array(
    'Size'          => '640x360',
    'Bitrate'       => '1500',
    'Rate'          => '24',
    'AudioBitrate'  => '160',
    'AudioRate'     => '44100',
    'File'          => '1494491761',
);
*/
$file = ( !empty($_POST['File']) ) ? $tmp . $_POST['File'] . '.tmp' : false;
if($file) {
    $new = $tmp . $_POST['File'] . '-conv.mp4';
    $txt = $tmp . $_POST['File'] . '.txt';
    $command = 'ffmpeg -y -i ' . $file;
    $command .= ' -codec:v libx264 -profile:v baseline -level 3.0 -preset slow -b:v ' . $_POST['Bitrate'] . 'k -maxrate ' . $_POST['Bitrate'] . 'k -bufsize 1000k -r ' . $_POST['Rate'] . ' -vf scale=' . $_POST['Size'] . ' -pix_fmt yuv420p -threads 0 ';
    $command .= ' -codec:a libvo_aacenc -ar ' . $_POST['AudioRate'] . ' -b:a ' . $_POST['AudioBitrate'] . 'k ';
    $command .= $new . ' 1>' . $txt . ' 2>&1';

    //-codec:v libx264 -profile: high -preset slow -b:v 500k -maxrate 500k -bufsize 1000k -vf scale=-1:480 -threads 0 -codec:a libfdk_aac -b:a 128k

    $curl = curl_init();
    $url = $uploads . 'service/dealer/_videocurl.php';

    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 1,
        CURLOPT_TIMEOUT => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array('command' => $command),
    );
    
    $curl = curl_init($url);
    curl_setopt_array($curl, $options);
    $result = curl_exec($curl);
    curl_close($curl);

    $ret = array(
        'preview' => $uploads . 'tmp/' . $_POST['File'] . '-conv.mp4',
        'tmp' => $_POST['File'] . '-conv.mp4',
    );
    echo json_encode($ret);
}