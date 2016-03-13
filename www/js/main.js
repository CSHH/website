$(function() {

    (function() {
        $('[data-confirm]').click(function(e) {
            var q = $(this).attr('data-confirm');
            var a = window.confirm(q);
            if (a === false) {
                e.preventDefault();
            }
        });
    })();

    (function() {
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
    })();

    (function() {
        var cookieName = 'cshhCookiesConfirmation';

        if (document.cookie.indexOf(cookieName) === -1) {
            var $container = $('#cookies-confirmation');

            $container[0].style.display = 'block';

            $container.find('.confirmation-button').click(function() {
                document.cookie = cookieName + '=' + true + '; expires=Fri, 31 Dec 9999 23:59:59 GMT';
                $container.fadeOut();
            });
        }
    })();

    $.nette.ext({
        load: function() {
            $('.flash, .form-error').find('.close').click(function() {
                $(this).parent().remove();
            });
        }
    });

});
