var AppConfig = $.extend(true, {
    systemLanguage: 'uk',
    userTimeZone: '',
    domain: '',
    httpScheme: ''
}, (typeof AppConfig !== 'undefined' ? AppConfig : {}));

var SiteCore = function (options) {
    return {
        init: function () {
            this.initPNotify();

            $.fn.extend({
                equalHeights: function (options) {
                    var ah = (Math.max.apply(null, $(this).map(function () {
                        return $(this).height();
                    }).get()));
                    if (typeof ah === 'number') $(this).height(ah);
                }
            });

            const IS_IOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

            if (IS_IOS) {
                document.documentElement.classList.add('ios');
            }

            $('.news-item.small').equalHeights();
            $(window).resize(function () {
                $('.news-item.small').equalHeights();
            }).trigger('resize');
        },
        initPNotify: function () {
            var $flashMessages = $('#module-flash-messages').find('.message');

            if ($flashMessages.length) {
                $flashMessages.each(function (i) {
                    var $msg = $(this);

                    new PNotify({
                        title: $msg.data('title'),
                        text: $msg.text(),
                        icon: $msg.data('icon'),
                        type: $msg.data('style'),
                        delay: 6000 //Show the notification 4sec
                    });
                });
            }
        },
        getCsrfToken: function () {
            var token = $('meta[name="csrf-token"]').attr("content");
            return (typeof token !== 'undefined' ? token : null);
        },
        t: function (key, params, lang) {
            lang = lang || AppConfig.systemLanguage;
            params = params || {};

            var keys = key.split('.'),
                result = undefined,
                keysStr = '';

            for (var i in keys) {
                keysStr += '["' + keys[i] + '"]';
            }

            if (keysStr) {
                result = eval('Translations["' + lang + '"]' + keysStr + ';');
            }

            if (result && params) {
                for (var paramName in params) {
                    result = result.replace(new RegExp('\{' + paramName + '\}', 'g'), params[paramName]);
                }
            }

            return result;
        },
        windowSize: function (width) {
            var biggestHeight = 0;

            var isCabinet = $('.cabinet').size(),
                searchBlock = '.steps-block > div',
                isEdit = $('.user-form-edit').size();

            if (isCabinet) {
                searchBlock = '.cabinet article > div';
            }

            $(searchBlock).each(function () {
                if (width < 768) {
                    biggestHeight += $(this).height();
                }
                else {
                    if ($(this).height() > biggestHeight) {
                        biggestHeight = $(this).height();
                    }
                }
            });

            $(".steps-block").height(biggestHeight);

            var height = 80 + biggestHeight + 300;

            if (isEdit) {
                height += 320;
            }

            if (width < 768) {
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
        },
        windowScroll: function () {
            var sidebarClass = '.sidebar-right-fixed';

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
        }
    }
}();

$(function () {
    SiteCore.init();
});

$(document).ready(function () {
    'use strict';

    var screenW = $(document).width();

    SiteCore.windowSize(screenW);
    $(window).resize(function () {
        SiteCore.windowSize(screenW);
    });

    $(window).scroll(function () {
        SiteCore.windowScroll();
    });
});