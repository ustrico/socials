$(function () {

    var currentBrand = function (value) {
            $e = $('#BrandSelect');
            if (typeof value != "undefined") {
                $e.data('current', value);
            } else {
                return $e.data('current');
            }
            $('.JohnsMan').removeClass('active');
            $('#JohnsPrev div').remove();
            $('#JohnsCode').html('');

        }, generate = function () {
            $('#JohnsCode').html('');
            $('#JohnsPrev div').each(function(){
                $('#JohnsCode').html( $('#JohnsCode').html() + $(this).html() );
            });
        };

    $('#BrandSelect').change(function () {
        window.location.href = '?brand=' + $('#BrandSelect').val();
    });

    $('.JohnsMan').click(function(){
        $(this).toggleClass('active');
        id = $(this).attr('id');
        if ($(this).hasClass('active')){
            $('<div id="' + id + 'Prev" class="uk-nestable-item">').html( $('.JohnsItem', this).html() ).appendTo('#JohnsPrev');
        } else {
            $('#' + id + 'Prev').remove();
        }
        generate();
    });

    $('.uk-nestable').on('change.uk.nestable', function(e) {
        generate();
    });

    $('#ButtonCopy').click(function(){
        $('#JohnsCode').select();
        try {
            var successful = document.execCommand('copy');
            if (successful) {
                UIkit.notify('Copied', {pos: 'top-left', status: 'success'});
            }
        } catch(err) {
            console.log('Oops, unable to copy');
        }
        window.getSelection().removeAllRanges();
    });

});