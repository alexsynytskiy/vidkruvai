var Achievements = function(options) {
    var achievementList = '#achievement-list';

    $('body').on('click', '#notification-filter-status .dropdown-menu a', function (e) {
        e.preventDefault();

        $('#achievementsearch-filterachievementtype').val($(this).data('status'));

        $(this).closest('form').submit();
    });

    $("#achievement-filters").on("pjax:end", function(e) {
        e.preventDefault();
        $.pjax.reload({
            container: achievementList
        });
    });

    $(achievementList).on("pjax:end", function(e) {
        e.preventDefault();
        SiteCore.initKnobDial();
    });
};