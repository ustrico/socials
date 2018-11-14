<?php
function cmp($a, $b){
    return strcmp($a["name"], $b["name"]);
}
$Johns = array();
$handle = fopen('marya.csv', 'r');
while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
    $insert = array('', '', '', '');
    if (!empty($data[0])){
        $insert['name'] = $data[0];
    }
    if (!empty($data[1])){
        $insert['func'] = $data[1];
    }
    if (!empty($data[2])){
        $insert['phon'] = $data[2];
    }
    if (!empty($data[3])){
        $insert['mail'] = $data[3];
    }
    $Johns[] = $insert;
}
fclose($handle);

usort($Johns, 'cmp');

$JohnsTemplates = array(
    'marya' => '<p style="font-size:13px; color:#666666;"><strong>%s</strong>, %s;<br />
%s <a href="mailto:%s">%s</a>.</p>',
    'ed' => '<p style="font-size: 13px"><strong>%s</strong>, %s;<br />
%s <a href="mailto:%s" style="color:#cc0000;">%s</a>.</p>',
);

$JohnsBrands = array(
    'marya',
    'ed',
);

$brandCurrent = (!empty($_GET['brand'])) ? $_GET['brand'] : 'marya'; 

function man($man = array()){
    global $JohnsTemplates;
    global $brandCurrent;
    $man['name'] = (!empty($man['name'])) ? $man['name'] : '';
    $man['func'] = (!empty($man['func'])) ? $man['func'] : '';
    $man['phon'] = (!empty($man['phon'])) ? $man['phon'] : '';
    $man['mail'] = (!empty($man['mail'])) ? $man['mail'] : '';
    return sprintf($JohnsTemplates[$brandCurrent], $man['name'], $man['func'], $man['phon'], $man['mail'], $man['mail']);
}