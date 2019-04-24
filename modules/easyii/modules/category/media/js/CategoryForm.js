var CategoryForm = function (options) {
    var pageOptions = $.extend(true, {}, options);

    var selectors = {
        category: '#categories',
        reloadGrid: '.reload-grid'
    };

    $('body').on("change", selectors.reloadGrid, function (e) {
        e.preventDefault();

        $.pjax.reload({
            container: selectors.category,
            timeout: false,
            global: false,
            async: true
        });
    }).on("pjax:end", selectors.category, function (e) {
        e.preventDefault();

        var $el = $(selectors.reloadGrid), // your input id for the HTML select input
            settings = $el.attr('data-krajee-select2');
        settings = window[settings];

        $el.select2(settings);

        $('.kv-plugin-loading').hide();

        $('.switch').switcher({copy: {en: {yes: '', no: ''}}}).on('change', function () {
            var checkbox = $(this);
            checkbox.switcher('setDisabled', true);

            $.getJSON(checkbox.data('link') + '/' + (checkbox.is(':checked') ? 'on' : 'off') + '/' + checkbox.data('id'), function (response) {
                if (response.result === 'error') {
                    alert(response.error);
                }
                if (checkbox.data('reload')) {
                    location.reload();
                } else {
                    checkbox.switcher('setDisabled', false);
                }
            });
        });
    });
};
