$(function() {

    var cookieName = 'cshhHideChat';
    var ix = document.cookie.indexOf(cookieName);
    var substr = document.cookie.substr(ix, cookieName.length + 2);
    var arr = substr.split('=');

    if (arr[1] === 't') {
        $('#chatbox').removeClass('active');
    }

    $('#chatbox .chatbox-switch').click(function() {
        if ($('#chatbox').hasClass('active')) {
            $('#chatbox').removeClass('active');
            document.cookie = cookieName + '=' + true + '; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT';
        } else {
            $('#chatbox').addClass('active');
            document.cookie = cookieName + '=' + false + '; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT';
        }
    });
});
