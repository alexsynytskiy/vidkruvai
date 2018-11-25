var Tasks = function () {
    "use strict";

    /**
     * @returns {boolean}
     */
    var tasksPage = function () {
        var handleTasksChangeStatus = function () {
            $('body').on('click', '.read-tasks', function (e) {
                    e.preventDefault();

                    var tasksId = $(this).parent('div').data('task-id');

                    var $readLabel = $(this).parent().parent().find('.task-new');
                    $readLabel.remove();

                    if (typeof tasksId === 'undefined') {
                        return false;
                    }

                    _mark([tasksId], 'read', 0);
                    $(this).remove();
                })
                .on('click', '#mark-all-tasks-as-read', function () {
                    if ($(this).hasClass('disabled')) {
                        return false;
                    }

                    swal({
                        text: SiteCore.t('tasks.markAllConfirm'),
                        type: 'question',
                        showCancelButton: true,
                        cancelButtonText: SiteCore.t('cancel')
                    }).then(function (result) {
                        if (result.value) {
                            _mark([], null, 1).then(function () {
                                $('.read-tasks').remove();
                                $('.task-new').remove();
                                updateTasksCounters();

                                new PNotify({
                                    title: 'Успіх!',
                                    text: SiteCore.t('tasks.allHaveBeenRead'),
                                    icon: '',
                                    type: 'success',
                                    delay: 3000 //Show the notification 4sec
                                });

                                $('#mark-all-tasks-as-read').hide();
                            });
                        }
                    });
                }).on('click', '#load-more-tasks', function (e) {
                e.preventDefault();

                const $loadMore = $(this),
                    lastId = parseInt($loadMore.attr('data-last-id'));

                $.post(
                    '/tasks/load-more/',
                    {
                        lastId: lastId, _csrf: SiteCore.getCsrfToken()
                    },
                    function (response) {
                        if (typeof response.items !== 'undefined') {
                            $('#tasks-list').append(response.items);

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
                    }, 'json');

                $loadMore.blur();
            });

            function _mark(tasksIds, tasksStatus, markAll) {
                return markTasks(tasksIds, tasksStatus, markAll)
                    .then(function () {
                        ObserverList.notify(ObserverList.EVENT_ON_NEWS_STATUS_CHANGE);
                        ObserverList.notify(ObserverList.EVENT_ON_NEWS_AFTER_STATUS_CHANGE);
                    });
            }
        };

        return {
            init: function () {
                ObserverList.subscribe(ObserverList.EVENT_ON_NEWS_STATUS_CHANGE, refreshToolbarTasks);
                ObserverList.subscribe(ObserverList.EVENT_ON_NEWS_BEFORE_STATUS_CHANGE, blockActionButtons, [true]);
                ObserverList.subscribe(ObserverList.EVENT_ON_NEWS_AFTER_STATUS_CHANGE, blockActionButtons, [false]);
                handleTasksChangeStatus();
            }
        };
    };

    var markTasks = function (tasksIds, tasksStatus, markAll) {
        markAll = markAll || 0;

        return $.ajax({
            url: '/tasks/mark/',
            dataType: 'json',
            method: 'POST',
            data: {ids: tasksIds, status: tasksStatus, mark_all: markAll, _csrf: SiteCore.getCsrfToken()},
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

    var updateTasksCounters = function (counters) {
        var total = counters,
            isTotalPositive = parseInt(total) > 0;

        if (isTotalPositive === true) {
            $('.tasks-unread').text(total.toString());
        }
        else {
            $('.tasks-unread').remove();
        }
    };

    var blockActionButtons = function (mode) {
        var tasksPage = $('.tasks-page'),
            $markAllBtn = tasksPage.find('#mark-all-tasks-as-read');

        if (mode) {
            $markAllBtn.addClass('disabled');
        } else {
            $markAllBtn.removeClass('disabled');
        }
    };

    var refreshToolbarTasks = function () {
        $.get('/tasks/counter/', function (result) {
            updateTasksCounters(result.counters);
        }, 'json');
    };

    return {
        TasksPage: tasksPage
    };
}();