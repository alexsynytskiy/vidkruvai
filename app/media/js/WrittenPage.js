var WrittenPage = function (options) {
    var pageOptions = $.extend(true, {
        startBlockUrl: ''
    }, options);

    $(document).ready(function () {
        'use strict';

        var screenW = $(document).width();

        new ResizeSensor($('body .content-left-fixed .profile-user-page'), function () {
            SiteCore.windowSize(screenW);
        });
    });
};

