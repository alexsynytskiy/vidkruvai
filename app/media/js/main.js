var screenH;
var screenW;

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

    windowSize();
    $(window).resize(function () {
        windowSize();
    });

    const IS_IOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

    if (IS_IOS) {
        document.documentElement.classList.add('ios');
    }

    $(window).resize(function () {
        $('.answers .answer').equalHeights();
    }).trigger('resize');

    // Calculate the screen size
    screenH = $(document).height();
    screenW = $(document).width();
});

/**
 * Animate the canvas
 */
function animate() {
    context.clearRect(0, 0, screenW, screenH);
    $.each(stars, function () {
        this.draw(context);
    })
}

/**
 * window with gradient size preparing
 */
function windowSize() {
    var height = 80 + $('.steps-block').height() + 310;

    if (height < $(window).height()) {
        height = '100vh';
    }
    else {
        height += 'px';
    }

    $('#gradient, body').css({
        'min-height': height
    });
}
