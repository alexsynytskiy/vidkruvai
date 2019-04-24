var ProfilePage = function (options) {
    var pageOptions = $.extend(true, {
        executedTasksData : []
    }, options);

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

    var labelsLine = [],
        valuesLine = [];

    $.each(pageOptions.executedTasksData, function(key, value) {
        labelsLine.push(value.month);
        valuesLine.push(value.value);
    });

    new Chart('chart-1', {
        type: 'line',
        data: {
            labels: labelsLine,
            datasets: [{
                backgroundColor: '#f7e5b8',
                borderColor: '#ffcd56',
                data: valuesLine,
                label: 'Виконані завдання за місяцями',
                fill: 'fill'
            }]
        },
        scale: {
            ticks: {
                min: 0,
                max: 5,
                beginAtZero: true
            }
        }
    });

    var screenW = $(document).width();
    SiteCore.windowSize(screenW);
};
