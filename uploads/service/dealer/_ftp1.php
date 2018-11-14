<?php
$dom = '../';
require($dom . 'auth.php');
include('_connect.php');

function cmp($a, $b) {
    return -strcmp($a['datetime'], $b['datetime']);
}


if($login) {
    $dir = ( !empty($_GET['dir']) ) ? $_GET['dir'] : '';

    $dirfull = explode('static.marya.ru', $dir);
    if (!empty($dirfull[1])){
        $dir = 'downloads' . $dirfull[1];
    }

    $dira = explode('/', $dir);
    foreach($dira as $k => $dirar){
        if (empty($dirar)){
            unset($dira[$k]);
        }
        if ($dirar==='downloads'){
            unset($dira[$k]);
        }
    }	
    array_unshift($dira, 'downloads');

    if (!ftp_chdir($connect, $dir)){
        ftp_mkdir($connect, $dir);
        ftp_chdir($connect, $dir);
    }

    $list = ftp_rawlist($connect, '.');
    $list1 = array();
    $list2 = array();
    /*
    foreach ($list as $item) {
        if (!stristr($item, '.')){
            if (ftp_size($connect, $item)<0){
                $list1[] = $item;
            } else {
                $list2[] = $item;
            }
        } else {
            $list2[] = $item;
        }
    }
    */

    foreach ($list as $child) {
        $chunks = preg_split("/\s+/", $child);
        list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time'], $item['name']) = $chunks;
        //$item['datetime'] = date_create( $item['month'] . ' ' . $item['day'] . ' ' . $item['time']);
        //$item['datetime'] = date_format($item['datetime'], 'Y-m-d H:i:s');
        $item['datetime'] = date('Y-m-d H:i', strtotime( $item['month'] . '-' . $item['day'] . ' ' . $item['time'] . '+4 hours'));
        if ($chunks[0]{0} === 'd') {
            $list1[] = $item;
        } else {
            $list2[] = $item;
        }
    }

    if ( !empty($_POST['sort']) ) {
        usort($list2, "cmp");
    }

    $dirar = array();
    for ($i=count($dira)-1;$i>=0;$i--){
        $dirar[] = array(
            'name' => $dira[$i],
            'link' => implode('/', $dira)
        );
        unset($dira[$i]);
    }

    $dira = array_reverse($dirar);
    echo '<div class="rootdir">';
    foreach ($dira as $dirai){
        echo '<a href="?dir=' . $dirai['link'] . '">' . $dirai['name'] . '</a> / ';
    }
    echo '</div>';

    echo '<ul>';
    foreach ($list1 as $item) {
        echo '<li class="dir"><a href="?dir=' . $dirai['link'] . '/' . $item['name'] . '">' . $item['name'] . '</a></li>';
    }
    foreach ($list2 as $item) {
        echo '<li data-dir="/' . $dirai['link'] . '/" data-file="' . $item['name'] . '" class="file"><a>' . $item['name'] . ' <span>' . number_format($item['size']/1024/1024, 3, '.', ' ') . '&nbsp;&nbsp;' . $item['datetime'] . '</span></a></li>';
    }
    echo '</ul>';

} else {
    ftp_quit($connect);
    exit();
}
ftp_quit($connect);

