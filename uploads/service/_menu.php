<nav class="uk-navbar" id="menu"><div class="uk-navbar-flip">
    <ul class="uk-navbar-nav">
        <?php foreach ($menua as $me){
            $class = (!empty($me['active'])) ? 'class="uk-active"' : '';
            echo '<li ' . $class . '><a href="' . $me['link'] . '">' . $me['name'] . '</a></li>';
        } ?>
    </ul>
</div></nav>