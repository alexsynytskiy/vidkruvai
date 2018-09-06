var screenH;
var screenW;
var sidebarClass = '.sidebar-right-fixed';

$.fn.extend({
    equalHeights: function (options) {
        var ah = (Math.max.apply(null, $(this).map(function () {
            return $(this).height();
        }).get()));
        if (typeof ah == 'number') $(this).height(ah);
    }
});

$(document).ready(function () {
    'use strict';

    // Calculate the screen size
    screenH = $(document).height();
    screenW = $(document).width();

    windowSize(screenW);
    $(window).resize(function () {
        windowSize(screenW);
    });

    const IS_IOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

    if (IS_IOS) {
        document.documentElement.classList.add('ios');
    }

    $('.news-item.small').equalHeights();
    $(window).resize(function () {
        $('.news-item.small').equalHeights();
    }).trigger('resize');

    $(window).scroll(function () {
        if ($(window).width() > 991) {
            var classToAdd = 'sidebar-right-fixed-bottom-big';

            if ($(window).width() < 1200) {
                classToAdd = 'sidebar-right-fixed-bottom-small';
            }

            if ($(window).scrollTop() + 120 > $(document).height() - $(window).height()) {
                console.log('add');
                $(sidebarClass).addClass(classToAdd);
            }
            else {
                console.log('remove');
                var $sidebar = $(sidebarClass);
                $sidebar.removeClass('sidebar-right-fixed-bottom-small');
                $sidebar.removeClass('sidebar-right-fixed-bottom-big');
            }
        }
    });
});

/**
 * window height preparing
 */
function windowSize(width) {
    var biggestHeight = 0;

    var isCabinet = $('.cabinet').size(),
        searchBlock = '.steps-block > div',
        isEdit = $('.user-form-edit').size();

    if(isCabinet) {
        searchBlock = '.cabinet article > div';
    }

    $(searchBlock).each(function() {
        if(width < 768) {
            biggestHeight += $(this).height();
        }
        else {
            if($(this).height() > biggestHeight) {
                biggestHeight = $(this).height();
            }
        }
    });

    $(".steps-block").height(biggestHeight);

    var height = 80 + biggestHeight + 300;

    if(isEdit) {
        height += 320;
    }

    if(width < 768) {
        height = 80 + biggestHeight + 410;
    }

    if (height < $(window).height()) {
        height = '100vh';
    }
    else {
        height += 'px';
    }

    $('body').css({
        'min-height': height
    });
}
