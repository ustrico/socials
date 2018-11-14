<?php
$dom = '../';
require($dom . 'auth.php');
require('_tokens.php');

$brand = ( !empty($_POST['brand']) ) ? $_POST['brand'] : false;

echo vkapi($brand, 'wall.get', array('filter' => 'postponed'));
