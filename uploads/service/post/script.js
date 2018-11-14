$(function () {
    var periods = [ 9, 12, 15, 23 ],
    vkgetpostponed = function (brand) {
        brand = (typeof brand != 'undefined') ? brand : 0;
        $.post(
            '_vk_getpostponed.php',
            {
                'brand': brand,
            }
        ).done(function (response) {
            res = JSON.parse(response);
            $.each(res.response.items, function (index, value) {
                console.log(value);
                
                var date = new Date(value.date*1000);
                var hours1 = date.getHours();
                var hours = '0' + hours1;
                var minutes = '0' + date.getMinutes();
                var time = hours.substr(-2) + ':' + minutes.substr(-2);
                var month = date.getMonth()+1;
                month = '0' + month;
                var dayy = '0' + date.getDate();
                var month1 = date.getFullYear() + month.substr(-2);
                var day =  dayy.substr(-2);
                var id = value.id;
                var period = 1;
                if (hours<periods[1]) {
                    period = 0;
                } else if (hours>periods[2]) {
                    period = 2;
                }
                if ( !$('#postpreviewvk'+id).size() ){
                    $('<div class="postpreview vk" id="postpreviewvk' + id + '" data-id="' + id + '" data-month="' + month1 + '" data-day="' + day + '" data-time="' + time + '" data-brand="' + brand + '" data-net="vk" data-period="' + period + '">' +
                        '</div>').appendTo('#postponed');
                }
            });
        });
    },
    renderCalendar = function () {
        $('.TabBody.Brand').each(function(){
            $('#hidden .Calendar').clone().appendTo(this);
        });
    };

    $('.TabUl a').click(function (e) {
        e.preventDefault();
        id = $(this).attr('href');
        $tabs = $(this).closest('.TabUl').next('.TabBodies').find('.TabBody').not('[id="' + id + '"]');
        $tabs.css('position', 'absolute').fadeOut();
        $(id).css('position', 'relative').fadeIn();
    });
    $('.TabUl a:first').trigger('click');

    renderCalendar();

    vkgetpostponed('marya');
});