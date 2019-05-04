var RatingPage = function (options) {
    var pageOptions = $.extend(true, {
        mapUrl: '',
        stateData: {},
        marksJS: {},
        cityProgressUrl: '',
        stateProgressUrl: ''
    }, options);

    var selectors = {
        marker: '.team-name',
        cityStore: '.store-main',
        lockPage: '.load-blocker',
        progressBlock: '.cities-main',
        stateRating: '#state-rating',
        statesRatingBlock: '.states-main'
    };

    $('body').on("click", selectors.marker, function (e) {
        e.preventDefault();

        $(selectors.lockPage).show();
        var $body = $('body');
        this.blur();

        $.post(
            pageOptions.cityProgressUrl,
            {
                cityId: $(this).data('city-id'),
                teamId: $(this).data('team-id'),
                _csrf: SiteCore.getCsrfToken()
            },
            function (response) {
                $(selectors.lockPage).hide();

                if (typeof response.progress !== 'undefined') {
                    var $statesRatingView = $body.find(selectors.statesRatingBlock);
                    $statesRatingView.empty();

                    var $cityProgressView = $body.find(selectors.progressBlock);
                    $cityProgressView.empty();
                    $cityProgressView.append(response.progress);

                    var screenW = $(document).width();
                    SiteCore.windowSize(screenW);
                }
            }, 'json');
    }).on("click", selectors.stateRating, function (e) {
        e.preventDefault();

        $(selectors.lockPage).show();
        var $body = $('body');
        this.blur();

        $.post(
            pageOptions.stateProgressUrl,
            {
                stateId: $(this).attr('data-id'), _csrf: SiteCore.getCsrfToken()
            },
            function (response) {
                $(selectors.lockPage).hide();

                if (typeof response.rating !== 'undefined') {
                    var $cityProgressView = $body.find(selectors.progressBlock);
                    $cityProgressView.empty();

                    var $statesRatingView = $body.find(selectors.statesRatingBlock);
                    $statesRatingView.empty();
                    $statesRatingView.append(response.rating);

                    var screenW = $(document).width();
                    SiteCore.windowSize(screenW);
                }
            }, 'json');
    });

    var map = function () {
        var defer = $.Deferred();

        $("#mapsvg").mapSvg({
            colors: {
                baseDefault: "#000000",
                background: "#fff",
                directory: "#fafafa",
                base: "#707cff",
                stroke: "#FFC7AC",
                disabled: "#000000",
                hover: -7,
                selected: "#abb2ff"
            },
            zoom: {
                on: true,
                limit: [0, 10],
                delta: 2,
                buttons: {
                    on: true,
                    location: "right"
                },
                mousewheel: true
            },
            scroll: {
                on: true,
                limit: true,
                background: false,
                spacebar: false
            },
            markerLastID: 1,
            cursor: "pointer",
            width: 2000,
            tooltipsMode: 'combined',
            source: pageOptions.mapUrl,
            title: "Ukraine",
            markers: $.parseJSON(pageOptions.marksJS),
            regions: $.parseJSON(pageOptions.stateData),

            responsive: true,
            onClick: null,
            mouseOver: null,
            mouseOut: null
        });

        setTimeout(function () {
            defer.resolve();
        }, 500);

        return defer;
    };

    var resolve = function () {
        var defer = $.Deferred();

        var screenW = $(document).width();
        SiteCore.windowSize(screenW);

        setTimeout(function () {
            defer.resolve();
        }, 2000);

        return defer;
    };

    map().then(resolve);
};
