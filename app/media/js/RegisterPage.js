var RegisterPage = function (options) {
    var pageOptions = $.extend(true, {
        mentorValue: ''
    }, options);

    var selectors = {
        customOption: '.custom-option',
        optionItem: '.item',
        roleInput: '#registerform-role'
    };

    var selection = {state: true};

    $('body').on("click", selectors.optionItem, function (e) {
        e.preventDefault();

        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

            selection.state = false;
        }

        if (!selection.state) {
            $(this).removeClass('selected');
            selection.state = true;
        }
        else {
            var $options = $(selectors.optionItem);

            ($options) && $.each($options, function (index, value) {
                if ($(value).hasClass('selected')) {
                    $(value).removeClass('selected');
                }
            });

            $(this).addClass('selected');

            $(selectors.roleInput).val($(this).data('value'));
        }

        if($(this).data('value') === pageOptions.mentorValue) {
            $('.field-registerform-class').hide();
        }
        else {
            $('.field-registerform-class').show();
        }
    });
};
