$(function () {
    var progressbar = $('#progressbar'),
        bar = progressbar.find('.uk-progress-bar'),
        settings = {
            single: false,
            action: '_upload.php', // upload url
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

                $target = $('.Item.uploading');
                $('.Item').removeClass('uploading');
                res = JSON.parse(response);
                $.each(res, function (index, value) {
                    $target.find('.itemDonor').val( value.name ).text( value.name );
                    $target.find('.upload-select').data('valuename', value.name).data('valueext', value.ext).data('valuetmp', value.tmp);
                    $target.find('.file-uploaded').text(value.name + value.ext);
                });
            }
        },
        drop = UIkit.uploadDrop($('.upload-drop'), settings),
        
    TplSelectHandle = function (el) {
        $('.Tpl').slideUp().removeClass('active');
        $('#' + el).slideDown().addClass('active');
        globalDateHandle( $('#' + el + ' .globalDate').val() );
        itemDonorAuto();
    },
    globalDateHandle = function (el) {
        $('.Tpl.active .itemDate, .Tpl.active .itemDateInserted').val( el ).text( el );
        arr = el.split('-');
        int = parseInt(arr.join(''), 10);
        $('.Tpl.active .itemPriority').val( int ).text( int );
        dir = $('.Tpl.active .globalDir');
        dirData = dir.data('value');
        if (dirData.indexOf('$y')>-1){
            dirData = dirData.replace('$y', arr[0]);
        }
        if (dirData.indexOf('$m')>-1){
            dirData = dirData.replace('$m', arr[1]);
        }
        dir.val( dirData ).text( dirData );
        globalDirHandle(dirData);
    },
    globalNameHandle = function (el) {
        $('.Tpl.active .itemName').each(function () {
            val = $(this).data('value') + el;
            $(this).val( val ).text( val );
        });
    },
    globalUrlHandle = function (el) {
        $('.Tpl.active .itemUrl').each(function () {
            val = $(this).data('value') + el;
            $(this).val( val ).text( val );
        });
    },
    globalDirHandle = function (el) {
        $('.Tpl.active .itemDir').each(function () {
            $(this).val( el ).text( el );
        });
    },
    itemDonorHandle = function (el, apply) {
        apply = (typeof apply != undefined) ? apply : false;
        $.post(
            '_find.php',
            {
                'name': el.val(),
            }
        ).done(function (response) {
            list = el.parents('.Item').find('.itemDonorList');
            list.html('');
            res = JSON.parse(response);
            $.each(res, function (index, item) {
                id = list.attr('id') + 'Item' + item.DownloadItemID;
                $('<div class="itemDonorListItem" id="' + id + '" title="' + item.Name + '">' +
                    '<a href="http://marya.ru/download/category/' + item.DownloadSectionID + '/" title="' + item.SectionName + '" target="_blank" class="uk-icon-folder-o"></a> ' +
                    '<a href="http://marya.ru/download/viewitem/' + item.DownloadItemID + '" title="Open" target="_blank" class="uk-icon-link"></a> ' +
                    item.Name + '</div>'
                ).appendTo(list);
                for (var key in item) {
                    $('#' + id).data(key, item[key]);
                }
                list.show();
                if (apply) {
                    $('.itemDonorListItem:first', list).trigger('click');
                }
            });
        });

    },
    itemDonorAuto = function () {
        tpl = $('.Tpl.active');
        $('.Item', tpl).each(function () {
            donor = $('.itemDonor', this);
            name = $('.itemName', this).val();
            if ( !donor.val() && name ){
                donor.val(name).text(name);
                itemDonorHandle( donor, true );
            }
        });
    },
    idForUpdate = function (item, id) {
        item.data('id', id);
        item.find('.ItemID').html('<span>ID: <a href="http://marya.ru/download/viewitem/' + id + '" target="_blank">' + id + '</a></span>');
        $('.insert-update', item).text('Update').addClass('update');
    },
    insertUpdate = function (el, step) {
        item = el.parents('.Item');
        item.addClass('progress');
        item.removeClass('iudone');
        item.addClass('ftpdone');
        fileext = item.find('.upload-select').data('valueext');
        url = item.find('.itemUrl').val();
        dir = item.find('.itemDir').val();
        item.find('.itemUrl').data('end', dir + url + fileext);
        file = item.find('.upload-select').data('valuename');
        if (file) {
            item.removeClass('ftpdone');
            item.find('.uk-form-file').addClass('progress');
            filetmp = item.find('.upload-select').data('valuetmp');
            $.post(
                '_ftp.php',
                {
                    'file': file,
                    'filetmp': filetmp,
                    'fileext': fileext,
                    'url': url,
                    'dir': dir,
                }
            ).done(function (response) {
                item.find('.uk-form-file').removeClass('progress');
                item.find('.upload-select').data('valuename', '');
                item.addClass('ftpdone');
                if (response) {
                    url = url + response;
                    item.find('.itemUrl').addClass('alert').val( url ).text( url );
                    afterInsertUpdate(item);
                } else {
                    item.find('.itemUrl').removeClass('alert');
                }
            });
        }

        fields = {};
        $('.ifield', item).each(function () {
            fields[$(this).attr('name')] = $(this).val();
        });
        fields['Url'] = item.find('.itemUrl').data('end');

        if (item.data('id')){
            fields['DownloadItemID'] = item.data('id');
            $.post(
                '_update.php',
                fields
            ).done(function (response) {
                item.addClass('iudone');
                item.find('.itemUrl').removeClass('alert');
                afterInsertUpdate(item);
            });
        } else {
            $.post(
                '_insert.php',
                fields
            ).done(function (response) {
                item.addClass('iudone');
                if (response>0) {
                    idForUpdate(item, response);
                }
                afterInsertUpdate(item);
            });
        }

    },
    afterInsertUpdate = function (item) {
        if (!item.hasClass('iudone') || !item.hasClass('ftpdone') ){
            setTimeout(function () {
                afterInsertUpdate(item);
            }, 500);
        } else {
            archive = item.find('.itemDonorListItem.active');
            if ( archive.size() && $('.itemDonorArchive', item).prop('checked') && !$('.itemDonorArchive', item).parents('.switch').hasClass('done') ){
                $('.itemDonorArchive', item).parents('.switch').addClass('progress');
                fieldsa = {
                    'DownloadItemID': archive.data('DownloadItemID'),
                    'Archive': 1
                };
                $.post(
                    '_update.php',
                    fieldsa
                ).done(function (response) {
                    $('.itemDonorArchive', item).parents('.switch').removeClass('progress').addClass('done');
                    updateFtp(item);
                });
            } else {
                updateFtp(item);
            }
        }


    },
    updateFtp = function (next) {

        if (item.find('.itemUrl').hasClass('alert')){
            insertUpdate($('.insert-update', item));
        } else {
            afterAllUpdate(next);
        }
    },
    afterAllUpdate = function (next) {

        item.removeClass('progress');
        if (doall) {
            item.removeClass('doall').removeClass('doall');
            next = item.parents('.Tpl').find('.Item.doall:not(.active):first').find('.insert-update');
            if (next.size()){
                insertUpdate(next);
            }
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

    $('.globalDir').keyup(function () {
        globalDirHandle( $(this).val() );
    });

    $('.itemDonor').keyup(function () {
        itemDonorHandle( $(this) );
    });

    $('.Item').on('click', '.delete', function(){
        $deleteitem = $(this).parents('.Item');
        UIkit.modal.confirm('Delete item?', function(){
            $deleteitem.remove();
        });

    }).on('click', '.cutHandle', function(){
        $(this).next('.cutBody').slideToggle();

    }).on('click', '.uk-form-file', function(){
        $('.Item').removeClass('uploading');
        $(this).parents('.Item').addClass('uploading');

    }).on('click', '.itemDonorListItem', function(){
        $(this).parents('.itemDonorList').find('.itemDonorListItem').removeClass('active');
        $(this).addClass('active');
        donor = $(this);
        item = donor.parents('.Item');
        $('.ifield', item).each(function () {
            val = donor.data($(this).attr('name'));
            $(this).val( val ).text( val );
        });

        dir = '';
        url = donor.data('Url');
        if (url !== null){
            dir = url.split('/downloads/');
            dir = dir[dir.length-1];
            dir = dir.split('/');
            dir = (dir.length > 1) ? dir[0] : '';
            if ( dir.substr(0,2)==='20' ) dir = '';
        }

        if (dir) {
            dir1 = $('.itemDir', item).val();
            dir0 = dir1.split('/downloads/');
            dir0 = dir0[1];
            dir0 = dir0.split('/');
            dir0 = (dir0[0].substr(0,2)!= '20') ? dir0[0] : '';

            if (dir0) {
                dir1 = dir1.replace(dir0, dir);
            } else {
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
                url = url.substr(0,urlext);
            }
        } else {
            url = '';
        }

        date = $('.globalDate', donor.parents('.Tpl')).val();
        //globalDateHandle( date );

        $('.itemUrl', item).val( url + date ).text( url + date );
        //globalUrlHandle( url + date );

        $('.itemDonorPick', item).slideDown();

    }).on('click', '.insert-update', function(){
        insertUpdate($(this));

    }).on('click', '.itemDonorPick', function(){
        item = $(this).parents('.Item');
        if ( $('.itemDonorListItem.active', item ).size() ){
            idForUpdate(item, $('.itemDonorListItem.active', item ).data('DownloadItemID'));
        }
    }).bind('drop', function(e) {
        $('.Item').removeClass('uploading');
        $(e.currentTarget).addClass('uploading');
    });

    $('.Tpl').on('click', '.doall', function(){
        tpl = $(this).parents('.Tpl');
        $('.Item', tpl).addClass('doall');
        tpl.addClass('doall');
        insertUpdate($('.insert-update:first', tpl));
    });

    $('.upload-select').each(function () {
        UIkit.uploadSelect($(this), settings);
    });

    TplSelectHandle( 'default' );

});