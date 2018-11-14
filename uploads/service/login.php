<?php
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: .');
    exit;
} else if (!empty($_POST['Username'])) {
    $name = preg_replace('/[^A-Za-z0-9_.]/', '', $_POST['Username']);
    $pass = preg_replace('/[^A-Za-z0-9_.]/', '', $_POST['Password']);
    include('user.php');
    if (in_array($name, array_keys($User))) {
        if ($User[$name]['pass'] == md5($pass)) {
            $_SESSION['user'] = array(
                'name' => $name,
                'role' => $User[$name]['role'], 
            );
            header('Location: .');
            exit;
        }
    }
}
$dom = (!empty($dom)) ? $dom : '';
include($dom . '_head.php');
?>
<body class="uk-height-1-1">

<div class="uk-vertical-align uk-text-center uk-height-1-1">
    <div class="uk-vertical-align-middle" style="width: 250px;">
        <form class="uk-panel uk-panel-box uk-form" method="post">
            <div class="uk-form-row">
                <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Username" name="Username">
            </div>
            <div class="uk-form-row">
                <input class="uk-width-1-1 uk-form-large" type="password" placeholder="Password" name="Password">
            </div>
            <div class="uk-form-row">
                <button class="uk-width-1-1 uk-button uk-button-primary uk-button-large">Login</button>
            </div>

        </form>
    </div>
</div>

</body>

</html>
