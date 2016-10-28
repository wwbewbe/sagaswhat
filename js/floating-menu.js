//フローティングメニュー（全画面右下に表示）
jQuery(function() {
    var floatingBtn = jQuery('#floating-menu');
    floatingBtn.hide();
    jQuery(window).scroll(function () {
        if (jQuery(window).scrollTop() > 0) {
            floatingBtn.fadeIn();
        } else {
            floatingBtn.fadeOut();
        }
    });
    //スクロールしてトップ
//    floatingBtn.click(function () {
//        jQuery('body,html').animate({
//            scrollTop: 0
//        }, 500);
//        return false;
//    });
});
