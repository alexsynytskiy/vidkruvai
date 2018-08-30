var ProfilePage = function (options) {
    var pageOptions = $.extend(true, {}, options);

    var selectors = {
        blockStart: '.i',
        sidebarClass: '.sidebar-right-fixed'
    };

    $('body').on("click", selectors.blockStart, function (e) {
        e.preventDefault();

    });

    $(window).scroll(function () {
        if ($(window).width() > 991) {
            var classToAdd = 'sidebar-right-fixed-bottom-big';

            if ($(window).width() < 1200) {
                classToAdd = 'sidebar-right-fixed-bottom-small';
            }

            if ($(window).scrollTop() + 200 > $(document).height() - $(window).height()) {
                $(selectors.sidebarClass).addClass(classToAdd);
            }
            else {
                $(selectors.sidebarClass).removeClass('sidebar-right-fixed-bottom-small');
                $(selectors.sidebarClass).removeClass('sidebar-right-fixed-bottom-big');
            }
        }
    });
};
