var Achievements = function (options) {
    var pageURL = window.location.pathname,
        selectors = {
        achievementList: '.progress-items',
        hiddenFormSelector: '#selected-achievements-filters',
        filterButton: '#filter-btn-title',
        filterItem: '#notification-filter-status li'
    };

    $('body').on('click', '#notification-filter-status .dropdown-menu a', function (e) {
        e.preventDefault();

        $(selectors.hiddenFormSelector + ' #type').remove();

        $('<input>').attr({
            type: 'hidden',
            name: 'AchievementSearch[filterAchievementType]',
            id: 'type',
            value: $(this).data('status')
        }).appendTo(selectors.hiddenFormSelector);

        var url = pageURL + "?" + $(selectors.hiddenFormSelector).serialize();

        history.replaceState(null, null, url);

        $.when(
            $.get(url, function (html) {
                $(selectors.achievementList).html($(html).find(selectors.achievementList).html());
            })
        ).then(function (data, textStatus, jqXHR) {
            SiteCore.initKnobDial();

            var screenW = $(document).width();
            SiteCore.windowSize(screenW);
        });

        $(selectors.filterButton).text($(this).text());

        var $items = $(selectors.filterItem);

        ($items) && $.each($items, function (index, value) {
            if ($(value).hasClass('active')) {
                $(value).removeClass('active');
            }
        });

        $(this).parent().addClass('active');
    })
        .on('click', 'a.show-full-group', function (e) {
            e.preventDefault();

            var id = $(this).attr('id');

            $(this).hide();

            $('#' + id + '-preview').toggleClass('hidden');
            $('#' + id + '-full').toggleClass('hidden');
        });
};
