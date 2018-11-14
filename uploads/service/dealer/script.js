$(function () {
    var progressbar = $('#progressbar'),
        bar = progressbar.find('.uk-progress-bar'),
        settings = { //настройки upload
            single: false,
            action: '_upload.php',
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
                target = $('.uk-form-row.uploading');
                item = target.closest('.Item');
                target.removeClass('uploading');
                res = JSON.parse(response);
                $.each(res, function (index, value) {
                    if (target.hasClass('url')) item.find('.itemDonor').val( value.name ).text( value.name ); //ищем имя файла в донор
                    target.find('.upload-select').data('type', 'upload').data('valueext', value.ext).data('valuetmp', value.tmp);
                    target.find('.file-uploaded').text(value.name + value.ext);
                    ifield = target.find('.ifield').data('value');
                    target.find('.ifield').val( ifield ).text( ifield ); //меняем поле на предустановленное значение
                });
            }
        },
        drop = UIkit.uploadDrop($('.upload-drop'), settings),
        
    TplSelectHandle = function (el) {
        $('.Tpl').slideUp().removeClass('active');
        $('#' + el).slideDown().addClass('active');
        globalDateHandle( $('#' + el + ' .globalDate').val() );
        if ( $('#' + el).hasClass('donor') ){
            itemDonorAuto();
        }
    },
    globalDateHandle = function (el) {
        $('.Tpl.active .itemDate, .Tpl.active .itemDateInserted').val( el ).text( el );
        arr = el.split('-');
        int = parseInt(arr.join(''), 10);
        $('.Tpl.active .itemPriority').val( int ).text( int );
        dir = $('.Tpl.active .globalDir');
        //dirData = dir.data('value');
        dirData = dir.val();
        if (dirData) {
            if (dirData.indexOf('$y')>-1){
                dirData = dirData.replace('$y', arr[0]);
            }
            if (dirData.indexOf('$m')>-1){
                dirData = dirData.replace('$m', arr[1]);
            }
            dir.val( dirData ).text( dirData );
            globalDirHandle(dirData);
        }
    },
    globalNameHandle = function (el) {
        $('.Tpl.active .itemName').each(function () {
            val = $(this).data('value') + el;
            $(this).val( val ).text( val );
        });
    },
    globalUrlHandle = function (el) {
        $('.Tpl.active .itemUrl, .Tpl.active .FieldsOne .itemVideo').each(function () {
            val = $(this).data('value') + el;
            $(this).val( val ).text( val );
        });
    },
    globalImageBigHandle = function (el) {
        $('.Tpl.active .itemImageBig').each(function () {
            val = $(this).data('value') + el;
            $(this).val( val ).text( val );
        });
    },
    globalImageHandle = function (el) {
        $('.Tpl.active .itemImage').each(function () {
            val = $(this).data('value') + el;
            $(this).val( val ).text( val );
        });
    },
    globalPublishHandle = function (el) {
        $('.Tpl.active .itemPublish').each(function () {
            $(this).val( el ).text( el );
        });
    },
    globalArchiveHandle = function (el) {
        $('.Tpl.active .itemArchive').each(function () {
            $(this).val( el ).text( el );
        });
    },
    globalDirHandle = function (el) {
        $('.Tpl.active .itemDir').each(function () {
            val = $(this).data('value') + el;
            $(this).val( val ).text( val );
        });
        if ( typeof ftptimeout != 'undefined' ){
            clearInterval(ftptimeout);
        }
        ftptimeout = setTimeout(function () {
            ftp( '?dir=downloads' + el );
        }, 1000);
    },
    globalDownloadSectionIDHandle = function (el) {
        $('.Tpl.active .itemDownloadSectionID').each(function () {
            $(this).val( el ).text( el );
        });
    },
    itemsSelectHandle = function (el) {
        tpl = el.closest('.Tpl');
        id = el.val();
        if ( el.prop('checked') ){
            el.closest('label').addClass('active');
            if ($('#hidden #' + id).size()) {
                $('#' + id).appendTo( $('.Items', tpl) );
            }
        } else {
            el.closest('label').removeClass('active');
            if ($('#' + id, tpl).size()) {
                $('#' + id).appendTo( '#hidden' );
            }
        }
    },
    itemDonorHandle = function (el, apply, pick) {
        apply = (typeof apply != 'undefined') ? apply : false;
        pick = (typeof pick != 'undefined') ? pick : false;
        $.post(
            '_find.php',
            {
                'name': el.val(),
            }
        ).done(function (response) {
            list = el.closest('.Item').find('.itemDonorList');
            list.html('');
            res = JSON.parse(response);
            $.each(res, function (index, item) {
                id = list.attr('id') + 'Item' + item.DownloadItemID;
                status = '';
                if (item.Archive==1){
                    status += ' archive';
                }
                if (item.Publish==1){
                    status += ' publish';
                }
                $('<div class="itemDonorListItem ' + status + '" id="' + id + '" title="' + item.Name + '">' +
                    '<a href="http://marya.ru/download/category/' + item.DownloadSectionID + '/" title="' + item.SectionName + '" target="_blank" class="uk-icon-folder-o"></a> ' +
                    '<a href="http://marya.ru/download/viewitem/' + item.DownloadItemID + '" title="Open" target="_blank" class="uk-icon-link"></a> ' +
                    item.Name +
                    '<div class="status"><span class="uk-icon-file-archive-o"></span><span class="uk-icon-eye"></span></div>' +
                    '</div>'
                ).appendTo(list);
                for (var key in item) {
                    $('#' + id).data(key, item[key]);
                }
            });
            list.show();
            if (apply) {
                if (apply===true) {
                    apply = $('.itemDonorListItem:first', list);
                } else {
                    apply = $('#' + list.attr('id') + 'Item' + apply);
                }
                apply.trigger('click');
            }
            if (pick) {
                el.closest('.Item').find('.itemDonorPick').trigger('click');
            }
        });

    },
    itemDonorAuto = function () {
        tpl = $('.Tpl.active');
        $('.Item', tpl).each(function () {
            donor = $('.itemDonor', this);
            name = $('.itemName', this).val();
            pick = ($(this).hasClass('Pick')) ? true : false;
            if ( !donor.val() && name ){
                donor.val(name).text(name);
                itemDonorHandle( donor, true, pick );
            }
            if ( ($('.globalPublish', tpl).val()!=0) && !pick ){
                $('.itemDonorArchive', this).attr('checked', 'checked').val('checked');
            }
        });
    },
    idForUpdate = function (item, id) {
        item.data('id', id);
        item.find('.ItemID').html('<span>ID: <a href="http://marya.ru/download/viewitem/' + id + '" target="_blank">' + id + '</a></span>');
        $('.insert-update', item).text('Update').addClass('update');
        $('.itemDonorPick', item).text('Unpick').addClass('Unpick');
    },
    ftp = function (href) {
        $('#acc-ftp-body').addClass('progress');
        sort = '';
        if ( $('#acc-ftp .status').hasClass('bydate') ){
            sort = 'bydate';
        }
        $.post(
            '_ftp1.php' + href,
            {
                'sort': sort,
            }
        ).done(function (response) {
            $('#acc-ftp-body').removeClass('progress').html(response);
            $('#col2').scrollTop(0);
            $('#acc-ftp-body li').draggable({
                helper: "clone",
                appendTo: "#grid"
            });
        });
    },
    db = function () {
        opened = [];
        $('#acc-db-body .CategoryLi.opened:not(:first)').each(function() {
            opened.push( $(this).attr('id') );
        });
        $('#acc-db-body').addClass('progress');
        data = {};
        if ( $('#acc-db-title .status').hasClass('Publish') ){
            data.Publish = 1;
        }
        if ( $('#acc-db-title .status').hasClass('Archive') ){
            data.Archive = 1;
        }
        $.post(
            '_tree.php',
            data
        ).done(function (response) {
            $('#acc-db-body').removeClass('progress').html('');
            res = JSON.parse(response);
            CategoryLi = '<h7></h7><ul class="Children"></ul><ul class="Items"></ul>';
            $.each(res.Sections, function (index, value) {
                if ( !$('#Category' + value.ParentSection).size() ){
                    $('<li id="Category'  + value.ParentSection + '" class="CategoryLi">' + CategoryLi + '</li>').appendTo( '#acc-db-body' );
                }
                if ( !$('#Category' + value.DownloadSectionID).size() ){
                    $('<li id="Category'  + value.DownloadSectionID + '" class="CategoryLi">' + CategoryLi + '</li>').appendTo( '#Category' + value.ParentSection + ' > .Children' );
                }
                cat = $('#Category' + value.DownloadSectionID);
                cat.find('h7:first').html('<span class="handle"></span>' + value.Name).attr('title',  value.DownloadSectionID);
                for (var key in value) {
                    cat.data(key, value[key]);
                }
                cat.appendTo( '#Category' + value.ParentSection + ' > .Children' );
            });
            $.each(res.Items, function (index, value) {
                $('<li id="Item' + value.DownloadItemID + '" class="Itemli"><h8 title="' + value.DownloadItemID + '"><span class="handle"></span>' + value.Name +
                    '<div class="status"><span data-field="Archive" class="setItemStatus uk-icon-file-archive-o"></span><span data-field="Publish" class="setItemStatus uk-icon-eye"></span></div></h8></li>')
                    .appendTo( '#Category' + value.DownloadSectionID + ' > .Items' );
                item = $('#Item' + value.DownloadItemID);
                for (var key in value) {
                    item.attr('data-' + key, value[key]);
                }
                if (value.Archive==1) {
                    item.addClass('Archive');
                }
                if (value.Publish!=1) {
                    item.addClass('Publish');
                }
            });

            $('#acc-db-body h7').each(function() {
                li = $(this).parent('li');
                if ( li.find('.Items li').size() || li.find('.Nado').size() ){
                    li.addClass('Nado');
                }
            });
            $('.CategoryLi:first').addClass('opened');
            opened.map(function(id) {
                $('#' + id + ' h7:first').trigger('click');
            });

            $('#acc-db-body .Itemli').draggable({
                helper: "clone",
                appendTo: "#grid"
            });

        });
    },
    domen = function (url) {
        if ( url.indexOf('http')<0 ){
            url = 'http://marya.ru/' + url;
        }
        return url;
    },
    setItemStatus = function (el) {
        li = el.closest('.Itemli');
        li.addClass('progress');
        fields = {
            'DownloadItemID' : li.data('downloaditemid')
        };
        set = 0;
        if ( el.data('field')=='Publish' ){
            if ( li.hasClass('Publish') ){
                set = 1;
            }
        } else if ( el.data('field')=='Archive' ) {
            if ( !li.hasClass('Archive') ){
                set = 1;
            }
        }
        fields[el.data('field')] = set;
        $.post(
            '_update.php',
            fields
        ).done(function (response) {
            li.toggleClass(el.data('field')).removeClass('progress');
        });
    },
    categoryMenu = function (el) {
        menu = el.next('.menu');
        if ( !el.next('.menu').size() ){
            $('<div class="menu"><div data-action="copy">Копировать</div><div data-action="edit">Редактировать</div></div>').insertAfter(el);
            menu = el.next('.menu');
            menu.slideUp(0);
        }
        menu.slideToggle();
    },
    categoryMenuCopy = function (id) {
        li = $('#Category' + id);
        $('#categoryitem').removeClass('Edit').addClass('Copy').data('id', id);
        $('#categoryitem input').each(function () {
            val = li.data($(this).attr('name'));
            $(this).text(val).val(val);
        });
        name = li.data('Name') + ' copy';
        $('#categoryitemName').text(name).val(name);
        UIkit.modal('#categoryitem').show();
    },
    categoryMenuEdit = function (id) {
        li = $('#Category' + id);
        $('#categoryitem').removeClass('Copy').addClass('Edit').data('id', id);
        $('#categoryitem input').each(function () {
            val = li.data($(this).attr('name'));
            $(this).text(val).val(val);
        });
        UIkit.modal('#categoryitem').show();
    },
    urlEnd = function (inp) {
        urlEndurl = inp.val();
        urlEnddir = '';
        urlEndext = '';
        if (urlEndurl){
            if ( urlEndurl.indexOf('/')<0 ){
                urlEnddir = inp.closest('.Item').find('.itemDir').val();
                if ( !urlEnddir ){
                    urlEnddir = inp.closest('.Tpl').find('.globalDir').val();
                }
            }
            if ( urlEndurl.indexOf('.')<0 ){
                urlEndext = inp.closest('.uk-form-row').find('.upload-select').data('valueext');
                if ( !urlEndext ){
                    urlEndext = '.jpg';
                }
            }
        }
        return urlEnddir + urlEndurl + urlEndext;
    },
    videoProgress = function (file, form) {
        $.post(
            '_videoprogress.php',
            {
                'File': file
            }
        ).done(function (response) {
            res = JSON.parse(response);
            if (res.progress<100) {
                bar.css('width', res.progress + '%').text(res.progress + '%');
                setTimeout(function () {
                    return videoProgress(file, form);
                }, 1000);
            } else {
                videoEnd(file, form, res);
            }
        });
    },
    videoEnd = function (file, form, data) {
        form.addClass('done');
        bar.css('width', '100%').text('100%');
        setTimeout(function () {
            progressbar.addClass('uk-hidden');
        }, 1000);
        video = form.find('video');
        video.slideUp();
        setTimeout(function () { //чтобы видео успело подгрузиться (или сохраниться на диске, хз)
            video.attr('src', form.data('converted')).data('file', file).slideDown();
        }, 2000);
        form.find('li[data-file="' + form.data('tmp') + '"]').remove();
        $('<li data-file="' + form.data('tmp') + '" class="vfile">' + form.data('converted') + '<span>' + data.lsize + '</span><span class="remove"></span></li>').appendTo(form.find('.videofile')).draggable({
            helper: "clone",
            appendTo: "#grid"
        });
        videoFrames(file, form);
    },
    videoFrames = function (file, form, time) {
        time = (typeof time != 'undefined') ? time : 'end';
        $.post(
            '_videoframes.php',
            {
                'File': file,
                'Time': time
            }
        ).done(function (response) {
            $('<a class="videoframe" href="/uploads/tmp/' + response + '" data-file="' + response + '" target="_blank" style="background-image:url(/uploads/tmp/' + response + ')"><span class="remove"></span></a>').appendTo(form.find('.videoframes')).draggable({
                helper: "clone",
                appendTo: "#grid"
            });
        });
    },
    insertUpdate = function (el, step) {
        step = (typeof step != 'undefined') ? step : 'step1';
        item = el.closest('.Item');
        item.addClass(step);
        dir = item.find('.itemDir').val();

        if (step==='step1'){
            item.addClass('progress');
            tpl = item.closest('.Tpl');
            fileext = item.find('.uk-form-row.url .upload-select').data('valueext');
            url = item.find('.itemUrl').val();
            file = item.find('.uk-form-row.url .upload-select').data('type');
            urlEndVal = urlEnd( item.find('.itemUrl') );
            item.find('.itemUrl').data('end', urlEndVal);
            if (file) {
                item.find('.uk-form-row.url .uk-form-file').addClass('progress');
                filetmp = item.find('.uk-form-row.url .upload-select').data('valuetmp');
                fieldsa = {
                    'filetmp': filetmp,
                    'fileext': fileext,
                    'url': url,
                    'dir': dir,
                    'type': file
                };
                $.post(
                    '_ftp.php',
                    fieldsa
                ).done(function (response) {
                    item.find('.uk-form-row.url .file-uploaded').text('');
                    item.find('.uk-form-row.url .uk-form-file').removeClass('progress');
                    item.find('.uk-form-row.url .upload-select').data('type', '');
                    url = response;
                    item.find('.itemUrl').val(url).text(url);
                    item.removeClass('step1');
                    ftp( '?dir=downloads' + dir );
                    return insertUpdate(el, 'step1a');
                });

            } else {
                item.find('.itemUrl').val(urlEndVal).text(urlEndVal);
                item.removeClass('step1');
                return insertUpdate(el, 'step1a');
            }
        } else if (step==='step1a'){
            ImageBig = item.find('.itemImageBig').closest('.uk-form-row');
            ImageBigfile = ImageBig.find('.upload-select').data('type');
            ImageBigfileext = ImageBig.find('.upload-select').data('valueext');
            ImageBigurl = item.find('.itemImageBig').val();
            ImageBigurlEndVal = urlEnd( item.find('.itemImageBig') );
            item.find('.itemImageBig').data('end', ImageBigurlEndVal);
            if (ImageBigfile) {
                ImageBig.find('.uk-form-file').addClass('progress');
                filetmp = ImageBig.find('.upload-select').data('valuetmp');
                fieldsa = {
                    'filetmp': filetmp,
                    'fileext': ImageBigfileext,
                    'url': ImageBigurl,
                    'dir': dir,
                    'type': ImageBigfile,
                    'max': ImageBig.data('max'),
                    'pdf': ImageBig.data('pdf'),
                };
                $.post(
                    '_ftp.php',
                    fieldsa
                ).done(function (response) {
                    ImageBig.find('.file-uploaded').text('');
                    ImageBig.find('.uk-form-file').removeClass('progress');
                    ImageBig.find('.upload-select').data('type', '');
                    ImageBigurl = response;
                    item.find('.itemImageBig').val(ImageBigurl).text(ImageBigurl);
                    item.removeClass('step1a');
                    ftp( '?dir=downloads' + dir );
                    return insertUpdate(el, 'step1b');
                });

            } else {
                item.find('.itemImageBig').val(ImageBigurlEndVal).text(ImageBigurlEndVal);
                item.removeClass('step1a');
                return insertUpdate(el, 'step1b');
            }
        } else if (step==='step1b'){
            Image = item.find('.itemImage').closest('.uk-form-row');
            Imagefile = Image.find('.upload-select').data('type');
            Imagefileext = Image.find('.upload-select').data('valueext');
            Imageurl = item.find('.itemImage').val();
            ImageurlEndVal = urlEnd( item.find('.itemImage') );
            item.find('.itemImage').data('end', ImageurlEndVal);
            if (Imagefile) {
                Image.find('.uk-form-file').addClass('progress');
                filetmp = Image.find('.upload-select').data('valuetmp');
                fieldsa = {
                    'filetmp': filetmp,
                    'fileext': Imagefileext,
                    'url': Imageurl,
                    'dir': dir,
                    'type': Imagefile,
                    'max': Image.data('max'),
                    'pdf': Image.data('pdf'),
                };
                $.post(
                    '_ftp.php',
                    fieldsa
                ).done(function (response) {
                    Image.find('.file-uploaded').text('');
                    Image.find('.uk-form-file').removeClass('progress');
                    Image.find('.upload-select').data('type', '');
                    Imageurl = response;
                    item.find('.itemImage').val(Imageurl).text(Imageurl);
                    item.removeClass('step1b');
                    ftp( '?dir=downloads' + dir );
                    return insertUpdate(el, 'step1c');
                });

            } else {
                item.find('.itemImage').val(ImageurlEndVal).text(ImageurlEndVal);
                item.removeClass('step1b');
                return insertUpdate(el, 'step1c');
            }
        } else if (step==='step1c'){
            if ( $('.FieldsOne .itemVideo', item).size() ){
                Video = item.find('.itemVideo').closest('.uk-form-row');
                Videofile = Video.find('.upload-select').data('type');
                Videofileext = Video.find('.upload-select').data('valueext');
                Videourl = item.find('.itemVideo').val();
                VideourlEndVal = urlEnd( item.find('.itemVideo') );
                item.find('.itemVideo').data('end', VideourlEndVal);
                if (Videofile) {
                    Video.find('.uk-form-file').addClass('progress');
                    filetmp = Video.find('.upload-select').data('valuetmp');
                    fieldsa = {
                        'filetmp': filetmp,
                        'fileext': Videofileext,
                        'url': Videourl,
                        'dir': dir,
                        'type': Videofile,
                    };
                    $.post(
                        '_ftp.php',
                        fieldsa
                    ).done(function (response) {
                        Video.find('.file-uploaded').text('');
                        Video.find('.uk-form-file').removeClass('progress');
                        Video.find('.upload-select').data('type', '');
                        Videourl = response;
                        item.find('.itemVideo').val(Videourl).text(Videourl);
                        item.removeClass('step1c');
                        return insertUpdate(el, 'step2');
                    });
                } else {
                    item.find('.itemVideo').val(VideourlEndVal).text(VideourlEndVal);
                    item.removeClass('step1c');
                    return insertUpdate(el, 'step2');
                }
            } else {
                item.removeClass('step1c');
                return insertUpdate(el, 'step2');
            }
        } else if (step==='step2'){
            fields = {};
            $('.ifield', item).each(function () {
                fields[$(this).attr('name')] = $(this).val();
            });

            if (item.data('id')){
                fields['DownloadItemID'] = item.data('id');
                $.post(
                    '_update.php',
                    fields
                ).done(function (response) {
                    item.removeClass('step2');
                    return insertUpdate(el, 'step3');
                });
            } else {
                $.post(
                    '_insert.php',
                    fields
                ).done(function (response) {
                    if (response>0) {
                        idForUpdate(item, response);
                    }
                    item.removeClass('step2');
                    return insertUpdate(el, 'step3');
                });
            }
        } else if (step==='step3'){
            archive = item.find('.itemDonorListItem.active');
            if ( archive.size() && $('.itemDonorArchive', item).prop('checked') && !$('.itemDonorArchive', item).closest('.switch').hasClass('done') ){
                $('.itemDonorArchive', item).closest('.switch').addClass('progress');
                fieldsa = {
                    'DownloadItemID': archive.data('DownloadItemID'),
                    'Archive': 1
                };
                $.post(
                    '_update.php',
                    fieldsa
                ).done(function (response) {
                    $('.itemDonorArchive', item).closest('.switch').removeClass('progress').addClass('done');
                    item.removeClass('step3');
                    return insertUpdate(el, 'step4');
                });
            } else {
                item.removeClass('step3');
                return insertUpdate(el, 'step4');
            }
        } else if (step==='step4'){
            item.removeClass('progress').removeClass('step4');
            if (item.closest('.Tpl').hasClass('doall')) {
                item.removeClass('doall');
                next = item.closest('.Tpl').find('.Item.doall:not(.active):first').find('.insert-update');
                if (next.size()){
                    return insertUpdate(next);
                } else {
                    return insertUpdate(el, 'step5');
                }
            } else {
                return insertUpdate(el, 'step5');
            }
        } else if (step==='step5'){
            afterfiles = tpl.find('.afterfiles');
            afterfiles.html('');
            afterfilesar = [];
            $('.uk-form-row.image, .uk-form-row.url, .uk-form-row.video', tpl).each(function () {
                if ( !afterfilesar[$('.ifield', this).val()] ){
                    afterfilesar.push($('.ifield', this).val());
                }
            });
            afterfilesdata = {};
            id = item.attr('id');
            afterfilesar.forEach(function(i, k, arr) {
                $('<div id="afterfiles' + id + k + '">' + i + '</div>').appendTo(afterfiles);
                afterfilesdata['afterfiles' + id + k] = i;
            });
            $.post(
                '_check.php',
                afterfilesdata
            ).done(function (response) {
                res = JSON.parse(response);
                $.each(res, function (index, value) {
                    $('#' + index).addClass('exist' + value);
                });
            });

            if ( tpl.data('after') ){
                $('.aftertext', tpl).html('');
                if ( tpl.data('after')=='links' ){
                    $('.Item', tpl).each(function () {
                        link = $('.ItemID a', this).attr('href');
                        link = '<a href="' + link + '">' + link + '</a>';
                        name = $('.itemName', this).val();
                        $('<p>' + name + ' ' + link + '</p>').appendTo( $('.aftertext', tpl) );
                    });
                } else if ( tpl.data('after')=='audio' ){
                    $('<pre>').appendTo( $('.aftertext', tpl) );
                    $('.Item', tpl).each(function () {
                        link = $('.ItemID a', this).html();
                        link = '{Block_ID_' + link + '}\n' +
                            '<p><audio controls="controls" src="{Block_Url}" type="audio/mp3"></audio><br>\n' +
                            '{Block_Name} <a href="/download/getitem/{Block_DownloadItemID}/">Скачать</a>\n' +
                            '</p><br>';
                        link = link.replace(/</g, '&lt;');
                        link = link.replace(/>/g, '&gt;');
                        $('.aftertext pre', tpl).append(link + '<br><br>');
                    });
                } else if ( tpl.data('after')=='video' ){
                    $('<pre>').appendTo( $('.aftertext', tpl) );
                    link = '';
                    $('.Item', tpl).each(function () {
                        id = $('.ItemID a', this).html();
                        name = $('.itemName', this).val();
                        if ( $('.itemVideo', this).val() ){
                            if (link!=''){
                                link = link + '</p><br>\n\n';  
                            }
                            link = link + '{Block_ID_' + id + '}\n' +
                                '<video width="640" height="360" controls="" poster="{Block_ImageBig}" src="{Block_Video}" type=\'video/mp4; codecs="avc1.42001e, mp4a.40.5"\' /></video>\n' +
                                '<p>' + name + ' <br>\n' +
                                '<a href="/download/getitem/{Block_DownloadItemID}/">Скачать 16×9</a> &amp;nbsp;\n';
                        } else {
                            link = link + '<a href="/download/getitem/' + id + '/">Скачать ' + name + '</a> &amp;nbsp;\n';
                        }
                    });
                    link = link + '</p><br>\n\n';
                    link = link.replace(/</g, '&lt;');
                    link = link.replace(/>/g, '&gt;');
                    $('.aftertext pre', tpl).append(link);
                    
                }  else if ( tpl.data('after')=='akcii' ){
                    $('<pre>').appendTo( $('.aftertext', tpl) );
                    $('.Item', tpl).each(function () {
                        link = $('.ItemID a', this).html();
                        link = '{Block_ID_' + link + '}\n' + $('#after' +  $(this).attr('id') ).html();                         
                        link = link.replace(/</g, '&lt;');
                        link = link.replace(/>/g, '&gt;');
                        $('.aftertext pre', tpl).append(link + '<br><br>');
                    });
                }
            }

            item.removeClass('step5');
        }
    };


    $('#TplSelect').change(function () {
        TplSelectHandle( $(this).val() );
    });

    $('.globalDate').keyup(function () {
        globalDateHandle( $(this).val() );
    });

    $('.globalName').keyup(function () {
        globalNameHandle( $(this).val() );
    });

    $('.globalUrl').keyup(function () {
        globalUrlHandle( $(this).val() );
    });

    $('.globalImageBig').keyup(function () {
        globalImageBigHandle( $(this).val() );
    });

    $('.globalImage').keyup(function () {
        globalImageHandle( $(this).val() );
    });

    $('.globalDir').keyup(function () {
        globalDirHandle( $(this).val() );
    });

    $('.globalPublish').keyup(function () {
        globalPublishHandle( $(this).val() );
    });

    $('.globalArchive').keyup(function () {
        globalArchiveHandle( $(this).val() );
    });

    $('.globalDownloadSectionID').keyup(function () {
        globalDownloadSectionIDHandle( $(this).val() );
    });

    $('.Tpl-settings').on('click', 'label', function(){
        $(this).closest('.uk-form-row').find('input').trigger('keyup');
    });

    $('.itemDonor').keyup(function () {
        itemDonorHandle( $(this) );
    });

    $('.Item').on('click', '.delete', function(){
        inp = $('.itemsSelect input[value="' + $(this).closest('.Item').attr('id') + '"]');
        inp.prop('checked', false);
        itemsSelectHandle(inp);

    }).on('click', '.cutHandle', function(){
        $(this).next('.cutBody').slideToggle();

    }).on('click', '.itemDonorListItem', function(){
        $(this).closest('.itemDonorList').find('.itemDonorListItem').removeClass('active');
        $(this).addClass('active');
        donor = $(this);
        item = donor.closest('.Item');
        $('.ifield:not(.itemPriority):not(.itemDateInserted):not(.itemDate)', item).each(function () {
            val = donor.data($(this).attr('name'));
            $(this).val( val ).text( val );
        });

        date = $('.globalDate', donor.closest('.Tpl')).val();
        globalDateHandle( date );

        dir = '';
        url = donor.data('Url');

        file = item.find('.uk-form-row.url .upload-select').data('type');

        if (url !== null){
            dir = url.split('/downloads/');
            dir = dir[dir.length-1];
            dir = dir.split('/');
            dir = (dir.length > 1) ? dir[0] : '';
            if ( dir.substr(0,2)==='20' ) dir = ''; //есть ли своя папка
        }
        dir1 = $('.itemDir', item).val();
        if (dir) { //своя папка есть
            dir0 = dir1.split('/downloads/');
            dir0 = dir0[1];
            dir0 = dir0.split('/');
            dir0 = (dir0[0].substr(0,2)!= '20') ? dir0[0] : ''; //своя папка это не дата
            if (dir0) {
                dir1 = dir1.replace(dir0, dir);
            } else { //своей папки все-таки нет, добавляем
                dir0 = dir1.split('/');
                dir0.splice(2,0,dir);
                dir1 = dir0.join('/');
            }
            $('.itemDir', item).val(dir1);
        }

        url = donor.data('Url');
        if (url !== null){
            url = url.split('/');
            url = url[url.length-1];
            url = url.split('20');
            url = url[0];
            urlext = url.lastIndexOf('.');
            if (urlext>0) {
                url = url.substr(0,urlext); //убираем расширение
            }
        } else {
            url = '';
        }

        $('.itemUrl', item).val( url + date ).text( url + date );

        name = $('.itemName', item).val();
        namear = [];
        if (name !== null){
            namear = name.split('20');
            name = namear[0];
            dateold = namear[1];
            dateend = '';

            if ( name[name.length-1]==='(' ){
                date += ')';
            } else if ( name[name.length-1]!==' ' ){
                date = ' ' + date;
            }

            if ( typeof dateold != 'undefined' ){
                i = 0;
                while( parseInt(dateold[i]) || (dateold[i]=='.') || (dateold[i]=='-')){
                    i++;
                }
                if ( i<3 ){
                    datey = date.indexOf('-');
                    date = date.substr(0, datey);
                    date += dateold.substring(i);
                }
            }
        } else {
            name = '';
        }
        $('.itemName', item).val( name + date ).text( name + date );

        $('.itemDonorPick', item).slideDown();

        $(this).closest('.itemDonorList').scrollTop(0).scrollTop($(this).position().top);


    }).on('click', '.insert-update', function(){
        insertUpdate($(this));

    }).on('click', '.itemDonorPick', function(){
        item = $(this).closest('.Item');
        donor = $('.itemDonorListItem.active', item );
        if ( $(this).hasClass('Unpick') ){
            item.data('id', '');
            item.find('.ItemID').html('');
            $('.insert-update', item).text('Insert').removeClass('update');
            $(this).text('Pick').removeClass('Unpick');
            unpick = $('.itemDonorListItem.active', item);
            if ( !unpick.size() ) {
                unpick = $('.itemDonorListItem:first', item);
            }
            unpick.trigger('click');
        } else if ( donor.size() ){
            $('.ifield', item).each(function () {
                val = donor.data( $(this).attr('name') );
                $(this).val( val ).text( val );
            });
            idForUpdate(item, $('.itemDonorListItem.active', item ).data('DownloadItemID'));
        }

    }).on('mouseenter', '.uk-form-row.image', function(){
        inp = $(this).find('.ifield');
        img = $(this).find('.preview');
        if ( !img.size() ){
            $('<div class="preview"><img><div class="size"></div></div>').insertBefore(inp);
            img = $(this).find('.preview');
        }
        img.find('img').attr('src', domen(inp.val()));
        img1 = img.find('img').clone().css({maxWidth:'none', position:'absolute', left:'-10000px'}).appendTo('body');
        img1.load(function(){
            w = $(this).width();
            h = $(this).height();
            img.find('.size').text(w + 'x' + h);
            img1.remove();
        });

    }).on('dblclick', 'textarea', function(){
        $(this).toggleClass('wide');

    }).on('click', '.file-uploaded', function(){
        $(this).closest('.uk-form-row').find('.upload-select').data('type', '');
        $(this).text('');
    });

    $('.Tpl').on('click', '.doall', function(){
        tpl = $(this).closest('.Tpl');
        $('.Item', tpl).addClass('doall');
        tpl.addClass('doall');
        insertUpdate($('.insert-update:first', tpl));
    });

    $('#acc-ftp-body').on('click', '.rootdir a, .dir a', function(){
        ftp($(this).attr('href'));
        return false;

    }).on('dblclick', '.file a', function(e){
        file = $(this).closest('.file');
        name = file.data('dir') + file.data('file');
        $('#fileName, #fileNameOld').val( name ).text( name );
        $('#renamefile').addClass('progress').removeClass('replace');
        UIkit.modal('#renamefile').show();
        $.post(
            '_findfile.php',
            {
                name: name
            }
        ).done(function (response) {
            $('#renamefilelinks').html('');
            $('#renamefile').removeClass('progress');
            res = JSON.parse(response);
            $.each(res, function (index, value) {
                $('<div class="renamefileitem"><label class="active"><input type="checkbox" value="'
                    + value.DownloadItemID + '" checked> <span><a href="http://marya.ru/download/viewitem/' + value.DownloadItemID + '" target="_blank">' + value.DownloadItemID + '</a> '
                    + value.Name + '</span></label></div>').appendTo('#renamefilelinks');
            });

        });
        return false;

    });

    $('.uk-form-row').bind('drop', function(e) {
        $('.uk-form-row').removeClass('uploading');
        $(e.currentTarget).addClass('uploading');

    })
    
    $('.Item .uk-form-row.url').droppable({
        accept: '.file',
        drop: function( event, ui ) {
            item = $(this).closest('.Item');
            url = $('.ui-draggable-dragging').data('file');
            $('#acc-ftp-body li[data-file="' + url + '"]').addClass('used');
            dir = $('.ui-draggable-dragging').data('dir');
            urlext = url.lastIndexOf('.');
            if (urlext>0) {
                urlex = url.substr(urlext);
                url = url.substr(0,urlext);
            }
            $(this).find('.ifield').val( url ).text( url );
            dirdata = item.find('.itemDir').data('value') + dir;
            item.find('.itemDir').val( dirdata ).text( dirdata );
            item.find('.itemDonor').val( url ).text( url );
            $(this).find('.upload-select').data('type', 'ftp').data('valueext', urlex).data('valuetmp', dir + url + urlex);
            $(this).find('.file-uploaded').text(url + urlex);
        }
    });     
    
    $('.Item .uk-form-row.image').droppable({
        accept: '.file, .videoframe',
        drop: function( event, ui ) {
            item = $(this).closest('.Item');
            url = $('.ui-draggable-dragging').data('file');
            if ( $('.ui-draggable-dragging').hasClass('videoframe') ){
                $(this).find('.upload-select').data('type', 'upload').data('valuetmp', url);
                $(this).find('.file-uploaded').text(url);
            } else {
                $('#acc-ftp-body li[data-file="' + url + '"]').addClass('used');
                dir = $('.ui-draggable-dragging').data('dir');
                urlext = url.lastIndexOf('.');
                if (urlext>0) {
                    urlex = url.substr(urlext);
                    url = url.substr(0,urlext);
                }
                dirdata = item.find('.itemDir').data('value') + dir;
                item.find('.itemDir').val( dirdata ).text( dirdata );
                $(this).find('.upload-select').data('type', 'ftp').data('valueext', urlex).data('valuetmp', dir + url + urlex);
                $(this).find('.file-uploaded').text(url + urlex);
            }
            $(this).find('.ifield').val( url ).text( url );
        }
    });

    $('.Item .uk-form-row.donor').droppable({
        accept: '.Itemli',
        drop: function( event, ui ) {
            item = $(this).closest('.Item');
            name = $('.ui-draggable-dragging').data('name');
            donor = $('.itemDonor', item);
            id = $('.ui-draggable-dragging').data('downloaditemid');
            donor.text(name).val(name);
            itemDonorHandle(donor, id, true);
        }
    });

    $('.Item .uk-form-row.video').droppable({
        accept: '.file, .vfile',
        drop: function( event, ui ) {
            item = $(this).closest('.Item');
            url = $('.ui-draggable-dragging').data('file');
            if ( $('.ui-draggable-dragging').hasClass('vfile') ){
                $(this).find('.upload-select').data('type', 'upload').data('valuetmp', url);
                $(this).find('.file-uploaded').text(url);
            } else {
                $('#acc-ftp-body li[data-file="' + url + '"]').addClass('used');
                dir = $('.ui-draggable-dragging').data('dir');
                urlext = url.lastIndexOf('.');
                if (urlext>0) {
                    urlex = url.substr(urlext);
                    url = url.substr(0,urlext);
                }
                dirdata = item.find('.itemDir').data('value') + dir;
                item.find('.itemDir').val( dirdata ).text( dirdata );
                $(this).find('.upload-select').data('type', 'ftp').data('valueext', urlex).data('valuetmp', dir + url + urlex);
                $(this).find('.file-uploaded').text(url + urlex);
            }
            $(this).find('.ifield').val( url ).text( url );
        }
    });
    
    $('#acc-db-body').on('click', 'h7', function(){
        $(this).next('.Children').slideToggle().next('.Items').slideToggle();
        $(this).parent('.CategoryLi').toggleClass('opened');

    }).on('click', '.setItemStatus', function(){
        setItemStatus($(this));

    }).on('click', '.CategoryLi h7 .handle', function(){
        categoryMenu($(this));
        return false;

    }).on('mouseleave', '.CategoryLi h7', function(){
        $(this).find('.menu').slideUp();

    }).on('click', '.CategoryLi .menu div', function(){
        li = $(this).parents('.CategoryLi:first');
        id = li.data('DownloadSectionID');
        if ( $(this).data('action')=='copy' ){
            categoryMenuCopy(id);
        } else if ( $(this).data('action')=='edit' ){
            categoryMenuEdit(id);
        }
        $(this).parent('.menu').slideUp();
        return false;

    });

    $('#acc-db-title').on('click', '.status span', function(){
        $(this).closest('.status').toggleClass( $(this).data('class') );
        db();
        return false;
    });

    $('#acc-ftp').on('click', '.status span', function(){
        $(this).closest('.status').toggleClass( 'bydate' );
        ftp( $('#acc-ftp-body .rootdir a:last' ).attr('href'));
        return false;
    });

    $('#categoryitem').on('submit', 'form', function(){
        if ( $('#categoryitem').hasClass('Copy') ){
            fields = $(this).serialize();
            $.post(
                '_insertcategory.php',
                fields
            ).done(function (response) {
                UIkit.modal('#categoryitem').hide();
                li = $('#Category' + $('#categoryitem').data('id')).clone().attr('id', 'Category' + response);
                $('#categoryitem input').each(function () {
                    li.data($(this).attr('name'), $(this).val());
                });
                li.find('.Children').remove();
                li.find('.Items').remove();
                li.find('h7').html('<b>' + response + '</b> ' + li.data('Name')).attr('title', response);
                li.insertAfter('#Category' + $('#categoryitem').data('id'));
            });
        } else if ( $('#categoryitem').hasClass('Edit') ){
            fields = $(this).serialize() + '&DownloadSectionID=' + $('#categoryitem').data('id');
            $.post(
                '_updatecategory.php',
                fields
            ).done(function (response) {
                UIkit.modal('#categoryitem').hide();
                db();
            });
        }
        return false;
    });

    $('#renamefile').on('submit', 'form', function(){
        if ( !$('#renamefile').hasClass('progress') ){
            $('#renamefile').addClass('progress');
            items = [];
            $('#renamefilelinks input:checked').each(function(){
                items.push($(this).val());
            });
            replace = ($('#renamefile').hasClass('replace')) ? 1 : 0;
            fields = {
                old: $('#fileNameOld').val(),
                new: $('#fileName').val(),
                replace: replace,
                items: items
            };
            $.post(
                '_renamefile.php',
                fields
            ).done(function (response) {
                UIkit.modal('#renamefile').hide();
                $('#acc-ftp-body .uk-alert').remove();
                if (response==1){
                    $('<div class="uk-alert uk-alert-success">Done</div>').prependTo('#acc-ftp-body');
                    $('.rootdir a:last').trigger('click');
                } else {
                    $('<div class="uk-alert uk-alert-danger">' + response + '</div>').prependTo('#acc-ftp-body');
                }
            });
        }
        return false;
    });

    $('#replacefilebutton').click(function(){
        $('#renamefile').addClass('replace').find('form').trigger('submit');
        return false;
    });

    $('.upload-select').each(function () {
        UIkit.uploadSelect($(this), settings);
    });

    $('.itemsSelect input').change(function(el){
        itemsSelectHandle( $(this) );
    });

    $('#grid').on('click', '.uk-form-file', function(){
        $('.uk-form-row').removeClass('uploading');
        $(this).closest('.uk-form-row').addClass('uploading');

    });

    $('#acc-video-body').on('submit', 'form', function(){
        form = $(this);
        if (form.hasClass('done')) {
            form.removeClass('done');
            file = form.find('.upload-select').data('valuetmp');
            fields = form.serialize() + '&File=' + file;
            bar.css('width', '0%').text('0%');
            progressbar.removeClass('uk-hidden');
            $.post(
                '_video.php',
                fields
            ).done(function (response) {
                res = JSON.parse(response);
                form.data('converted', res.preview).data('tmp', res.tmp);
                videoProgress(file, form);
            });
        }
        return false;

    }).on('click', '.vfile .remove', function(){
        $(this).closest('.vfile').remove();

    }).on('click', '.takevideoframe', function(){
        form = $(this).closest('form');
        video = form.find('video');
        videoFrames(video.data('file'), form, video.get(0).currentTime);

    }).on('click', '.videoframe .remove', function(){
        $(this).closest('.videoframe').remove();
        return false;

    }).on('click', '.videoframe', function(){
        $('#imgmodal img').attr('src', $(this).attr('href'));
        UIkit.modal('#imgmodal', {center:true}).show();
        return false;
    });

    $('#acc-video-body .videoframe').draggable({
        helper: "clone",
        appendTo: "#grid"
    });

    db();

    TplSelectHandle( 'default' );

});