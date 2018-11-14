<?php
$dom = '../';
require($dom . 'auth.php');
$tmp = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/';
/*
$_POST = array(
    'File'          => '1494491761',
);
*/
$file = ( !empty($_POST['File']) ) ? $tmp . $_POST['File'] . '.txt' : false;
$content = file_get_contents($file);

$ret = array('progress'=>100);

if($content) {
    preg_match("/Duration: (.*?), start:/", $content, $matches);
    $rawDuration = $matches[1];
    $ar = array_reverse(explode(":", $rawDuration));
    $duration = floatval($ar[0]);
    if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
    if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;
    preg_match_all("/time=(.*?) bitrate/", $content, $matches);
    $rawTime = array_pop($matches);
    if (is_array($rawTime)){$rawTime = array_pop($rawTime);}
    $ar = array_reverse(explode(":", $rawTime));
    $time = floatval($ar[0]);
    if (!empty($ar[1])) $time += intval($ar[1]) * 60;
    if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;
    $ret['progress'] = round(($time/$duration) * 100);

    preg_match("/Lsize= (.*?) time/", $content, $matches);
    if (!empty($matches[1])){
        $ret['progress'] = 100;
        $ret['lsize'] = number_format(trim($matches[1])/1024, 3, '.', ' ');
        $k = 9 - strlen($ret['lsize']);
        if ($k>0){
            for ($i=0;$i<$k;$i++){
                $ret['lsize'] = '&nbsp;' . $ret['lsize'];
            }
        }
        $ret['lsize'] = $duration . 'sec&nbsp;' . $ret['lsize'] . 'm';
    }
}

echo json_encode($ret);
