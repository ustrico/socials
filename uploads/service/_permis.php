<?php
    $menu = (!empty($menu)) ? $menu : 'socials';
    $menua = array(
        'socials' => array(
            'name' => 'Socials',
            'link' => $dom,
        ),
        'hancock' => array(
            'name' => 'Hancock',
            'link' => $dom.'hancock',
        ),
        'dealer' => array(
            'name' => 'Dealer',
            'link' => $dom.'dealer',
            'role' => 'dDF8L6',
        ),
        'gwd' => array(
            'name' => 'Gwd',
            'link' => $dom.'gwd',
        ),
        'post' => array(
            'name' => 'Posting',
            'link' => $dom.'post',
        ),
    );
    $menua[$menu]['active'] = true;
    foreach ($menua as $k => $me){
        $permis = true;
        if ( !empty($me['role']) ) {
            if ( empty($_SESSION['user']['role']) ) {
                $permis = false;
            } else if ( !in_array($me['role'], $_SESSION['user']['role']) ){
                $permis = false;
            }
        }
        if (!$permis) {
            unset($menua[$k]);
        }
    }