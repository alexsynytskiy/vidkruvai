$(document).ready(function () {
    'use strict';

    var $flashMessages = $('#landings-flash-messages').find('.message'),
        sidebarClass = '.sidebar-right-fixed';

    if ($flashMessages.length) {
        $flashMessages.each(function (i) {
            var $msg = $(this);

            new PNotify({
                title: $msg.data('title'),
                text: $msg.text(),
                icon: $msg.data('icon'),
                type: $msg.data('style'),
                delay: 4000
            });
        });
    }

    $('.slider-1').bxSlider({
        slideWidth: 1170,
        slideHeight: 250,
        maxSlides: 1,
        minSlides: 1,
        slideMargin: 0
    });

    $(window).scroll(function () {
        if ($(window).width() > 991) {
            var classToAdd = 'sidebar-right-fixed-bottom-big';

            if ($(window).width() < 1200) {
                classToAdd = 'sidebar-right-fixed-bottom-small';
            }

            if ($(window).scrollTop() + 200 > $(document).height() - $(window).height()) {
                $(sidebarClass).addClass(classToAdd);
            }
            else {
                $(sidebarClass).removeClass('sidebar-right-fixed-bottom-small');
                $(sidebarClass).removeClass('sidebar-right-fixed-bottom-big');
            }
        }
    });
});