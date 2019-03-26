var TeamsForm = function (options) {
    var pageOptions = $.extend(true, {}, options);

    var selectors = {
        teams: '#teams'
    };

    $('body').on("click", '.send-invitation-again', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/admin/teams/a/send-invitation-again/',
            method: 'POST',
            data: {hash: $(this).data('hash'), _csrf: TeamsCore.getCsrfToken()},
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