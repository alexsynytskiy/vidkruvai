var WrittenTasksAnswersIndex = function (options) {
    var pageOptions = $.extend(true, {}, options);

    var selectors = {
        tasks: '#written-tasks',
        reloadGrid: '.reload-grid'
    };

    $('body').on("change", selectors.reloadGrid, function (e) {
        e.preventDefault();

        $.pjax.reload({
            container: selectors.tasks,
            timeout: false,
            global: false,
            async: true
        });
    }).on("pjax:end", selectors.tasks, function (e) {
        e.preventDefault();

        var $el = $(selectors.reloadGrid), // your input id for the HTML select input
            settings = $el.attr('data-krajee-select2');
        settings = window[settings];

        $el.select2(settings);

        $('.kv-plugin-loading').hide();
    });
};
