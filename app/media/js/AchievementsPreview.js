var AchievementsPreview = function(options) {
    var achievementList    = '.progress-items',
        hiddenFormSelector = '#selected-achievements-filters',
        pageURL            = window.location.pathname;

    $('body').on('click', '#notification-filter-status .dropdown-menu a', function (e) {
        e.preventDefault();

        $(hiddenFormSelector + ' #type').remove();

        $('<input>').attr({
            type: 'hidden',
            name: 'AchievementSearch[filterAchievementType]',
            id: 'type',
            value: $(this).data('status')
        }).appendTo(hiddenFormSelector);

        var url = pageURL + "?" + $(hiddenFormSelector).serialize();

        history.replaceState(null, null, url);

        $.get(url, function(html) {
            $(achievementList).html($(html).find(achievementList).html());

            SiteCore.initKnobDial();
        });
    })
        .on('click', 'a.show-full-group', function (e) {
            e.preventDefault();

            var id = $(this).attr('id');

            $(this).hide();

            $('#' + id + '-preview').toggleClass('hidden');
            $('#' + id + '-full').toggleClass('hidden');
        });
};