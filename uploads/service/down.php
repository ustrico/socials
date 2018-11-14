<?php
require('auth.php');
$dir = substr($_SERVER['SCRIPT_FILENAME'], 0, -8);
if (file_exists($dir . $_GET['file'])) {
    if (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($dir . $_GET['file']));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($dir . $_GET['file']));
    readfile($dir . $_GET['file']);
    exit;
}