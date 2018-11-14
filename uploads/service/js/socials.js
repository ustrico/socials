$(function () {

    var w1 = 800, w2 = 500, k = 1, w = 0, h = 0, x1 = 0, y1 = 0, x2 = 0, y2 = 0, d = 1, upcounti = 0,
        progressbar = $('#progressbar'),
        bar = progressbar.find('.uk-progress-bar'),
        settings = {
            single: false,
            action: 'upload.php', // upload url
            allow: '*.(jpeg|jpg|gif|png)', // allow only images
            loadstart: function () {
                bar.css('width', '0%').text('0%');
                progressbar.removeClass('uk-hidden');
            },
            progress: function (percent) {
                percent = Math.ceil(percent);
                bar.css('width', percent + '%').text(percent + '%');
            },
            allcomplete: function (response) {
                bar.css('width', '100%').text('100%');
                setTimeout(function () {
                    progressbar.addClass('uk-hidden');
                }, 1000);

                img = JSON.parse(response);
                beforeuploadcount = $('.uploaded').size();
                $.each(img, function (index, value) {
                    upcounti++;
                    $('<div class="uploaded" data-img="' + value['img'] + '" data-w="' + value['w'] + '" data-h="' + value['h'] + '" id="upp' + upcounti + '" data-version="0"><i></i><img src="' + value['img'] + '"></div>').appendTo('#uploaded');
                });
                upcount = $('.uploaded').size();
                if (upcount > 1) {
                    $('#uploaded').show();
                }
                $('.uploaded').eq(beforeuploadcount).find('img').trigger('click');
                $('#ButtonCropDiv').show();
            }
        },
        select = UIkit.uploadSelect($('#upload-select'), settings),
        drop = UIkit.uploadDrop($('#upload-drop'), settings),
        currentBrand = function (value) {
            $e = $('#BrandSelect');
            if (typeof value != "undefined") {
                $e.data('current', value);
            } else {
                return $e.data('current');
            }
        },
        currentCategory = function (value) {
            $e = $('#' + currentBrand() + ' > .CategorySelect');
            if (typeof value != "undefined") {
                $e.data('current', value);
            } else {
                return $e.data('current');
            }
        },
        currentTile = function (value) {
            $e = $('#' + currentCategory() + ' > .TileUl');
            if (typeof value != "undefined") {
                $e.data('current', value);
            } else {
                return $e.data('current');
            }
        },
        currentCollage = function (value) {
            $e = $('#' + currentCategory() + ' .CollageUl');
            if (typeof value != "undefined") {
                $e.data('current', value);
            } else {
                return $e.data('current');
            }
        },
        isTitle = function () {
            if (typeof currentCategory() != "undefined") {
                if ($('#' + currentCategory()).data('title')) {
                    $('#TitleDiv').slideDown();
                } else {
                    $('#TitleDiv').slideUp();
                }
            } else {
                $('#TitleDiv').slideUp();
            }
        },
        resizeArea = function () {
            cu = currentTile();
            $Tile = $('#' + cu);
            if ( $Tile.find('.Collage').size() ){
                cu = currentCollage();
                $Tile = $('#' + cu);
            }
            $Upp = $('.uploaded.active .' + cu);
            if (!$Upp.size()) {
                $('<b class="' + cu + '">').appendTo('.uploaded.active');
                $Upp = $('.uploaded.active .' + cu);
            }
            /*if ( $Tile.hasClass('Collage') ){
                $('.Collage:not(#' + cu + ')', $Tile.parent('.Tile')).each(function(){
                    $('.uploaded.active .' + $(this).attr('id')).remove();
                });
            }*/
            if ($Upp.data('x1') || $Upp.data('x2')) {
                x1 = $Upp.data('x1') * k;
                y1 = $Upp.data('y1') * k;
                x2 = $Upp.data('x2') * k;
                y2 = $Upp.data('y2') * k;
            } else {
                x1 = 0;
                y1 = 0;
                if (($Tile.data('w') / $Tile.data('h')) > w / h) {
                    x2 = w * k;
                    y2 = $Tile.data('h') / $Tile.data('w') * w * k;
                } else {
                    y2 = h * k;
                    x2 = $Tile.data('w') / $Tile.data('h') * h * k;
                }
                $Upp.data('x1', x1);
                $Upp.data('y1', y1);
                $Upp.data('x2', x2 / k);
                $Upp.data('y2', y2 / k);
            }
            resizePhoto();
            $('#Area').imgAreaSelect({
                hide: true,
                disable: true,
                remove: true
            });

            $('#Area').imgAreaSelect({
                aspectRatio: $Tile.data('w') + ':' + $Tile.data('h'),
                x1: x1, y1: y1, x2: x2, y2: y2,
                handles: true,
                enable: true,
                onSelectEnd: function (img, selection) {
                    cu = currentTile();
                    if ( $('#' + cu).find('.Collage').size() ){
                        cu = currentCollage();
                    }
                    $Upp = $('.uploaded.active .' + cu);
                    $Upp.data('x1', selection.x1 / k);
                    $Upp.data('y1', selection.y1 / k);
                    $Upp.data('x2', selection.x2 / k);
                    $Upp.data('y2', selection.y2 / k);
                    resizePhoto();
                }
            });

            setTimeout(function () {
                if ($Tile.data('src')) $('.imgareaselect-selection').css('background-image', 'url(' + $Tile.data('src') + ')');
            }, 100);

        },
        resizePhoto = function () {
            cu = currentTile();
            $Tile = $('#' + cu);
            cuw = w2;
            if ( $Tile.find('.Collage').size() ){
                cu = currentCollage();
                $Tile = $('#' + cu);
                cuw = $Tile.width();
            }
            $Upp = $('.uploaded.active .' + cu);
            d = $('.uploaded.active').data('w') / ($Upp.data('x2') - $Upp.data('x1'));
            $Tile.find('.Photo').css('width', cuw * d).css('left', -$Upp.data('x1') * k * d * cuw / w1).css('top', -$Upp.data('y1') * k * d * cuw / w1);
        };


    $('#ButtonCrop').click(function () {
        $('#Preview').show().addClass('loading');
        $Tile = $('#' + currentTile());
        $Upp = $('.uploaded.active .' + currentTile());
        options = {
            w: $Tile.data('w'),
            h: $Tile.data('h'),
            name: $Tile.attr('id'),
            tile: $Tile.data('src'),
        };
        if ( $Tile.find('.Collage').size() ){
            options1 = {};
            $('.Collage', $Tile).each(function(){
                $Upp1 = $('#' + $(this).data('up'));
                $Upp2 = $Upp1.find('.' + $(this).attr('id'));
                options1[$(this).attr('id')]={
                    img: $Upp1.data('img'),
                    w: $(this).data('w'),
                    h: $(this).data('h'),
                    x: $(this).data('l'),
                    y: $(this).data('t'),
                    x1: $Upp2.data('x1'),
                    y1: $Upp2.data('y1'),
                    x2: $Upp2.data('x2'),
                    y2: $Upp2.data('y2'),
                }
            });
            options = $.extend(options, {
                collage: options1,
            });
        } else {
            options = $.extend(options, {
                img: $('.uploaded.active').data('img'),
                x1: $Upp.data('x1'),
                y1: $Upp.data('y1'),
                x2: $Upp.data('x2'),
                y2: $Upp.data('y2'),
            });
        }
        if ($('#' + currentCategory()).data('title')) {
            options = $.extend(options, {
                title: $('#Title').val(),
                align: $('#' + currentCategory()).data('title'),
                left: $Tile.data('left'),
                top: $Tile.data('top'),
                size: $Tile.data('size'),
                font: $Tile.data('font'),
                color: $Tile.data('color'),
            });
            $Resize = $Tile.closest('.TileI').find('.TitleResize');
            if ( $Resize.size() ){
                options.size = $Resize.val();
                console.log(options);
            }
        }
        if ($Tile.hasClass('DigitTile')) {
            options = $.extend(options, {
                digitleft: $Tile.data('digit-left'),
                digittop: $Tile.data('digit-top'),
                digitsize: $Tile.data('digit-size'),
                digitfont: $Tile.data('digit-font'),
            });
        }
        $.post(
            'crop.php',
            options
        ).done(function (data) {
            $('<a target="_blank" href="down.php?file=' + data + '"><img src="' + data + '"></a>').insertAfter('#PreviewLoading');
            $('#Preview').removeClass('loading');
        });
    });

    $('.TileUl a').click(function (e) {
        e.preventDefault();
        $(this).parents('.Category').find('.TileI').slideUp();
        $Tile = $($(this).attr('href'));
        $Tile.parent('.TileI').slideDown();
        currentTile($Tile.attr('id'));
        if ($Tile.find('.Collage').size()){
            $ca = $Tile.parent('.TileI').find('.CollageUl .active');
            if ( !$ca.is('a') ){
                $ca = $Tile.parent('.TileI').find('.CollageUl a:first');
            }
            //if ( typeof $($ca.attr('href')).data('up') == "undefined" ) $('.uploaded.active img').trigger('click');
            $ca.trigger('click');
            console.log($($ca.attr('href')).data('up'));
            $('.uploaded.active img').trigger('click');
        }
        resizeArea();
    });

    $('.CollageUl a').click(function (e) {
        e.preventDefault();
        $Collage = $($(this).attr('href'));
        $(this).parents('.CollageUl').find('a').removeClass('active');
        $(this).addClass('active');
        currentCollage($Collage.attr('id'));
        $('#' + $Collage.data('up') + ' .' + $Collage.attr('id')).trigger('click');
    });

    $('.Collage').each(function () {
        ck = w2 / $(this).parent('.Tile').data('w');
        $CollageA = $('<div class="CollageA" data-id="' + $(this).attr('id') + '">').insertAfter($(this).parent('.Tile').find('.Logo'));
        $('[data-id="' + $(this).attr('id') + '"]').css('width', $(this).data('w')*ck).css('height', $(this).data('h')*ck).css('left', $(this).data('l')*ck).css('top', $(this).data('t')*ck);
    });

    $(document.body).on('click', '.CollageA', function (e) {
        $('a[href="#' + $(this).data('id') + '"]').trigger('click');
    });

    $('#BrandSelect').change(function () {
        $('.Brand').slideUp();
        $tar = $('#' + $(this).val());
        $tar.slideDown();
        currentBrand($(this).val());
        isTitle();
        resizeArea();
    });

    $('.CategorySelect').change(function () {
        $brand = $('#' + currentBrand());
        $('.Category', $brand).slideUp();
        $tar = $('#' + $(this).val());
        $tar.slideDown();
        currentCategory($(this).val());
        $('#BrandSelect').data('category', $(this).val());
        if (!currentTile()) {
            $tar.find('.TileUl').find('a:first').trigger('click');
        } else {
            resizeArea();
        }
        isTitle();
    });

    $('#Title').keyup(function () {
        var val = $(this).val();
        var text = val.replace(/\r\n|\r|\n/g, '<br />');
        $('.Text').html(text);
        var digit = text.indexOf('<br />');
        if (digit>0){
            $('.DigitTile .Digit').html(text.substr(0, digit));
            $('.DigitTile .Text').html(text.substr(digit+6));
        }
    });

    $(document.body).on('click', '#Preview a', function () {
        $(this).fadeOut(1000, function () {
            $(this).remove();
            if (!$('#Preview a').size()) $('#Preview').hide();
        });
    });

    $(document.body).on('click', '.uploaded :not(i)', function () {
        $up = $(this).parents('.uploaded');
        $('.uploaded').removeClass('active');
        $up.addClass('active');
        $('#Area').css('width', '').css('height', '').attr('src', $up.data('img'));
        w = $up.data('w');
        h = $up.data('h');
        w1 = $('#col1').width();
        if (w > w1) {
            k = w1 / w;
            $('#Area').css('width', w1);
        }
        if (h * k > ( $(window).height() - 100 )) {
            k = ($(window).height() - 100) / h;
            w1 = w * k;
            $('#Area').css('width', w1);
        }
        if (!currentCategory()) {
            $category = $('#' + currentBrand() + ' > .CategorySelect');
            $option = $category.find('option:not(:disabled):first');
            $option.attr('selected', true);
            $category.val($option.val()).trigger('change');
        } else if (!currentTile()) {
            $('#' + currentCategory()).find('.TileUl').find('a:first').trigger('click');
        } else {
            resizeArea();
        }
        $('.Photo').each(function(){
            if (!$(this).parent('.Collage').size()){
                $(this).attr('src', $up.data('img'));
            }
        });
        $('#' + currentCollage()).data('up', $up.attr('id')).find('.Photo').attr('src', $up.data('img'));
    });

    $(document.body).on('click', '.uploaded i', function (e) {
        $(this).parents('.uploaded').fadeOut(1000, function () {
            $(this).remove();
        });
    });
    $(window).resize(function () {
        $('.uploaded.active img').trigger('click');
    }).trigger('resize');

    $('#Edit i').click(function () {
        $Upp = $('.uploaded.active');
        if ( $(this).hasClass('undo') ){
            if ( $('a', $Upp).size() ) {
                data = $('a:last', $Upp).attr('href');
                $('a:last', $Upp).remove();
                $('.uploaded.active').data('img', data).find('img').attr('src', data).trigger('click');
            }
        } else {
            $Upp.data('version', $Upp.data('version')+1);
            options = {
                name: $Upp.data('img'),
                edit: $(this).attr('class'),
                version: $Upp.data('version'),
            };
            $(this).addClass('loading');
            $.post(
                'edit.php',
                options
            ).done(function (data) {
                $Upp = $('.uploaded.active');
                $('<a href="' + $Upp.data('img') + '">').appendTo($Upp);
                $Upp.data('img', data).find('img').attr('src', data).trigger('click');
                $('#Edit i').removeClass('loading');
            });
        }
    });

    $('.TitleResize').change(function () {
        $resize = $(this).closest('.TitleResizeI');
        $resize.find('.TitleResizeVal').html( $(this).val() );
        $tile = $(this).closest('.TileI');
        $text = $tile.find('.Text');
        $text.css('fontSize', $text.data('k')*$(this).val() );
    });

});