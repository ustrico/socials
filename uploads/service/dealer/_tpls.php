<?php
$Tpls = array(
	'default' => array(
		'name' => '_Default',
		'settings' => array(
			'Name' => '«»',
			'Url' => '',
			'Dir' => '/downloads/$y/$m/',
			'Image' => '',
			'ImageBig' => '',
		),
		'items' => array(
			array(
				'Name' => '1',
			),
			array(
				'Name' => '2',
			),
		),
	),
	'akcii0' => array(
		'name' => 'ВСЕ АКЦИИ (создать ссылки)',
		'monthly' => true,
		'donor' => true,
		'after' => 'links',
		'settings' => array(
			'Name' => ' ( 2017)',
			'Publish' => 0,
			'Archive' => 0,
		),
		'items' => array(
			array(
				'Name' => 'Акции для дизайнеров',
			),
			array(
				'Name' => 'Все материалы по акциям',
			),
			array(
				'Name' => 'Акции для покупателей',
			),
			array(
				'Name' => 'Рекомендации по продвижению',
			),
			array(
				'Name' => 'Видео и Аудио',
			),
		),
	),
	'akciidesigner' => array(
		'name' => '-- Акции для дизайнеров',
		'monthly' => true,
		'donor' => 'pick',
		'settings' => array(
			'Name' => ' ( 2017)',
			'Url' => 'd',
			'Dir' => '/downloads/maket/$y/$m/',
			'Image' => 'd-s',
			'ImageBig' => 'd',
		),
		'items' => array(
			array(
				'Name' => 'Акции для дизайнеров',
				'Pick' => true,
				'ImageBigPdf' => 2,
			),
		),
		'fields' => array(
			'Retail' => 0,
			'Dealer' => 1,
			'DownloadSectionID' => 15,
		),
	),
	'akciiall' => array(
		'name' => '-- Все материалы',
		'monthly' => true,
		'donor' => true,
		'settings' => array(
			'Name' => ' ( 2017)',
			'Image' => 'a-s',
			'ImageBig' => 'a',
			'Body' => '',
			'Dir' => '/downloads/maket/$y/$m/',
		),
		'items' => array(
			array(
				'Name' => 'Все материалы по акциям',
				'Pick' => true,
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'DownloadSectionID' => 15,
		),
	),
	'videoall' => array(
		'name' => '-- Видео и Аудио',
		'monthly' => true,
		'donor' => true,
		'settings' => array(
			'Name' => ' ( 2017)',
			'ImageBig' => 'i',
			'Dir' => '/downloads/video/$y/$m/',
			'Body' => '',
		),
		'items' => array(
			array(
				'Name' => 'Видео и Аудио',
				'Pick' => true,
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'DownloadSectionID' => 4,
		),
	),
	'akcii' => array(
		'name' => 'ВСЕ АКЦИИ',
		'monthly' => true,
		'donor' => true,
		'after' => 'akcii',
		'settings' => array(
			'Name' => ' ( 2017)',
			'Url' => '',
			'Dir' => '/downloads/maket/$y/$m/',
			'Image' => '',
			'ImageBig' => '',
			'Publish' => 0,
			'Archive' => 0,
		),
		'items' => array(
			array(
				'Name' => 'Акции для покупателей',
				'Pick' => true,
				'Url' => 'a',
				'Image' => 'a-s',
				'ImageBig' => 'a',
				'ImageBigPdf' => 2,
				'after' => '<h3>Листовка  «Акции для покупателей»</h3>
<p><a href="{Block_ImageBig}" target="_blank" rel="colorbox"><img src="{Block_ImageBig}" width="400" style="border:1px solid #ccc;"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Рекомендации по продвижению',
				'Pick' => true,
				'Url' => 'p',
				'ImageBig' => 'p',
				'after' => '<h3>Презентация «Рекомендации по продвижению рекламной кампании»</h3>
<p><a href="/download/getitem/{Block_DownloadItemID}"><img src="{Block_ImageBig}" width="300" style="border:1px solid #ccc;"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Видео и Аудио',
				'Pick' => true,
				'after' => '<h3>Видео и Аудио ролики с акцией</h3>
<p><a href="/download/viewitem/{Block_DownloadItemID}" target="_blank"><img src="{Block_ImageBig}" width="300"></a></p>
<p><a href="/download/viewitem/{Block_DownloadItemID}" target="_blank">Открыть</a></p><br>

<h3>Музыка для студий</h3>
<a href="/download/category/222/" target="_blank" style="opacity:.4;"><img src="/downloads/audio/audio.svg"></a>
<p><a href="/download/category/222/" target="_blank">Открыть</a></p><br>'
			),
			array(
				'Name' => 'Баннер 6x3',
				'Url' => '6x3',
				'ImageBig' => '6x3',
				'after' => '<h3>Баннер 6x3</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="400"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Баннер 15x5',
				'Url' => '15x5',
				'ImageBig' => '15x5',
				'after' => '<h3>Баннер 15x5</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="600"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Роллап 600x1800',
				'Url' => '6x18',
				'ImageBig' => '6x18',
				'after' => '<div style="float:left;width:300px;"><h3>Роллап 600x1800</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" height="300"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p></div>'
			),
			array(
				'Name' => 'Роллап 900x2000',
				'Url' => '9x20',
				'ImageBig' => '9x20',
				'after' => '<h3>Роллап 900x2000</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" height="300"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Растяжка 10x1',
				'Url' => '10x1',
				'ImageBig' => '10x1',
				'after' => '<h3>Растяжка 10х1</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="800" style="border:1px solid #ccc;"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Наклейка на витрину',
				'Url' => 'vitrina',
				'ImageBig' => 'vitrina',
				'after' => '<h3>Наклейка на витрину</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="200"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Наклейка 80х80',
				'Url' => '80x80',
				'ImageBig' => '80x80',
				'after' => '<h3>Наклейка 80х80</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="200"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => '841х841',
				'Url' => '841',
				'ImageBig' => '841',
				'after' => '<h3>841х841</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="200"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'Буклет',
				'Url' => 'buk',
				'ImageBig' => 'buk',
				'after' => '<h3>Буклет</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="400" style="border:1px solid #ccc;"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
			array(
				'Name' => 'А4',
				'Url' => 'a4',
				'ImageBig' => 'a4',
				'after' => '<h3>Листовка А4</h3>
<p><a href="{Block_ImageBig}" target="_blank"  rel="colorbox"><img src="{Block_ImageBig}" width="250"></a></p>
<p><a href="/download/getitem/{Block_DownloadItemID}">Скачать</a></p><br>'
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'DownloadSectionID' => 15,
		),
	),
    'audio' => array(
        'name' => '-- Аудио',
		'monthly' => true,
		'after' => 'audio',
        'settings' => array(
            'Name' => ' «»',
            'Url' => '',
			'Dir' => '/downloads/audio/$y/$m/',
        ),
        'items' => array(
            array(
                'Name' => 'Хозяюшка 25 сек',
                'Url' => 'f-25',
            ),
            array(
                'Name' => 'Хозяюшка 20 сек',
                'Url' => 'f-20',
            ),
            array(
                'Name' => 'Безупречно 25 сек',
                'Url' => 'b-25',
            ),
            array(
                'Name' => 'Безупречно 20 сек',
                'Url' => 'b-20',
            ),
            array(
                'Name' => 'Молодые 25 сек',
                'Url' => 'y-25',
            ),
            array(
                'Name' => 'Молодые 20 сек',
                'Url' => 'y-20',
            ),
        ),
        'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'DownloadSectionID' => 4,
        ),
    ),
    'video' => array(
        'name' => '-- Видео',
		'monthly' => true,
		'after' => 'video',
		'settings' => array(
			'Name' => ' «»',
			'Url' => '',
			'Dir' => '/downloads/video/$y/$m/',
			'ImageBig' => '',
			'Video' => '',
		),
		'items' => array(
			array(
				'Name' => 'Видео 15 сек 16x9',
				'Url' => '15-16x9',
				'Video' => '15-16x9',
			),
			array(
				'Name' => 'Видео 15 сек 4x3',
				'Url' => '15-4x3',
			),
			array(
				'Name' => 'Видео 15 сек HD',
				'Url' => '15-hd',
				'Dir' => 'http://static.marya.ru',
			),
			array(
				'Name' => 'Видео 10 сек 16x9',
				'Url' => '10-16x9',
				'Video' => '10-16x9',
			),
			array(
				'Name' => 'Видео 10 сек 4x3',
				'Url' => '10-4x3',
			),
			array(
				'Name' => 'Видео 10 сек HD',
				'Url' => '10-hd',
				'Dir' => 'http://static.marya.ru',
			),
			array(
				'Name' => 'Видео 5 сек 16x9',
				'Url' => '5-16x9',
				'Video' => '5-16x9',
			),
			array(
				'Name' => 'Видео 5 сек 4x3',
				'Url' => '5-4x3',
			),
			array(
				'Name' => 'Видео 5 сек HD',
				'Url' => '5-hd',
				'Dir' => 'http://static.marya.ru',
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'DownloadSectionID' => 4,
		),
    ),
	'spr' => array(
		'name' => 'Справочники',
		'monthly' => true,
		'donor' => true,
		'settings' => array(
			'Name' => ' (версия VV) 2017.mm.dd',
			'Url' => 'vVV-2017-mm-dd',
			'Dir' => '/downloads/prices/$y/$m/',
			'DownloadSectionID' => '',
		),
		'items' => array(
			array(
				'Name' => 'Справочник №1',
				'Url' => 'spr-1-',
			),
			array(
				'Name' => 'Справочник №2',
				'Url' => 'spr-2-',
			),
			array(
				'Name' => 'Справочник №3',
				'Url' => 'spr-3-',
			),
			array(
				'Name' => 'Справочник №4',
				'Url' => 'spr-4-',
			),
			array(
				'Name' => 'Справочник №5',
				'Url' => 'spr-5-',
			),
			array(
				'Name' => 'Справочник №6',
				'Url' => 'spr-6-',
			),
			array(
				'Name' => 'Справочник Antro',
				'Url' => 'spr-antro-',
			),
			array(
				'Name' => 'Справочник Фотофасады',
				'Url' => 'spr-ff-',
			),
			array(
				'Name' => 'Справочник Декоративные секции',
				'Url' => 'spr-decor-',
			),
			array(
				'Name' => 'Справочник Стеклянные панели',
				'Url' => 'spr-spanel-',
			),
			array(
				'Name' => 'Справочник Столешницы Премиум',
				'Url' => 'spr-premium-',
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'Publish' => 1,
		),
	),
	'sprmvk' => array(
		'name' => '-- Справочники МВК',
		'monthly' => true,
		'donor' => true,
		'settings' => array(
			'Name' => ' (версия VV) 2017.mm.dd',
			'Url' => 'vVV-2017-mm-dd',
			'Dir' => '/downloads/prices/$y/$m/',
			'DownloadSectionID' => '',
		),
		'items' => array(
			array(
				'Name' => 'Справочник МВК №1',
				'Url' => 'spr-mvk-1-',
			),
			array(
				'Name' => 'Справочник МВК №2',
				'Url' => 'spr-mvk-2-',
			),
			array(
				'Name' => 'Справочник МВК №3',
				'Url' => 'spr-mvk-3-',
			),
			array(
				'Name' => 'Справочник МВК Фотофасады',
				'Url' => 'spr-mvk-ff-',
			),
			array(
				'Name' => 'Справочник МВК Декоративные секции',
				'Url' => 'spr-mvk-decor-',
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'Publish' => 1,
		),
	),
	'sprlight' => array(
		'name' => '-- Справочники Лайт',
		'monthly' => true,
		'donor' => true,
		'settings' => array(
			'Name' => ' (версия VV) 2017.mm.dd',
			'Url' => 'vVV-2017-mm-dd',
			'Dir' => '/downloads/prices/$y/$m/',
		),
		'items' => array(
			array(
				'Name' => 'Справочник кухни Мария Лайт №1',
				'Url' => 'light-1-',
			),
			array(
				'Name' => 'Справочник кухни Мария Лайт Components',
				'Url' => 'light-components-',
			),
			array(
				'Name' => 'База изображений стеновых панелей Мария Лайт',
				'Url' => 'light-baza-sten-panelei-',
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 0,
			'Publish' => 1,
			'DownloadSectionID' => 286,
		),
	),
	'videotrek' => array(
		'name' => 'Видеотрек',
		'monthly' => true,
		'donor' => true,
		'settings' => array(
			'Name' => ' ( 2017)',
			'Url' => '-2017-mm',
			'Dir' => '/downloads/video/$y/$m/',
		),
		'items' => array(
			array(
				'Name' => 'Кухни в телепроектах — часть 1',
				'Url' => 'videotrek-1',
			),
			array(
				'Name' => 'Кухни в телепроектах — часть 2',
				'Url' => 'videotrek-2',
			),
			array(
				'Name' => 'Кухни в телепроектах — часть 3',
				'Url' => 'videotrek-3',
			),
		),
		'fields' => array(
			'Retail' => 1,
			'Dealer' => 1,
			'DownloadSectionID' => 4,
		),
	),
);

$DownloadItemFields = array(
    'Name' => '',
	'Retail' => '',
	'Dealer' => '',
	'Image' => '',
	'ImageBig' => '',
	'Video' => '',
	'Url' => '',
	'Info' => '',
	'Body' => '',
	'DownloadSectionID' => '',
	'Priority' => '',
	'Publish' => '',
	'DateInserted' => '',
	'Date' => '',
	'Dealers' => '',
	'DealerIDs' => '',
	'Cities' => '',
	'Regions' => '',
	'Invert' => '',
	'Archive' => '',
	'Redirect' => '',
);

function _FieldView($key = '', $value = '', $id = '', $item = array() ){
	global $DownloadItemFields;
	$ifield = ( array_key_exists($key, $DownloadItemFields) ) ? 'ifield' : '';
	$url = '';
	$url1 = ' <a class="uk-form-file uk-button">Upload<input class="upload-select" type="file" id="' . $id . 'Upload"></a>
	<span class="file-uploaded"></span>';
	$field = '<input type="text" name="' . $key . '" class="' . $ifield . ' item' . $key . '" id="' . $id . 'field' . $key . '" value="' . $value . '" data-value="' . $value . '">';
	$row = '';
	$max = '';
	if ($key==='Url'){
		$row = 'url upload-drop';
		$url = $url1;
	} else if ($key==='Body'){
		$field = '<textarea name="' . $key . '" class="' . $ifield . ' item' . $key . '" id="' . $id . 'field' . $key . '" value="' . $value . '" data-value="' . $value . '">' . $value . '</textarea>';
	} else if ($key==='Image' || $key==='ImageBig'){
		$max = ($key==='Image') ? 200 : 1200;
		$max = ' data-max="' . $max . '"';
		$row = 'image upload-drop';
		$url = $url1;
		if ( !empty($item[$key.'Pdf']) ){
			$max .= ' data-pdf="' . $item[$key.'Pdf'] . '"';
		}
	} else if ($key==='Video'){
		$row = 'video';
		$url = $url1;
	}

	echo '<div class="uk-form-row ' . $row . '" ' . $max . '><label class="uk-form-label" for="' . $id . 'field' . $key . '">' . $key . '</label>
		<div class="uk-form-controls">' . $field . $url . '</div></div>';
}