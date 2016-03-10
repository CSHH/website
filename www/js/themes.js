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

            document.cookie = cookieName + '=' + toUse + '; expires=Fri, 31 Dec 9999 23:59:59 GMT';

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

            document.cookie = cookieName + '=' + t;
        }

        loadTheme(t);
    }

    init();

});
