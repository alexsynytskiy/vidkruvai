var Notifications = function () {
    "use strict";

    /**
     * @returns {boolean}
     */
    var reloadNotificationPage = function () {
        var pathname = window.location.pathname;

        if (!/\/notification/.test(pathname)) {
            return false;
        }

        $.get(pathname, function (html) {
            $('#full-notification-block').html($('#full-notification-block', html).html());

            $('.panel-heading .panel-title .text-light').remove();
        }, 'html');

    };

    var notificationPage = function () {
        $('body').on('click', '[data-action=reload]', function (e) {
            e.preventDefault();

            reloadNotificationPage();
        });

        $('#full-notification-block').on('pjax:compvare', function () {
            var pageNum = $('.pagination li.active a').data('page');
            $('.panel-heading .panel-title .text-light').remove();

            if (pageNum) {
                var caption = SiteCore.t('notification.pageCaption', {
                    pageNum: (pageNum + 1)
                });

                $('.panel-heading .panel-title').append('<small class="text-light">' + caption + '</small>');
            }
        });

        var handleNotificationActionButtonStatus = function (forceHide) {
            forceHide = forceHide || false;

            if ($('[data-checked=notification-checkbox]:checked').length !== 0 && !forceHide) {
                $('[data-notification-action]').removeClass('hide');
            }
            else {
                $('[data-notification-action]').addClass('hide');
            }
        };

        var handleNotificationCheckboxChecked = function () {
            $('body')
                .on('click', '[data-checked=notification-checkbox]', function (e) {
                    handleNotificationActionButtonStatus();
                })
                .on('click', '.list-group-item', function (e) {
                    var $target = $(e.target);

                    //If we click on link or checkbox, skip selection
                    if ($target.is('a') || $target.is('input[type=checkbox]')) {
                        return true;
                    }

                    var $checkbox = $(this).find('[data-checked=notification-checkbox]'),
                        checked = !$checkbox.prop('checked');

                    $checkbox.prop('checked', checked);

                    handleNotificationActionButtonStatus();
                });
        };

        var handleNotificationChangeStatus = function () {
            $('body')
                .on('click', '#list-notifications li.status-new .media-heading a', function (e) {
                    if (!$(this).hasClass('target_link')) {
                        e.preventDefault();
                    } else {
                        return true;
                    }

                    var notificationId = $(this).closest('li').data('notification-id');

                    if (typeof notificationId === 'undefined') {
                        return false;
                    }

                    $(this).removeClass('status-new');

                    _mark([notificationId], 'read', 0)
                })
                .on('click', 'a[data-notification-action]', function (e) {
                    e.preventDefault();

                    if ($(this).closest('li').hasClass('disabled')) {
                        return false;
                    }

                    var status = $(this).data('notification-action'),
                        notificationIds = [],
                        $icon = $(this).find('i'),
                        spinnerClass = 'icon-spinner2 spinner',
                        defaultIconCssClass = $icon.data('default-fa-class');

                    $('#list-notifications').find('input[type=checkbox]:checked').each(function () {
                        notificationIds.push($(this).val());
                    });

                    $icon.removeClass(defaultIconCssClass).addClass(spinnerClass);

                    if (notificationIds.length > 0) {
                        _mark(notificationIds, status, 0).then(function () {
                            showNotify(SiteCore.t('notification.statusChangeSuccessfully'));
                            $icon.addClass(defaultIconCssClass).removeClass(spinnerClass);
                        });

                    }

                })
                .on('click', '#mark-all-as-read', function () {
                    if ($(this).hasClass('disabled')) {
                        return false;
                    }

                    swal({
                        text: SiteCore.t('notification.markAllConfirm'),
                        type: 'question',
                        showCancelButton: true,
                        cancelButtonText: SiteCore.t('cancel')
                    }).then(function () {
                        _mark([], null, 1).then(function () {
                            showNotify(SiteCore.t('notification.allHaveBeenRead'));
                        });
                    }).catch(swal.noop);
                });

            function _mark(notificationIds, notificationStatus, markAll) {
                return markNotification(notificationIds, notificationStatus, markAll)
                    .then(function () {
                        ObserverList.notify(ObserverList.EVENT_ON_NOTIFICATION_STATUS_CHANGE);
                        ObserverList.notify(ObserverList.EVENT_ON_NOTIFICATION_AFTER_STATUS_CHANGE);
                    });
            }
        };

        var filterNotificationsByCategory = function () {
            ObserverList.subscribe(ObserverList.EVENT_ON_NOTIFICATION_FILTER_FIRE, reloadNotificationPage);

            $('body').on('click', '#notification-filter-type .dropdown-menu li a', function (e) {
                e.preventDefault();

                var url = $(this).attr("href"),
                    dataStatus = $(this).data('status');

                if (dataStatus !== undefined) {
                    if (dataStatus === '') {
                        return window.location.replace(url);
                    }

                    window.location.replace(url + "/" + dataStatus);
                }

                ObserverList.notify(ObserverList.EVENT_ON_NOTIFICATION_FILTER_FIRE);
            });
        };

        var filterNotificationsByStatus = function () {
            ObserverList.subscribe(ObserverList.EVENT_ON_NOTIFICATION_FILTER_FIRE, reloadNotificationPage);

            $('body').on('click', '#notification-filter-status .dropdown-menu li a', function (e) {
                e.preventDefault();

                var linkText = $(this).text(),
                    url = $(this).attr('href'),
                    $buttonTitle = $(this).closest('.dropdown').find('#filter-btn-title'),
                    $dropDownMenu = $(this).closest('.dropdown-menu'),
                    $subtitleFilter = $('#subtitle-filter-label'),
                    status = $(this).attr("id");

                $('#notification-filter-type').find('a').each(function (i) {
                    $(this).attr("data-status", status);
                });

                if ($(this).hasClass('all-list')) {
                    $subtitleFilter.text('');
                } else {
                    $subtitleFilter.text(' - ' + linkText);
                }

                $buttonTitle.text(linkText);

                $dropDownMenu.find('li').each(function (i) {
                    $(this).removeClass('active');
                });
                $(this).parent('li').addClass('active');

                history.replaceState(null, null, url);

                ObserverList.notify(ObserverList.EVENT_ON_NOTIFICATION_FILTER_FIRE);
            });
        };

        var showNotify = function (msg) {
            new PNotify({
                title: '',
                text: msg,
                type: 'success',
                delay: 2000 //Show the notification 2sec
            });
        };

        return {
            init: function () {
                ObserverList.subscribe(ObserverList.EVENT_ON_NOTIFICATION_STATUS_CHANGE, reloadNotificationPage);

                ObserverList.subscribe(ObserverList.EVENT_ON_NOTIFICATION_BEFORE_STATUS_CHANGE, blockActionButtons, [true]);

                ObserverList.subscribe(ObserverList.EVENT_ON_NOTIFICATION_AFTER_STATUS_CHANGE, blockActionButtons, [false]);
                ObserverList.subscribe(ObserverList.EVENT_ON_NOTIFICATION_AFTER_STATUS_CHANGE, handleNotificationActionButtonStatus, [true]);

                handleNotificationCheckboxChecked();
                handleNotificationChangeStatus();
                filterNotificationsByStatus();
                filterNotificationsByCategory();
            }
        };

    };

    var markNotification = function (notificationIds, notificationStatus, markAll) {
        markAll = markAll || 0;

        return $.ajax({
            url: '/notification/mark/',
            dataType: 'json',
            method: 'POST',
            data: {ids: notificationIds, status: notificationStatus, mark_all: markAll},
            beforeSend: function () {
                ObserverList.notify(ObserverList.EVENT_ON_NOTIFICATION_BEFORE_STATUS_CHANGE);
            },
            success: function (data) {
                return true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                return false;
            }
        });
    };

    var updateNotificationCounters = function (counters) {
        var total = counters.total,
            isTotalPositive = parseInt(total) > 0,
            totalNotificationsBadge = $('#top-menu-total-notifications-badge');

        $('.total-notifications').text(isTotalPositive ? total : '');

        isTotalPositive ? totalNotificationsBadge.removeClass('hide') : totalNotificationsBadge.addClass('hide');

        for (var el in counters) {
            if (el == 'total') {
                continue;
            }

            //noinspection JSUnfilteredForInLoop
            $('.total-' + el + '-notifications').text(parseInt(counters[el]) > 0 ? counters[el] : '');
        }
    };

    var blockActionButtons = function (mode) {
        var $notificationPage = $('.notification-page'),
            $notificationButtons = $notificationPage.find('[data-notification-action]').closest('li'),
            $notificationTitle = $notificationPage.find('.list-group-item.status-new .media-heading a'),
            $markAllBtn = $notificationPage.find('#mark-all-as-read').closest('.btn-group').find('> button');

        if (mode) {
            $notificationButtons.addClass('disabled');
            $notificationButtons.prop('disabled', true);

            $notificationTitle.addClass('disabled');
            $markAllBtn.addClass('disabled');

        } else {
            $notificationButtons.removeClass('disabled');
            $notificationButtons.prop('disabled', false);

            $notificationTitle.removeClass('disabled');
            $markAllBtn.removeClass('disabled');
        }
    };

    var refreshToolbarNotifications = function () {
        $.get('/notification/get-data/', function (result) {
            updateNotificationCounters(result.counters);
            $('#toolbar-list-notifications').html(result.content);
        }, 'json');
    };

    var initToolbarNotifications = function () {
        ObserverList.subscribe(ObserverList.EVENT_ON_NOTIFICATION_STATUS_CHANGE, refreshToolbarNotifications);

        $('body').on('click', '#toolbar-notifications #toolbar-list-notifications li.notification-item a', function (e) {
            var $parentLi = $(this).closest('li');

            if (!$parentLi.hasClass('target_link')) {
                e.preventDefault();
            } else {
                return true;
            }

            var notificationId = $parentLi.data('notification-id');

            if (!notificationId) {
                return false;
            }

            markNotification([notificationId], 'read')
                .then(function () {
                    ObserverList.notify(ObserverList.EVENT_ON_NOTIFICATION_STATUS_CHANGE);
                    ObserverList.notify(ObserverList.EVENT_ON_NOTIFICATION_AFTER_STATUS_CHANGE);
                });

        })
    };

    return {
        NotificationPage: notificationPage,
        InitToolbarNotifications: initToolbarNotifications
    };
}();
