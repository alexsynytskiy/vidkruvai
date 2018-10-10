var AddSchoolPage = function (options) {
    var pageOptions = $.extend(true, {
        getStateCitiesUrl: ''
    }, options);

    var selectors = {
        cityField: '.field-addschoolform-city_id',
        citySelect: '#addschoolform-city_id',
        addNewCityLink: '#add-new-city',
        createNewCityBlock: '.create-new-city-form',
        newCityName: '#addschoolform-city_name',
        stateField: '#addschoolform-state_id'
    };

    $('body').on("change", selectors.stateField, function (e) {
        e.preventDefault();

        if($(this).val() !== null) {

            var $cityOptionsBlock = $(selectors.citySelect);

            $.ajax({
                url: pageOptions.getStateCitiesUrl,
                dataType: 'json',
                method: 'POST',
                data: {stateId: $(this).val(), _csrf: SiteCore.getCsrfToken()},
                success: function (data) {
                    if (data.status === 'success') {
                        $cityOptionsBlock.empty();

                        $cityOptionsBlock.append('<option value>Місто</option>');

                        (data.cities) && $.each(data.cities, function (index, value) {
                            var o = new Option(value, index);
                            $(o).html(value);

                            $cityOptionsBlock.append(o);
                        });

                        var $el = $(selectors.citySelect), // your input id for the HTML select input
                            settings = $el.attr('data-krajee-select2');
                        settings = window[settings];

                        $el.select2(settings);

                        $(selectors.cityField).show();
                        $(selectors.addNewCityLink).removeClass('hidden-link');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    return false;
                }
            });
        }
        {
            $(selectors.cityField).hide();
            $(selectors.addNewCityLink).addClass('hidden-link');
        }
    }).on("change", selectors.citySelect, function (e) {
        e.preventDefault();

        if($(this).val() !== null) {
            $(selectors.newCityName).val('');
            $(selectors.createNewCityBlock).hide();
        }
        else {
            $(selectors.addNewCityLink).show();
        }
    }).on("click", selectors.addNewCityLink, function (e) {
        e.preventDefault();

        $(selectors.createNewCityBlock).toggle();

        if($(selectors.citySelect).val()) {
            $(selectors.stateField).trigger("change");
        }
    });
};
