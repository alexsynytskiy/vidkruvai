var StorePage = function (options) {
    var pageOptions = $.extend(true, {
        elementsUrl: '',
        modalPrepareUrl: '',
        buyUrl: '',
        rulesReadUrl: ''
    }, options);

    var selectors = {
        buy: '.buy-item',
        confirmFirst: '#buy-question',
        lockPage: '.load-blocker',
        sellItem: '.sell-item-wrapper',
        rulesReadId: '#rules-store-ready',
        schoolStoreBlock: '#school-store',
        rulesStoreNotificationBlock: '#rules-store-notification'
    };

    $('body').on("click", selectors.buy, function (e) {
        e.preventDefault();
        $(selectors.lockPage).show();
        $('body').css('overflow', 'hidden');
        this.blur();

        var itemId = parseInt($(this).attr('data-id'));

        $.post(
            pageOptions.modalPrepareUrl,
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
    }).on("click", selectors.rulesReadId, function (e) {
        e.preventDefault();
        $(selectors.lockPage).show();

        $.post(
            pageOptions.rulesReadUrl,
            {
                _csrf: SiteCore.getCsrfToken()
            },
            function (response) {
                if (typeof response.status !== 'undefined') {
                    $(selectors.lockPage).hide();
                    $(selectors.rulesStoreNotificationBlock).hide();
                    $(selectors.schoolStoreBlock).append(response.categories);

                    var screenW = $(document).width();
                    SiteCore.windowSize(screenW);
                }
            }, 'json');
    }).on("click", selectors.confirmFirst, function (e) {
        e.preventDefault();

        var $modal = $(this).closest(selectors.sellItem);
        $modal.find('.description.short, .category, .level').hide();
        $modal.find('.sub-name.info').show();

        $modal.find(selectors.confirmFirst).html('Так, купити за ' + $(this).data('cost') + ' балів<i class="fa fa-angle-right"\n' +
            '                                                                                     aria-hidden="true"></i>');
        $modal.find(selectors.confirmFirst).addClass('first-confirmed');
    }).on("click", selectors.confirmFirst + '.first-confirmed', function (e) {
        e.preventDefault();

        var itemId = parseInt($(this).attr('data-id'));
        var $modal = $(this).closest(selectors.sellItem);

        $.post(
            pageOptions.buyUrl,
            {
                itemId: itemId, _csrf: SiteCore.getCsrfToken()
            },
            function (response) {
                if (typeof response.status !== 'undefined') {
                    var $soldItem = $('body').find('.buy-item[data-id="' + itemId + '"]');

                    if(response.status === 'success') {
                        $modal.find('.description').hide();
                        $modal.find('.header').text('Ви купили:');
                        $modal.find('.selected-item').addClass('bought');
                        $modal.find('.name').text('Вітаємо!');
                        $modal.find('.sub-name.info').hide();
                        $modal.find('.sub-name.finish').show();

                        $modal.find(selectors.confirmFirst).html('Прогрес <i class="fa fa-angle-right" aria-hidden="true"></i>');
                        $modal.find(selectors.confirmFirst).parent().attr('href', pageOptions.elementsUrl);
                        $modal.find(selectors.confirmFirst).removeAttr('id');
                    }

                    if(response.status === 'success' || (response.status === 'error' && response.subStatus === 'already-bought')) {
                        $soldItem.closest('.item').addClass('bought');
                        $soldItem.empty();

                        $('#' + response.categorySlug + '-text').text(response.categoryBoughtElements + '/' + response.categoryAllElements);
                        $('#' + response.categorySlug + '-chart').css('width', (response.categoryBoughtElements * 100) / response.categoryAllElements + '%');

                        var $currentLevel = $soldItem.closest('.level');
                        $currentLevel.find('.item').remove();
                        $currentLevel.append(response.currentLevelElements);

                        if(response.openNextLevel) {
                            var $nextLevel = $currentLevel.next();
                            $nextLevel.find('.item').remove();
                            $nextLevel.append(response.nextLevelElements);
                        }
                    }

                    if(response.status === 'error' && response.subStatus === 'already-bought') {
                        $.modal.close();
                    }
                }
                else {
                    $.modal.close();
                }

                new PNotify({
                    title: response.status === 'error' ? 'Помилка!' : 'Успіх!',
                    text: response.message,
                    icon: '',
                    type: response.status,
                    delay: 2000
                });
            }, 'json');
    });
};
