<?php
require('auth.php');
include('socialphotos.php');
include('_head.php');
?>
<body>
<?php
include('_menu.php'); 
?>

<div id="progressbar" class="uk-progress uk-progress-striped uk-hidden">
    <div class="uk-progress-bar" style="width: 0%;">0%</div>
</div>

<div class="wrap">
    <form class="uk-form uk-form-stacked">

        <div class="uk-grid" id="grid">
            <div class="uk-width-1-2" id="col1">
                <div id="upload-drop" class="uk-placeholder uk-text-center">
                    <i class="uk-icon-cloud-upload uk-icon-medium uk-text-muted uk-margin-small-right"></i>
                    Attach files by dropping them here or <a class="uk-form-file">selecting one<input id="upload-select"
                                                                                                      type="file"></a>.
                </div>

                <div id="uploaded"></div>

                <div id="Edit">
                    <i class="undo"         title="Undo"></i>
                    <i class="mirror"       title="Mirror"></i>
                    <i class="rotate"       title="Rotate"></i>
                    <i class="brightplus"   title="Brightness↑"></i>
                    <i class="brightminus"  title="Brightness↓"></i>
                    <i class="contrplus"    title="Saturation↑"></i>
                    <i class="contrminus"   title="Saturation↓"></i>
                </div>

                <div id="Resize">
                    <img id="Area">
                </div>

            </div>
            <div class="uk-width-1-2" id="col2">

                <select id="BrandSelect" data-current="<?= key($Socials) ?>">
                    <?php
                    $i = 0;
                    foreach ($Socials as $BrandID => $Brand) {
                        $class = (empty($i)) ? 'uk-active' : '';
                        $i++;
                        ?>
                        <option value="<?= $BrandID ?>" class="<?= $class ?>"><?= $BrandID ?></option>
                    <?php } ?>
                </select>

                <div id="TitleDiv">
                    <label class="uk-form-label">Title</label>
                    <textarea id="Title"></textarea>
                </div>

                <?php
                $i = 0;
                foreach ($Socials as $BrandID => $Brand) {
                    $class = (empty($i)) ? 'first' : '';
                    $i++;
                    ?>

                    <div class="Brand <?= $class ?>" id="<?= $BrandID ?>">

                        <select class="CategorySelect">
                            <option disabled selected>Рубрика</option>
                            <?php foreach ($Brand as $CategoryID => $Category) { ?>
                                <option value="<?= $BrandID . $CategoryID ?>"><?= $Category['name'] ?></option>
                            <?php } ?>
                        </select>

                        <?php foreach ($Brand as $CategoryID => $Category) {
                            $title = (!empty($Category['title'])) ? 'data-title="' . $Category['title'] . '"' : '';
                            ?>
                            <div class="Category" id="<?= $BrandID . $CategoryID ?>" <?= $title ?> >
                                <?php if (!empty($Category['hint'])) {
                                    echo '<div class="Hint">' . $Category['hint'] . '</div>';
                                } ?>
                                <ul class="uk-tab TileUl" data-uk-tab>
                                    <?php foreach ($Category['tiles'] as $TileID => $Tile) { ?>
                                        <li><a href="#<?= $BrandID . $CategoryID . $TileID ?>"><?= $TileID ?></a></li>
                                    <?php } ?>
                                </ul>
                                <?php foreach ($Category['tiles'] as $TileID => $Tile) {
                                    $text = '';
                                    if (!empty($Category['title'])) {
                                        if (!empty($Tile['title']['text'])) foreach ($Tile['title']['text'] as $attr => $value) {
                                            $text .= 'data-' . $attr . '="' . $value . '" ';
                                        }
                                    }
                                    if (!empty($Tile['digit'])) {
                                        if (!empty($Tile['digit']['text'])) foreach ($Tile['digit']['text'] as $attr => $value) {
                                            $text .= 'data-digit-' . $attr . '="' . $value . '" ';
                                        }
                                    }
                                ?>
                                    <div class="TileI">
                                    <?php $digit = (!empty($Tile['digit'])) ? 'DigitTile' : '';  ?>
                                    <div class="Tile <?=$digit?>" id="<?= $BrandID . $CategoryID . $TileID ?>"
                                         data-w="<?= $Tile['w'] ?>" data-h="<?= $Tile['h'] ?>"
                                         data-src="<?= $Tile['src'] ?>"
                                         style="height:<?= (500 / $Tile['w'] * $Tile['h']) ?>px;" <?= $text ?>>
                                        <?php if (!empty($Tile['collage'])) {
                                            foreach ($Tile['collage'] as $CollageID => $Collage) { ?>
                                                <div class="Collage"
                                                     id="<?= $BrandID . $CategoryID . $TileID . $CollageID ?>"
                                                     data-id="<?= $BrandID . $CategoryID . $TileID . $CollageID ?>"
                                                     data-w="<?= $Collage['w'] ?>" data-h="<?= $Collage['h'] ?>"
                                                     data-l="<?= $Collage['x'] ?>" data-t="<?= $Collage['y'] ?>"
                                                ><img class="Photo"></div>
                                            <?php }
                                        } else { ?>
                                            <img class="Photo" src="<?= $Tile['src'] ?>">
                                        <?php } ?>
                                        <img class="Logo" src="<?= $Tile['src'] ?>">
                                        <?php if (!empty($Tile['digit'])) {
                                            $css = '';
                                            $k = 500 / $Tile['w'];
                                            $Tile['digit']['css'] = array();
                                            $Tile['digit']['css']['left'] = ($Tile['digit']['text']['left'] * $k) . 'px';
                                            $Tile['digit']['css']['top'] = (($Tile['digit']['text']['top'] - $Tile['digit']['text']['size']) * $k) . 'px';
                                            $Tile['digit']['css']['font-size'] = ($Tile['digit']['text']['size'] * $k) . 'px';
                                            if (!empty($Tile['digit']['font'])) {
                                                $Tile['digit']['css']['font-family'] = $Tile['digit']['font'];
                                            }
                                            if ($Category['title'] === 'center') {
                                                $Tile['digit']['css']['width'] = '100%';
                                                $Tile['digit']['css']['left'] = 0;
                                                $Tile['digit']['css']['text-align'] = 'center';
                                            }
                                            if (!empty($Tile['digit']['css'])) foreach ($Tile['digit']['css'] as $attr => $value) {
                                                $css .= $attr . ':' . $value . ';';
                                            }
                                            ?>
                                            <div class="Digit" style="<?= $css ?>">13</div>
                                        <?php } ?>
                                        <?php if (!empty($Category['title'])) {
                                            $css = '';
                                            if (empty($Tile['title']['css']) && !empty($Tile['title']['text'])) {
                                                $k = 500 / $Tile['w'];
                                                $Tile['title']['css'] = array();
                                                $Tile['title']['css']['left'] = ($Tile['title']['text']['left'] * $k) . 'px';
                                                $Tile['title']['css']['top'] = (($Tile['title']['text']['top'] - $Tile['title']['text']['size']) * $k) . 'px';
                                                $Tile['title']['css']['font-size'] = ($Tile['title']['text']['size'] * $k) . 'px';
                                            }
                                            if (!empty($Category['title-font'])) {
                                                $Tile['title']['css']['font-family'] = $Category['title-font'];
                                            }
                                            if ($Category['title'] === 'center') {
                                                $Tile['title']['css']['width'] = '100%';
                                                $Tile['title']['css']['left'] = 0;
                                                $Tile['title']['css']['text-align'] = 'center';
                                            }
                                            if (!empty($Tile['title']['text']['width'])) {
                                                $Tile['title']['css']['width'] = ($Tile['title']['text']['width'] * $k) . 'px';
                                                $Tile['title']['css']['left'] = ($Tile['title']['text']['left'] * $k - $Tile['title']['css']['width']/2) . 'px';
                                            }
                                            if (!empty($Tile['title']['text']['color'])) {
                                                $Tile['title']['css']['color'] = $Tile['title']['text']['color'];
                                            }
                                            if (!empty($Tile['title']['css'])) foreach ($Tile['title']['css'] as $attr => $value) {
                                                $css .= $attr . ':' . $value . ';';
                                            }
                                            ?>
                                            <div class="Text" style="<?= $css ?>" data-k="<?=$k?>">Заголовок</div>
                                        <?php } ?>
                                    </div>
                                        <?php if (!empty($Tile['collage'])) { ?>
                                            <div class="uk-button-group CollageUl">
                                                <?php foreach ($Tile['collage'] as $CollageID => $Collage) { ?>
                                                    <a class="uk-button" href="#<?= $BrandID . $CategoryID . $TileID . $CollageID ?>"><?= $CollageID ?></a>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($Tile['title']['text']['title-resize'])) {
                                            $size = $Tile['title']['text']['size'];
                                            $min = $Tile['title']['text']['size']/2;
                                            $max = $size + $min;
                                        ?>
                                            <div class="TitleResizeI">
                                                <input class="TitleResize" type="range" name="title-resize" min="<?=$min?>" max="<?=$max?>" step="1" value="<?=$size?>">
                                                <div class="TitleResizeVal"><?=$size?></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    </div>

                <?php } ?>
                <div id="ButtonCropDiv"><a class="uk-button uk-button-primary" id="ButtonCrop">Save it!</a></div>
            </div>
        </div>
    </form>
</div>

<div id="Preview">
    <i class="uk-icon-cog uk-icon-spin" id="PreviewLoading"></i>
</div>

<link rel="stylesheet" type="text/css" media="all" href="uk/css/components/form-file.min.css">
<link rel="stylesheet" type="text/css" media="all" href="uk/css/components/placeholder.min.css">
<link rel="stylesheet" type="text/css" media="all" href="uk/css/components/progress.min.css">
<link rel="stylesheet" type="text/css" media="all" href="js/imgareaselect/css/imgareaselect-default.dt.css">
<link rel="stylesheet" type="text/css" media="all" href="design/socials.css?v=<?=$version?>"/>
<script type="text/javascript" src="uk/js/components/upload.min.js"></script>
<script type="text/javascript" src="js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
<script type="text/javascript" src="js/socials.js?v=<?=$version?>"></script>

</body>
</html>