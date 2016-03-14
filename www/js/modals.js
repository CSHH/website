$(function() {

    $('[data-modal-target]').click(function() {
        var attr = $(this).attr('data-modal-target');
        var $target = $('.modal').filter('[data-modal=' + attr + ']');
        $('.modal').removeClass('active');
        $('#black-filter').addClass('active');
        $target.addClass('active');
        $('body').css('overflow', 'hidden');

        var windowHeight = window.innerHeight;
        var targetHeight = $target.height();

        if (windowHeight > targetHeight) {
            var gap = (windowHeight - targetHeight) / 2;
            $target[0].style.top = gap + 'px';
        } else {
            $target[0].style.top = '0px';
            $target[0].style.height = windowHeight + 'px';
            $target[0].style.overflow = 'auto';
        }
    });

    $('.modal > .close').click(function() {
        $('.modal').removeClass('active');
        $('#black-filter').removeClass('active');
        $('body').css('overflow', 'auto');
    });
});
