$(function() {

    var cookieName = 'cshhCookiesConfirmation';

    if (document.cookie.indexOf(cookieName) === -1) {
        var $container = $('#cookies-confirmation');

        $container[0].style.display = 'block';

        $container.find('.confirmation-button').click(function() {
            document.cookie = cookieName + '=' + true + '; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT';
            $container.fadeOut();
        });
    }
});
;


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

    $.nette.ext({
        load: function() {
            $('.flash, .form-error').find('.close').click(function() {
                $(this).parent().remove();
            });
        }
    });

    $.nette.init();

});
;


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
;


$(function() {

    var cookieName      = 'cshhTheme',
        themeOtherworld = 'otherworld',
        themeFog        = 'fog';

    function getCurrentTheme() {

        var t  = null,
            ix = document.cookie.indexOf(cookieName);

        if (ix !== -1) {

            var str = document.cookie.substr(ix + 10);

            if (str.indexOf(themeOtherworld) !== -1) {
                t = themeOtherworld;

            } else if (str.indexOf(themeFog) !== -1) {
                t = themeFog;
            }
        }

        return t;
    }

    function loadTheme(theme) {
        $('#theme-stylesheet').attr('href', App.BASE_PATH + '/css/theme-' + theme + '.css');
        $('#bg-body-wrapper').css('background-image', 'url(' + App.BASE_PATH + '/images/bg-' + theme + '.jpg)');
    }

    function getThemeByTime() {

        var h = (new Date()).getHours();

        if (h >= 8 && h < 20) {

            return themeFog;

        } else {

            return themeOtherworld;

        }
    }

    function changeThemeOnClick() {

        $('#theme-switch').click(function () {

            var t = getCurrentTheme();

            if (t === null) {

                t = getThemeByTime();

            }

            var toUse = t === themeFog ? themeOtherworld : themeFog;

            document.cookie = cookieName + '=' + toUse + '; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT';

            loadTheme(toUse);
        });
    }

    changeThemeOnClick();

    function init() {

        var t;

        if (getCurrentTheme() === null) {

            t = getThemeByTime();

        } else {

            t = getCurrentTheme();

            document.cookie = cookieName + '=' + t + '; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT';
        }

        loadTheme(t);
    }

    init();

});
