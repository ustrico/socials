<?php
$dom = '../';
require($dom . 'auth.php');
require('_tokens.php');

$ret = '<tr><th>id</th><th>first_name</th><th>last_name</th></tr>';

$result = json_decode(vkapi('marya', 'likes.getList', array(
    'type' => 'post',
    'item_id' => 7787,
    'filter' => 'likes,copies',
    'count' => 1000,
    'extended' => 1,
)));

foreach ($result->response->items as $item){
    $ret .= '<tr>';
    $ret .= '<td>' . $item->id . '</td>';
    $ret .= '<td>' . $item->first_name . '</td>';
    $ret .= '<td>' . $item->last_name . '</td>';
    $ret .= '</tr>';
}

echo '<table border="1">' . $ret . '</table>';
