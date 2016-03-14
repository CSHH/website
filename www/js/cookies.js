$(function() {

    var cookieName = 'cshhCookiesConfirmation';

    if (document.cookie.indexOf(cookieName) === -1) {
        var $container = $('#cookies-confirmation');

        $container[0].style.display = 'block';

        $container.find('.confirmation-button').click(function() {
            document.cookie = cookieName + '=' + true + '; expires=Fri, 31 Dec 9999 23:59:59 GMT';
            $container.fadeOut();
        });
    }
});
