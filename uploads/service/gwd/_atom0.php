<?php
$dom = '../';
require($dom . 'auth.php');

function imgToBase64($path){
    return 'data:image/' . pathinfo($path, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($path));
}

function imgToSvg($path){
    $svg = file_get_contents($path);
    preg_match('|<svg (.+?)>|is', $svg, $tag);
    preg_match('|id="(.+?)"|is', $tag[1], $id);
    preg_match('|' . $tag[0] . '(.+?)</svg>|is', $svg, $data);
    return array(
        'data' => $data[1],
        'tag' => str_ireplace($id[0], '', $tag[1]),
    );
}

$sizes = array(
    'google' => array(
        '120x600',
        '160x600',
        '200x200',
        '240x400',
        '250x250',
		'300x1050',
        '300x250',
        '300x600',
        '320x100',
        '320x50',
        '336x280',
        '468x60',
        '728x90',
        '970x90',
        '970x250',
    ),
    'yandex' => array(
        /*
        '160x600',
        '240x400',
        '300x250',
        '300x300',
        '728x90',
        */
    ),
);

$preview = array(
    'google' => '',
    'yandex' => '',
);


$dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/gwd/';
$ok = $dir . 'ok/';
$prosmotr = iconv("UTF-8","WINDOWS-1251", 'ПРОСМОТР');
$pre = $ok . $prosmotr . '/';

$list_dirs = scandir($dir);
array_shift($list_dirs);
array_shift($list_dirs);

echo 'Founded folders: ';
print_r($list_dirs);

if (!is_dir($ok)) {
    mkdir($ok);
}
if (!is_dir($pre)) {
    mkdir($pre);
}

