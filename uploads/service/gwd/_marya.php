<?php
$dom = '../';
require($dom . 'auth.php');

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
        '160x600',
        '240x400',
        '240x600',
        '300x250',
        '300x300',
        '300x500',
        '300x600',
        '320x50',
        '320x100',
        '320x480',
        '336x280',
        '480x320',
        '728x90',
        '970x250',
        '1000x120',
    ),
);

$preview = array(
    'google' => '',
    'yandex' => '',
);


$dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/gwd/';
$ok = $dir . 'ok/';
$gdir = $ok . 'google/';
$ydir = $ok . 'yandex/';

$list_dirs = scandir($dir);
array_shift($list_dirs);
array_shift($list_dirs);

echo 'Founded folders: ';
print_r($list_dirs);

if (!is_dir($ok)) mkdir($ok);
if (!is_dir($gdir)) mkdir($gdir);
if (!is_dir($ydir)) mkdir($ydir);

foreach ($list_dirs as $size) {
    if ($size != 'ok') {
        $di = $dir . $size . '/';
        $list_files = scandir($di);
        array_shift($list_files);
        array_shift($list_files);

        if (in_array('index.html', $list_files)) {
            $file = file_get_contents($di . 'index.html');
            $siz = explode('x', $size);
            $w = $siz[0];
            $h = $siz[1];
            echo '<br><br>' . $size . ' ========================================<br>';

            if (in_array($size, $sizes['google'])) {

                echo 'GOOGLE ---------------<br>';
                $google = $file;

                $preview['google'] .= '<h1>' . $size . '</h1><iframe src="' . $size . '/index.html" width="' . $w . '"
                height="' . $h . '"></iframe>';

                echo 'Write ' . $size . '.zip';
                $zip = new ZipArchive();				
				$zipopen = $zip->open($gdir . $size . '.zip', ZipArchive::OVERWRITE|ZipArchive::CREATE);
                if ($zipopen === TRUE) {
                    $zip->addFromString('index.html', $google);
                    foreach ($list_files as $file) {
                        if ($file != 'index.html') $zip->addFile($di . $file, $file);
                    }
                    $zip->close();
                    echo ' OK';
                } else {
                    echo ' Error: ' . $zipopen;
                }
                echo '<br>';
            }

            if (in_array($size, $sizes['yandex'])) {

                echo 'YANDEX ---------------<br>';
                $yandex = $file;

                preg_match('|<gwd-google-ad (.+?)</gwd-google-ad>|is', $yandex, $doubleclick);
                $yandex = str_ireplace($doubleclick[0], '<a id="click_area" href="#" target="_blank">' . $doubleclick[0] . '</a>', $yandex);
                echo 'Wrap gwd-google-ad with click_area<br>';

                $yandex = str_ireplace('</body>', '<script>document.getElementById("click_area").href = yandexHTML5BannerApi.getClickURLNum(1);</script></body>', $yandex);
                echo 'Add click_area js<br>';

                $preview['yandex'] .= '<h1>' . $size . '</h1><iframe src="' . $size . '/index.html"
                width="' . $w . '" height="' . $h . '"></iframe>';

                echo 'Write ' . $size . '.zip';
                $zip = new ZipArchive();
                $zipopen = $zip->open($ydir . $size . '.zip', ZipArchive::OVERWRITE|ZipArchive::CREATE);
                if ($zipopen === TRUE) {
                    $zip->addFromString('index.html', $yandex);
                    foreach ($list_files as $file) {
                        if ($file != 'index.html') $zip->addFile($di . $file, $file);
                    }
                    $zip->close();
                    echo ' OK';
                } else {
                    echo ' Error: ' . $zipopen;
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
file_put_contents($dir . 'prosmotr.html', $previewFile);
