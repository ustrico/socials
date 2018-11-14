<?php
$dom = '../';
$menu = 'post';
$meta = array(
    'title'=>'Posting'
);
require($dom . 'auth.php');
include('_tokens.php');
include($dom . '_head.php');
?>
<body>
<?php
include($dom . '_menu.php');
?>
<div class="wrap">

    <form class="uk-form uk-form-stacked">
        <div class="uk-grid" id="grid">
            <div class="uk-width-1-2" id="col1">

            </div>
            <div class="uk-width-1-2" id="col2">

                <ul class="uk-tab TabUl" id="BrandSelect" data-uk-tab>
                    <?php foreach ($tokens as $BrandID => $Brand) { ?>
                        <li><a href="#<?= $BrandID ?>"><?= $BrandID ?></a></li>
                    <?php } ?>
                </ul>
                <div class="TabBodies">
                <?php foreach ($tokens as $BrandID => $Brand) { ?>
                    <div class="TabBody Brand" id="<?= $BrandID ?>">

                    </div>
                <?php } ?>
                </div>

            </div>
        </div>
    </form>

    <div id="postponed"></div>
    <div id="hidden" class="hidden">
        <div class="Calendar">
            <?= calendar() ?>
        </div>
    </div>
        
</div>

<link rel="stylesheet" type="text/css" media="all" href="style.css">

<script type="text/javascript" src="script.js"></script>

</body>
</html>