var TeamsIndex = function (options) {
    var pageOptions = $.extend(true, {
        removeTeamUrl: ''
    }, options);

    var selectors = {
        teams: '#teams',
        reloadGrid: '.reload-grid'
    };

    $('body').on("change", selectors.reloadGrid, function (e) {
        e.preventDefault();

        $.pjax.reload({
            container: selectors.teams,
            timeout : false,
            global: false,
            async: true
        });
    }).on("pjax:end", selectors.teams, function (e) {
        e.preventDefault();

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
    }).on("click", '.remove-team', function (e) {
        e.preventDefault();

        var id = $(this).data('id');

        swal({
            text: 'Ви впевнені що хочете видалити команду?',
            type: 'question',
            showCancelButton: true,
            cancelButtonText: 'Відмінити'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url:  pageOptions.removeTeamUrl,
                    method: 'POST',
                    cache: false,
                    data: {teamId: id, _csrf: TeamsCore.getCsrfToken()},
                    success: function (data) {
                        new PNotify({
                            title: 'Успіх!',
                            text: data.message,
                            icon: '',
                            type: data.status,
                            delay: 3000 //Show the notification 4sec
                        });

                        $.pjax.reload({
                            container: selectors.teams,
                            timeout : false,
                            global: false,
                            async: true
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        new PNotify({
                            title: 'Помилка!',
                            text: errorThrown,
                            icon: '',
                            type: 'error',
                            delay: 3000 //Show the notification 4sec
                        });
                    }
                });
            }
        });
    });
};
