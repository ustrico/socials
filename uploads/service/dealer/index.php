<?php
$dom = '../';
$menu = 'dealer';
$meta = array(
    'title'=>'Dealer'
);
require($dom . 'auth.php');
include($dom . '_head.php');
?>
<body>
<?php
include($dom . '_menu.php');
include('_tpls.php');
$aftertext = '';
?>

<div id="progressbar" class="uk-progress uk-progress-striped uk-hidden uk-active">
    <div class="uk-progress-bar" style="width: 0%;">0%</div>
</div>

<div class="wrap">
    <div class="uk-grid" id="grid">
        <div class="uk-width-1-2" id="col1">
            <form class="uk-form">
            <select id="TplSelect">
                <?php
                foreach ($Tpls as $TplID => $Tpl) { ?>
                    <option value="<?= $TplID ?>"><?= $Tpl['name'] ?></option>
                <?php } ?>
            </select>
            </form>

            <?php
            foreach ($Tpls as $TplID => $Tpl) {
                $DownloadItemFieldsTmp = $DownloadItemFields;
                $after = (!empty($Tpl['after'])) ? 'data-after="' . $Tpl['after'] . '"' : '';
                ?>
                <div class="Tpl <?=( (!empty($Tpl['donor'])) ? 'donor' : '' )?>" id="<?= $TplID ?>" <?=$after?>>
                    
                    <form class="uk-form uk-form-horizontal">

                    <?php if ( !empty($Tpl['items']) && count($Tpl['items'])>1 ) { ?>
                        <div multiple class="itemsSelect">
                            <?php foreach ($Tpl['items'] as $ItemID => $Item) { ?>
                                <div class="uk-form-row"><label class="active"><input type="checkbox" value="<?= $TplID ?>Item<?= $ItemID ?>" checked> <?= $Item['Name'] ?></label></div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <?php
                    if (!empty($Tpl['monthly'])){
                        $datey = date('Y');
                        $datem = date('n');
                        $dated = date('j');
                        if ($dated < 10) {
                            $dated = 1;
                        } else if ($dated > 20) {
                            $dated = 1;
                            $datem++;
                            if ($datem>12) {
                                $datem = 1;
                                $datey++;
                            }
                        }
                        $date = date_create($datey . '-' . $datem . '-' . $dated);
                        $date = date_format($date, 'Y-m-d');    
                    } else {
                        $date = date('Y-m-d');
                    }

                    ?>

                    <div class="Tpl-settings">

                    <div class="uk-form-row">
                        <label class="uk-form-label" for="<?= $TplID ?>date"><span>Date</span></label>
                        <div class="uk-form-controls">
                            <input type="text" name="date" class="globalDate" id="<?= $TplID ?>date" value="<?=$date?>">
                        </div>
                    </div>

                    <?php
                    if ( !empty($Tpl['settings']) ) {
                        foreach ($Tpl['settings'] as $SetID => $Set) { ?>
                            <div class="uk-form-row">
                                <label class="uk-form-label" for="<?= $TplID ?>settings<?=$SetID?>"><span><?=$SetID?></span></label>
                                <div class="uk-form-controls">
                                    <input type="text" name="<?=$SetID?>" class="global<?=$SetID?>" id="<?=$TplID?>settings<?=$SetID?>" value="<?=$Set?>" data-value="<?=$Set?>">
                                </div>
                            </div>
                        <?php }
                    }
                    ?>
                    </div>

                    <?php
                    if ( !empty($Tpl['items']) ) { ?>
                        <div class="Items uk-nestable" data-uk-nestable="{handleClass:'uk-nestable-handle'}">
                        <?php foreach ($Tpl['items'] as $ItemID => $Item) {
                            $pick = ( !empty($Item['Pick']) ) ? 'Pick' : '';
                            ?>
                            <div class="Item uk-nestable-item <?=$pick?>" id="<?= $TplID ?>Item<?= $ItemID ?>">
                                <div class="ItemHandle uk-nestable-handle">
                                    <div class="ItemID"></div>
                                    <div class="delete"></div>
                                </div>
                                <?php

                                if ( !empty($Tpl['settings']) ) { ?>
                                    <div class="FieldsOne">

                                        <div class="uk-form-row donor">
                                            <label class="uk-form-label" for="<?= $TplID ?>Item<?= $ItemID ?>Donor">Donor</label>
                                            <div class="uk-form-controls">
                                                <input type="text" name="Donor" class="itemDonor" id="<?=$TplID?>Item<?=$ItemID?>Donor" value="">

                                                &nbsp;&nbsp;&nbsp;Archive:
                                                <label class="switch">
                                                    <input type="checkbox" name="DonorArchive" class="itemDonorArchive">
                                                    <div class="slider"></div>
                                                </label>

                                                &nbsp;&nbsp;&nbsp;<a class="uk-button itemDonorPick">Pick</a>

                                                <div class="itemDonorList" id="<?= $TplID ?>Item<?= $ItemID ?>DonorList">
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    foreach ($Tpl['settings'] as $SetID => $Set) {
                                        $Set = (!empty($Item[$SetID])) ? $Item[$SetID] : '';
                                        _FieldView($SetID, $Set, $TplID . 'Item' . $ItemID, $Item);
                                        unset($DownloadItemFieldsTmp[$SetID]);
                                    } ?>
                                    </div>
                                <?php } ?>

                                <div class="ItemButtons">
                                    <div class="uk-form-controls">
                                        <button class="uk-button uk-button-primary insert-update" type="button">Insert</button>
                                    </div>
                                </div>

                                <div class="FieldsTwo">
                                    <div class="FieldsTwoHandle cutHandle">...</div>
                                    <div class="FieldsTwoBody cutBody">
                                <?php
                                if ( !empty($Tpl['fields']) ) {
                                    foreach ($Tpl['fields'] as $FieldID => $Field) {
                                        _FieldView($FieldID, $Field, $TplID . 'Item' . $ItemID);
                                        unset($DownloadItemFieldsTmp[$FieldID]);
                                    }
                                }

                                foreach ($DownloadItemFieldsTmp as $FieldID => $Field) {
                                    _FieldView($FieldID, $Field, $TplID . 'Item' . $ItemID);
                                }

                                if ( !empty($Item['after']) ){
                                    $aftertext .= '<div id="after' . $TplID . 'Item' . $ItemID . '">' . $Item['after'] . '</div>';
                                }

                                ?>

                                    </div>
                                </div>

                            </div>
                        <?php } ?>
                        </div>
                    <?php } ?>

                    <a class="uk-button uk-button-primary doall">DO ALL</a>
                            
                    </form>
                    
                    <div class="afterfiles"></div>
                    <div class="aftertext"></div>
                    
                </div>
            <?php } ?>
        </div>
        <div class="uk-width-1-2" id="col2">

            <div class="uk-accordion" data-uk-accordion>

                <h3 class="uk-accordion-title" id="acc-ftp">files
                    <div class="status">
                        <span class="uk-icon-sort-numeric-desc" data-class="date" title="Sort by Date"></span>
                    </div>
                </h3>
                <div class="uk-accordion-content" id="acc-ftp-body"></div>

                <h3 class="uk-accordion-title" id="acc-db-title">db
                    <div class="status Publish Archive">
                        <span class="uk-icon-file-archive-o" data-class="Archive"></span>
                        <span class="uk-icon-eye" data-class="Publish"></span>
                    </div>
                </h3>
                <div class="uk-accordion-content" id="acc-db-body"></div>


                <h3 class="uk-accordion-title" id="acc-video-title">video</h3>
                <div class="uk-accordion-content" id="acc-video-body">

                    <form class="uk-form done">
                        <div class="uk-form-row videoupload">
                            <a class="uk-form-file uk-button">Upload<input class="upload-select" type="file"></a>
                            <span class="file-uploaded"></span>
                        </div>
                        <div class="videoconvert">
                            <div class="uk-form-row">
                                <label class="uk-form-label">Size</label>
                                <div class="uk-form-controls">
                                    <input type="text" name="Size" value="640:360">
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Bitrate(k)</label>
                                <div class="uk-form-controls">
                                    <input type="text" name="Bitrate" value="1500">
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Rate</label>
                                <div class="uk-form-controls">
                                    <input type="text" name="Rate" value="24">
                                </div>
                            </div>
                            <br>
                            <div class="uk-form-row">
                                <label class="uk-form-label">AudioBitrate(k)</label>
                                <div class="uk-form-controls">
                                    <input type="text" name="AudioBitrate" value="160">
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">AudioRate</label>
                                <div class="uk-form-controls">
                                    <input type="text" name="AudioRate" value="48000">
                                </div>
                            </div>
                            <div class="uk-form-controls">
                                <button class="uk-button" type="submit">Convert</button>
                            </div>
                        </div>
                        <div class="videoplayer">
                            <video controls preload type='video/mp4; codecs="avc1.42001e, mp4a.40.5"'></video>
                            <p><a class="uk-button takevideoframe">Snapshot</a></p>
                        </div>
                        <div class="videoframes"></div>
                        <div class="videofile"></div>

                    </form>

                </div>


            </div>
        </div>
    </div>
</div>

<div id="categoryitem" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <form class="uk-form uk-form-horizontal">
            <div class="uk-form-row">
                <label class="uk-form-label" for="categoryitemName">Name</label>
                <div class="uk-form-controls">
                    <input type="text" name="Name" id="categoryitemName">
                </div>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="categoryitemPriority">Priority</label>
                <div class="uk-form-controls">
                    <input type="text" name="Priority" id="categoryitemPriority">
                </div>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="categoryitemParentSection">ParentSection</label>
                <div class="uk-form-controls">
                    <input type="text" name="ParentSection" id="categoryitemParentSection">
                </div>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="categoryitemPublish">Publish</label>
                <div class="uk-form-controls">
                    <input type="text" name="Publish" id="categoryitemPublish">
                </div>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-controls">
                    <button type="submit" class="uk-button uk-button-primary" id="categoryitembutton"><span class="Copy">Copy</span><span class="Edit">Edit</span></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="renamefile" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <form class="uk-form">
            <div class="uk-form-row">
                <div class="uk-form-controls">
                    <input type="text" name="Name" id="fileName">
                    <input type="hidden" id="fileNameOld">
                </div>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-controls">
                    <button type="submit" class="uk-button uk-button-primary" id="renamefilebutton">RENAME</button>
                    <a id="replacefilebutton">REPLACE EXISTING</a>
                </div>
            </div>
            <div id="renamefilelinks"></div>
        </form>
    </div>
</div>

<div id="imgmodal" class="uk-modal">
    <div class="uk-modal-dialog uk-modal-dialog-lightbox">
        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
        <img src="" alt="">
    </div>
</div>

<div id="hidden"><?=$aftertext?></div>

<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>uk/css/components/nestable.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>uk/css/components/form-file.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>uk/css/components/placeholder.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>uk/css/components/progress.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>uk/css/components/accordion.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?=$dom?>js/imgareaselect/css/imgareaselect-default.dt.css">
<link rel="stylesheet" type="text/css" media="all" href="style.css">


<script type="text/javascript" src="<?=$dom?>uk/js/components/nestable.min.js"></script>
<script type="text/javascript" src="<?=$dom?>uk/js/components/upload.min.js"></script>
<script type="text/javascript" src="<?=$dom?>uk/js/components/accordion.min.js"></script>
<script type="text/javascript" src="<?=$dom?>js/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
<script type="text/javascript" src="<?=$dom?>js/jquery-ui.drag.min.js"></script>
<script type="text/javascript" src="script.js"></script>

</body>
</html>