foreach ($list_dirs as $list_dir) {
    if ($list_dir != 'ok') {
        $di = $dir . $list_dir . '/';
        $list_files = scandir($di);
        array_shift($list_files);
        array_shift($list_files);

        if (in_array('index.html', $list_files)) {
            $file = file_get_contents($di . 'index.html');
            $w = 0;
            $h = 0;

            preg_match('|data-gwd-width="(.+?)px"|is', $file, $w);
            $w = $w[1];

            preg_match('|data-gwd-height="(.+?)px"|is', $file, $h);
            $h = $h[1];

            $size = $w . 'x' . $h;
            echo '<br><br>' . $size . ' ========================================<br>';

            echo 'Files in folder ' . $list_dir . ': ';
            print_r($list_files);


            if (!stripos($file, 'name="ad.size"')) {
                $file = str_ireplace('<head>', '<head><meta name="ad.size" content="width=' . $w . ',height=' . $h . '">', $file);
                echo 'Add meta ad.size ' . $size . '<br>';
            }

            preg_match_all('|<img (.+?)>|is', $file, $img);
            $img = $img[1];
            if (count($img)) {
                $imgs = array();
                foreach ($img as $im) {
                    preg_match('|source="(.+?)"|is', $im, $src);
                    $ext = substr($src[1], -4);
                    if ($ext == '.svg') {
                        if (!in_array($src[1], $imgs)) {
                            $imgs[$src[1]] = imgToSvg($di . $src[1]);
                        }
                        echo 'Image to svg ' . $src[1] . ' ';
                        preg_match('|width="(.+?)"|is', $im, $iw);
                        preg_match('|height="(.+?)"|is', $im, $ih);
                        preg_match('|width="(.+?)"|is', $imgs[$src[1]]['tag'], $iwsvg);
                        preg_match('|height="(.+?)"|is', $imgs[$src[1]]['tag'], $ihsvg);
                        if (!empty($iw[0]) && !empty($iwsvg[0])){
                            $imgs[$src[1]]['tag'] = str_ireplace($iwsvg[0], $iw[0], $imgs[$src[1]]['tag']);
                            echo ' ' . $iw[0] . ' ';
                        }
                        if (!empty($ih[0]) && !empty($ihsvg[0])){
                            $imgs[$src[1]]['tag'] = str_ireplace($ihsvg[0], $ih[0], $imgs[$src[1]]['tag']);
                            echo ' ' . $ih[0] . ' ';
                        }
                        echo '<br>';
                        $im1 = str_ireplace($src[0], '' . $imgs[$src[1]]['tag'], $im);
                        $file = str_ireplace('<img ' . $im, '<svg ' . $im1, $file);
                        $file = str_ireplace('<svg ' . $im1 . '>', '<svg ' . $im1 . '>' . $imgs[$src[1]]['data'] . '</svg>', $file);

                    } else {
                        if (!in_array($src[1], $imgs)) {
                            $imgs[$src[1]] = imgToBase64($di . $src[1]);
                        }
                        $im1 = str_ireplace($src[0], 'src="' . $imgs[$src[1]] . '"', $im);
                        $file = str_ireplace($im, $im1, $file);
                        echo 'Image to base64 ' . $src[1] . '<br>';
                    }
                }

            }

            if (in_array($size, $sizes['google'])) {

                echo 'GOOGLE ---------------<br>';
                $google = $file;

                /*
                preg_match('|<gwd-doubleclick (.+?)</gwd-doubleclick>|is', $google, $doubleclick);
                $google = str_ireplace($doubleclick[0], '<a href="javascript:void(window.open(window.clickTag))">' . $doubleclick[0] . '</a>', $google);
                echo 'Wrap gwd-doubleclick with clickTag<br>';
                */

                file_put_contents($pre . $size . '-google.html', $google);
                $preview['google'] .= '<h1>' . $size . '</h1><iframe src="' . $prosmotr . '/' . $size . '-google.html" width="' . $w . '" height="' . $h . '"></iframe>';

                echo 'Write google-' . $size . '.zip';
                $zip = new ZipArchive();
                if ($zip->open($ok . 'google-' . $size . '.zip', ZipArchive::OVERWRITE) === TRUE) {
                    $zip->addFromString('index.html', $google);
                    $zip->close();
                    echo ' OK';
                } else {
                    echo ' ERROR';
                }
                echo '<br>';
            }

            if (in_array($size, $sizes['yandex'])) {

                echo 'YANDEX ---------------<br>';
                $yandex = $file;

                $yandex = str_ireplace('<head>', '<head><script type="text/javascript" src="https://awaps.yandex.net/data/lib/adsdk.js"></script>
<script>function getUrlParam(name) {name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),results = regex.exec(location.search);
return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));}</script>', $yandex);
                echo 'Add adsdk.js<br>';
                echo 'Add function getUrlParam<br>';


                $enabler = explode('Enabler.js', $yandex);
                $enabler1 = array_pop($enabler);
                $enabler = array_pop($enabler);
                $enabler = explode('src="', $enabler);
                $enabler = array_pop($enabler);
                $enabler_link = $enabler . 'Enabler.js';
                $enabler = explode($enabler_link, $yandex);
                $enabler1 = array_shift($enabler);
                $enabler1 = explode('<script', $enabler1);
                $enabler1 = '<script' . array_pop($enabler1);
                $enabler2 = implode($enabler_link, $enabler);
                $enabler2 = explode('</script>', $enabler2);
                $enabler2 = array_shift($enabler2) . '</script>';
                $enabler = $enabler1 . $enabler_link . $enabler2;
                $yandex = str_ireplace($enabler, '<script name="Enabler.js">' . file_get_contents($enabler_link) . '</script>', $yandex);
                echo 'Replace Enabler.js<br>';

                preg_match('|<gwd-doubleclick (.+?)</gwd-doubleclick>|is', $yandex, $doubleclick);
                $yandex = str_ireplace($doubleclick[0], '<a id="click1_area" href="$SHOP_URL" target="_blank">' . $doubleclick[0] . '</a>', $yandex);
                echo 'Wrap gwd-doubleclick with click1_area<br>';

                $yandex = str_ireplace('</body>', '<script>document.getElementById("click1_area").href = getUrlParam("link1");</script></body>', $yandex);
                echo 'Add click1_area js<br>';


                file_put_contents($pre . $size . '-yandex.html', $yandex);
                $preview['yandex'] .= '<h1>' . $size . '</h1><iframe src="' . $prosmotr . '/' . $size . '-yandex.html" width="' . $w . '" height="' . $h . '"></iframe>';

                echo 'Write yandex-' . $size . '.zip';
                $zip = new ZipArchive();
                if ($zip->open($ok . 'yandex-' . $size . '.zip', ZipArchive::OVERWRITE) === TRUE) {
                    $zip->addFromString('index.html', $yandex);
                    $zip->close();
                    echo ' OK';
                } else {
                    echo ' ERROR';
                }
                echo '<br>';

            }


        }
    }

}

$previewFile = '<style>iframe{border: 0 none;}h2{color: #0c0;}</style>';
if (!empty($preview['google'])) {
    $previewFile .= '<h2>Google</h2>' . $preview['google'] . '<br><br>';
}
if (!empty($preview['yandex'])) {
    $previewFile .= '<h2>Yandex</h2>' . $preview['yandex'] . '<br><br>';
}
file_put_contents($ok . $prosmotr . '.html', $previewFile);
