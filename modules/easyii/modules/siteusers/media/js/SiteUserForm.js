var SiteUserForm = function (options) {
    var pageOptions = $.extend(true, {
    }, options);

    var selectors = {
        siteUsers: '#site-users',
        reloadGrid: '.reload-grid'
    };

    $('body').on("change", selectors.reloadGrid, function (e) {
        e.preventDefault();

        $.pjax.reload({
            container: selectors.siteUsers,
            timeout : false,
            global: false,
            async: true
        });
    }).on("pjax:end", selectors.siteUsers, function (e) {
        e.preventDefault();

        var $el = $(selectors.reloadGrid), // your input id for the HTML select input
            settings = $el.attr('data-krajee-select2');
        settings = window[settings];

        $el.select2(settings);

        $('.kv-plugin-loading').hide();

        $('.switch').switcher({copy: {en: {yes: '', no: ''}}}).on('change', function(){
            var checkbox = $(this);
            checkbox.switcher('setDisabled', true);

            $.getJSON(checkbox.data('link') + '/' + (checkbox.is(':checked') ? 'on' : 'off') + '/' + checkbox.data('id'), function(response){
                if(response.result === 'error'){
                    alert(response.error);
                }
                if(checkbox.data('reload')){
                    location.reload();
                }else{
                    checkbox.switcher('setDisabled', false);
                }
            });
        });
    }).on("click", '.drop-user-password', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/admin/siteusers/a/drop-user-password/',
            method: 'POST',
            data: {userId: $(this).data('user-id'), _csrf: SiteUserCore.getCsrfToken()},
            success: function (data) {
                new PNotify({
                    title: 'Успіх!',
                    text: data.message,
                    icon: '',
                    type: 'success',
                    delay: 3000 //Show the notification 4sec
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                new PNotify({
                    title: 'Помилка!',
                    text: 'Виникла помилка!',
                    icon: '',
                    type: 'error',
                    delay: 3000 //Show the notification 4sec
                });
            }
        });
    });
};
