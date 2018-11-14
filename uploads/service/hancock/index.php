<?php
$dom = '../';
$menu = 'hancock';
$meta = array(
    'title'=>'Hancock'
);
require($dom . 'auth.php');
include($dom . '_head.php');
include('johns.php');
?>
<body>
<?php
include($dom . '_menu.php');
?>
<div class="wrap">
    <form class="uk-form uk-form-stacked">
        <div class="uk-grid" id="grid">
            <div class="uk-width-1-2" id="col1">
                <div id="JohnsPrev" class="uk-nestable" data-uk-nestable>
                </div>
                <textarea id="JohnsCode"></textarea>
                <div id="ButtonCopyDiv"><a class="uk-button uk-button-primary" id="ButtonCopy">Copy</a></div>
            </div>
            <div class="uk-width-1-2" id="col2">
                <select id="BrandSelect">
                    <?php
                    foreach ($JohnsBrands as $Brand) {
                        $selected = ($Brand==$brandCurrent) ? 'selected' : '';
                        ?>
                        <option value="<?= $Brand ?>" <?= $selected ?> ><?= $Brand ?></option>
                    <?php } ?>
                </select>
                
                <div id="JohnsMen">

                <?php
                $i = 0;
                foreach ($Johns as $Man) {
                    $i++;
                    ?>
                    <div class="JohnsMan" id="man<?=$i?>" data-name="<?=$Man['name']?>" data-func="<?=$Man['func']?>" data-phon="<?=$Man['phon']?>" data-mail="<?=$Man['mail']?>">
                        <?=$Man['name']?>
                        <div class="hidden JohnsItem">
<?=man($Man);?>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </form>
</div>
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>uk/css/components/nestable.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>uk/css/components/notify.min.css">
<script type="text/javascript" src="<?=$dom?>uk/js/components/nestable.min.js"></script>
<script type="text/javascript" src="<?=$dom?>uk/js/components/notify.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>design/hancock.css"/>
<script type="text/javascript" src="<?=$dom?>js/hancock.js"></script>
</body>
</html>