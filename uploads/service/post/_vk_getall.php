<?php
$dom = '../';
require($dom . 'auth.php');
require('_tokens.php');

$ret = '<tr><th>id</th><th>date</th><th>day</th><th>hour</th><th>text</th><th>comments</th><th>likes</th><th>reposts</th><th>views</th><th>likes/views</th><th>reposts/views</th></tr>';

for ($i=0; $i<40; $i++){
    $result = json_decode(vkapi('wall.get', array('count'=>100, 'offset'=>100*$i)));
    foreach ($result->response->items as $item){
        $ret .= '<tr>';
        $ret .= '<td><a href="https://vk.com/wall-35250331_' . $item->id . '">' . $item->id . '</a></td>';
        $ret .= '<td>' . date("Y-m-d H:i", $item->date) . '</td>';
        $ret .= '<td>' . date("N", $item->date) . '</td>';
        $ret .= '<td>' . date("H", $item->date) . '</td>';
        $ret .= '<td>' . substr($item->text, 0, 5000) . '</td>';
        $ret .= '<td>' . $item->comments->count . '</td>';
        $ret .= '<td>' . $item->likes->count . '</td>';
        $ret .= '<td>' . $item->reposts->count . '</td>';
        $ret .= '<td>' . $item->views->count . '</td>';
        $ret .= '<td>' . ( (!empty($item->views->count)) ? number_format($item->likes->count/$item->views->count*100, 2) : 0) . '%</td>';
        $ret .= '<td>' . ( (!empty($item->views->count)) ? number_format($item->reposts->count/$item->views->count*100, 2) : 0) . '%</td>';
        $ret .= '</tr>';
    }
}

echo '<table border="1">' . $ret . '</table>';
