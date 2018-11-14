<?php
$dom = '../';
$menu = 'gwd';
$meta = array(
    'title'=>'GWD'
);
require($dom . 'auth.php');
include($dom . '_head.php');
?>
<body>
<?php
include($dom . '_menu.php');
?>
<div class="wrap" id="Segmento">
    <form class="uk-form uk-form-stacked" id="SegmentoForm">
        <div class="uk-grid" id="grid">
            <div class="uk-width-1-2" id="col1">
                <input type="hidden" id="action" name="action" value="_segmento">

                <button class="uk-button uk-button-primary" type="submit" value="_segmento">Segmento</button>
                <button class="uk-button uk-button-primary" type="submit" value="_atom">Atom</button>
                <button class="uk-button uk-button-primary" type="submit" value="_realweb">Realweb</button>
                <button class="uk-button uk-button-primary" type="submit" value="_marya">Marya</button>
                <div id="ret"></div>
                
            </div>
            <div class="uk-width-1-2" id="col2">
                

            </div>
        </div>
    </form>
</div>
<link rel="stylesheet" type="text/css" media="all" href="style.css">
<script type="text/javascript" src="script.js"></script>
</body>
</html>