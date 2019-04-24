var ProgressPage = function (options) {
    var pageOptions = $.extend(true, {
        boughtItemsPerCategory: []
    }, options);

    var selectors = {};

    // $('body').on("click", '', function (e) {
    //     e.preventDefault();
    //
    // });

    var labelsRadar = [],
        valuesRadar = [];

    $.each(pageOptions.boughtItemsPerCategory, function(key, value) {
        labelsRadar.push(key);
        valuesRadar.push(value);
    });

    var dataRadar = {
        labels: labelsRadar,
        datasets: [{
            backgroundColor: '#ffb1c1',
            borderColor: '#ff6384',
            data: valuesRadar,
            label: 'Графік впливу придбань на розвиток школи та міста за напрямами'
        }]
    };

    var chartOptionsRadar = {
        maintainAspectRatio: true,
        spanGaps: false,
        legend: {
            position: 'bottom'
        },
        elements: {
            line: {
                tension: 0.000001
            }
        },
        plugins: {
            filler: {
                propagate: false
            },
            'samples-filler-analyser': {
                target: 'chart-analyser'
            }
        },
        scale: {
            ticks: {
                min: 0,
                max: 10,
                beginAtZero: true
            }
        }
    };

    new Chart('chart-0', {
        type: 'radar',
        data: dataRadar,
        options: chartOptionsRadar
    });

    var screenW = $(document).width();
    SiteCore.windowSize(screenW);
};
