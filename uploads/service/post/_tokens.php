<?php
$dom = '../';
require($dom . 'auth.php');
$vkversion = '5.68';
$fbversion = 'v2.4';

$tokens = array(
    'marya' => array(
        'vk' => array(
            'owner_id' => -35250331,
            'access_token' => 'bda5ec80147fb2f29df06ce6bdeb6a6ea383be580e69a56ca46569b8ab32a4ba1ba9ed2237116f4625b00',
            'v' => $vkversion
        ),
        'fb' => array(
            'page_id' => 213509035375588,
            'access_token' => 'EAACEdEose0cBAP1iipAjoic0Y7vtUhn7oAvvOnHVwLzJdoYkzHTrK6B1S7uVW1995f5Oks3jMDnGj6EexT91Q68S74Ur6hWStLArZBT9iOlBZAXBMkwoAPCzsR2bXGLglDZA7dxDFNAWn1P8S2TUgj9vNdwlUBOIZBndtzbPiYpWSIBvH11ZB16g06WYxi3Ic98v7J11SwAZDZD',
            'app_id'  => 470381749997532,
            'app_secret' => '686e5eb2416bda669938b22a472dc493',
            'default_graph_version' => $fbversion,
        ),
    ),
    'ed' => array(
        'vk' => array(
            'owner_id' => -111836240,
            'access_token' => 'b7d6551a1036925c5183af69d12e9d64d17e175c64b15f321bd2f6f6ca3eb2bcb65aacf1c13daf4cadef2',
            'v' => $vkversion
        ),
    ),
    'mia' => array(
        'vk' => array(
            'owner_id' => -135308957,
            'access_token' => '54c68e99688cae0f9dced4d76f06430f095f5df4dc832e94ecbe037b211a2f1fd67de17d43c1f2d2401dd',
            'v' => $vkversion
        ),
    ),
);

function vkapi($brand=0, $metod='', $request_params=array()){
    global $tokens;
    return file_get_contents('https://api.vk.com/method/' . $metod . '?'. http_build_query(array_merge($request_params, $tokens[$brand]['vk'])));
}
/*
$date timestamp, time() default
$months integer
*/
function calendar($date = false, $months = 2){
    $ret = '';
    if (empty($date)) { $date = time(); }
    for ($monthi=0; $monthi<$months; $monthi++){
        if (!empty($monthi)) {
            $day = Date('j', $date);
            $daysinmonth = Date('t', $date);
            $date += ($daysinmonth - $day + 1) * 24 * 60 * 60;
        }
        $daysinmonth = Date('t', $date);
        $month = Date('M', $date);
        $monthN = Date('m', $date);
        $year = Date('Y', $date);
        $firstday = Date('N', strtotime('1 ' . $month . ' ' . $year));
        $ret .= '<div class="CalendarMonth ' . $year.$monthN . '"><h2>' . $month . '</h2><!--';
        for ($i=1; $i<$firstday; $i++){
            $ret .= '--><div class="day empty"></div><!--';
        }
        for ($i=1; $i<=$daysinmonth; $i++){
            $ret .= '--><div class="day day' . $i . '"><div class="daynum">' . $i . '</div><div class="daypart daypart0"></div><div class="daypart daypart1"></div><div class="daypart daypart2"></div></div><!--';
        }
        $ret .= '--></div>';
    }
    return $ret;
}


/*
$request_params = array(
    'client_id' => 6175126,
    'client_secret' => 'gadTDQuHtLyaomjDM6sI',
    'redirect_uri' => 'blank.html',
    'code' => 'be6963b98288c537b9',
    'test_mode' => 1,
    'v' => '5.68'
);
$get_params = http_build_query($request_params);
$result = json_decode(file_get_contents('https://oauth.vk.com/access_token?'. $get_params));
var_dump($result);

$request_params = array(
    'client_id' => 6175126,
    'display' => 'page',
    'redirect_uri' => 'blank.html',
    'scope' => '4096',
    'response_type' => 'code',
    'test_mode' => 1,
    'v' => '5.68'
);
$get_params = http_build_query($request_params);
$result = json_decode(file_get_contents('https://oauth.vk.com/authorize?'. $get_params));
var_dump($result);

$request_params = array(
    'owner_id' => -35250331,
    'post_id' => 8015,
    'message' => 'Тест',
    'attachments' => 'https://www.marya.ru/lp/artplay-media/',
    'publish_date' => strtotime('1 Jan 2018'),
    'test_mode' => 1,
    'access_token' => '5b4d66366ea4c4ee41fa9f7bcc2a829e3846efdfa78a1c81a1b056827191c56bb5ea773e59742577dd63c',
    'v' => '5.68'
);
$get_params = http_build_query($request_params);
$result = json_decode(file_get_contents('https://api.vk.com/method/wall.edit?'. $get_params));
var_dump($result);
*/
