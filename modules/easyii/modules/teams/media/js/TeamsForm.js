var TeamsForm = function (options) {
    var pageOptions = $.extend(true, {
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
    });
};
