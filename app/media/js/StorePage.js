var StorePage = function (options) {
    var pageOptions = $.extend(true, {
        elementsUrl: ''
    }, options);

    var selectors = {
        buy: '.buy-item',
        confirmFirst: '#buy-question',
        lockPage: '.load-blocker'
    };

    $('body').on("click", selectors.buy, function (e) {
        e.preventDefault();
        $(selectors.lockPage).show();
        $('body').css('overflow', 'hidden');
        this.blur();

        var itemId = parseInt($(this).attr('data-id'));

        $.post(
            '/store/modal-prepare/',
            {
                itemId: itemId, _csrf: SiteCore.getCsrfToken()
            },
            function (response) {
                $(selectors.lockPage).hide();
                $('body').css('overflow', 'none');
                if (typeof response.modalContent !== 'undefined') {
                    $(response.modalContent).appendTo('body').modal();
                }
            }, 'json');
    }).on("click", selectors.confirmFirst, function (e) {
        e.preventDefault();

        var $modal = $(this).closest('.sell-item-wrapper');
        $modal.find('.description.short, .category, .level').hide();
        $modal.find('.sub-name.info').show();

        $modal.find(selectors.confirmFirst).html('Так, купити за ' + $(this).data('cost') + ' балів<i class="fa fa-angle-right"\n' +
            '                                                                                     aria-hidden="true"></i>');
        $modal.find(selectors.confirmFirst).addClass('first-confirmed');
    }).on("click", selectors.confirmFirst + '.first-confirmed', function (e) {
        e.preventDefault();

        var itemId = parseInt($(this).attr('data-id'));

        $.post(
            '/store/buy/',
            {
                itemId: itemId, _csrf: SiteCore.getCsrfToken()
            },
            function (response) {
                if (typeof response.status !== 'undefined') {

                }
            }, 'json');

        var $modal = $(this).closest('.sell-item-wrapper');
        $modal.find('.description').hide();
        $modal.find('.header').text('Ви купили:');
        $modal.find('.selected-item').addClass('bought');
        $modal.find('.name').text('Вітаємо!');
        $modal.find('.sub-name.info').hide();
        $modal.find('.sub-name.finish').show();

        $modal.find(selectors.confirmFirst).html('Мої елементи <i class="fa fa-angle-right" aria-hidden="true"></i>');
        $modal.find(selectors.confirmFirst).parent().attr('href', pageOptions.elementsUrl);
        $modal.find(selectors.confirmFirst).addClass('success-bought');
    });
};
