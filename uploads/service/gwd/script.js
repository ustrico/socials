$(function () {
    $('#Segmento').on('click', 'button', function(){
        $('#action').val( $(this).val() );
        $('#SegmentoForm').trigger('submit');
        return false;
    }).on('submit', '#SegmentoForm', function(){
        $('#ret').html('');
         $.post(
             $('#action').val() + '.php'
         ).done(function (response) {
         $('#ret').html('<pre>' + response + '</pre>');
         });
        return false;
    });

});