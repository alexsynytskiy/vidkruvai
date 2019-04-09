var ProgressPage = function (options) {
    var pageOptions = $.extend(true, {
        boughtItemsPerCategory: []
    }, options);

    var selectors = {};

    // $('body').on("click", '', function (e) {
    //     e.preventDefault();
    //
    // });

    var labels = [],
        values = [];

    $.each(pageOptions.boughtItemsPerCategory, function(key, value) {
        labels.push(key);
        values.push(value);
    });

    var data = {
        labels: labels,
        datasets: [{
            backgroundColor: '#ffb1c1',
            borderColor: '#ff6384',
            data: values,
            label: 'Графік прогресу в розрізі напряму розвитку'
        }]
    };

    var chartOptions = {
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
        data: data,
        options: chartOptions
    });

    var screenW = $(document).width();
    SiteCore.windowSize(screenW);
};
