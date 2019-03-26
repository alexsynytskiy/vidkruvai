var News = function () {
    "use strict";

    /**
     * @returns {boolean}
     */
    var newsPage = function () {
        var lazyLoadInstance = new LazyLoad({
            elements_selector: ".lazy"
        });

        var handleNewsChangeStatus = function () {
            $('body')
                .on('click', '.read-news', function (e) {
                    e.preventDefault();

                    var newsId = $(this).parent('div').data('news-id');

                    if (typeof newsId === 'undefined') {
                        return false;
                    }

                    _mark([newsId], 'read', 0);
                    $(this).remove();
                })
                .on('click', '#mark-all-news-as-read', function () {
                    if ($(this).hasClass('disabled')) {
                        return false;
                    }

                    swal({
                        text: SiteCore.t('news.markAllConfirm'),
                        type: 'question',
                        showCancelButton: true,
                        cancelButtonText: SiteCore.t('cancel')
                    }).then(function (result) {
                        if (result.value) {
                            _mark([], null, 1).then(function () {
                                $('.read-news').remove();
                                updateNewsCounters();

                                new PNotify({
                                    title: 'Успіх!',
                                    text: SiteCore.t('news.allHaveBeenRead'),
                                    icon: '',
                                    type: 'success',
                                    delay: 3000 //Show the notification 4sec
                                });

                                $('#mark-all-news-as-read').hide();
                            });
                        }
                    });
                }).on('click', '#load-more-news', function (e) {
                e.preventDefault();

                const $loadMore = $(this),
                    lastId = parseInt($loadMore.attr('data-last-id'));

                $.post(
                    '/news/load-more/',
                    {
                        lastId: lastId, _csrf: SiteCore.getCsrfToken()
                    },
                    function (response) {
                        if (typeof response.items !== 'undefined') {
                            $('#news-list').append(response.items);

                            var screenW = $(document).width();
                            SiteCore.windowSize(screenW);
                        }

                        if (response.items !== 'undefined' && response.items) {
                            if (response.hasToLoadMore === true) {
                                $loadMore.attr('data-last-id', response.lastItemId);
                            }
                            else {
                                $loadMore.remove();
                            }
                        }
                        else {
                            $loadMore.remove();
                        }
                    }, 'json').then(function () {
                    var lazyLoadInstance = new LazyLoad({
                        elements_selector: ".lazy"
                    });
                });

                $loadMore.blur();
                $(window).trigger('resize');
            });

            function _mark(newsIds, newsStatus, markAll) {
                return markNews(newsIds, newsStatus, markAll)
                    .then(function () {
                        ObserverList.notify(ObserverList.EVENT_ON_NEWS_STATUS_CHANGE);
                        ObserverList.notify(ObserverList.EVENT_ON_NEWS_AFTER_STATUS_CHANGE);
                    });
            }
        };

        return {
            init: function () {
                ObserverList.subscribe(ObserverList.EVENT_ON_NEWS_STATUS_CHANGE, refreshToolbarNews);
                ObserverList.subscribe(ObserverList.EVENT_ON_NEWS_BEFORE_STATUS_CHANGE, blockActionButtons, [true]);
                ObserverList.subscribe(ObserverList.EVENT_ON_NEWS_AFTER_STATUS_CHANGE, blockActionButtons, [false]);
                handleNewsChangeStatus();
            }
        };
    };

    var markNews = function (newsIds, newsStatus, markAll) {
        markAll = markAll || 0;

        return $.ajax({
            url: '/news/mark/',
            dataType: 'json',
            method: 'POST',
            data: {ids: newsIds, status: newsStatus, mark_all: markAll, _csrf: SiteCore.getCsrfToken()},
            beforeSend: function () {
                ObserverList.notify(ObserverList.EVENT_ON_NEWS_BEFORE_STATUS_CHANGE);
            },
            success: function (data) {
                return true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                return false;
            }
        });
    };

    var updateNewsCounters = function (counters) {
        var total = counters,
            isTotalPositive = parseInt(total) > 0;

        if (isTotalPositive === true) {
            $('.news-unread').text(total.toString());
        }
        else {
            $('.news-unread').remove();
        }
    };

    var blockActionButtons = function (mode) {
        var $newsPage = $('.news-page'),
            $markAllBtn = $newsPage.find('#mark-all-news-as-read');

        if (mode) {
            $markAllBtn.addClass('disabled');
        } else {
            $markAllBtn.removeClass('disabled');
        }
    };

    var refreshToolbarNews = function () {
        $.get('/news/counter/', function (result) {
            updateNewsCounters(result.counters);
        }, 'json');
    };

    return {
        NewsPage: newsPage
    };
}();