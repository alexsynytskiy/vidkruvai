var ProgressPage = function (options) {
    var pageOptions = $.extend(true, {
        boughtItemsPerCategory: [],
        executedTasksData : []
    }, options);

    var selectors = {};

    // $('body').on("click", '', function (e) {
    //     e.preventDefault();
    //
    // });

    var labelsRadar = [],
        valuesRadar = [],
        labelsLine = [],
        valuesLine = [];

    $.each(pageOptions.boughtItemsPerCategory, function(key, value) {
        labelsRadar.push(key);
        valuesRadar.push(value);
    });

    $.each(pageOptions.executedTasksData, function(key, value) {
        labelsLine.push(value.month);
        valuesLine.push(value.value);
    });

    var dataRadar = {
        labels: labelsRadar,
        datasets: [{
            backgroundColor: '#ffb1c1',
            borderColor: '#ff6384',
            data: valuesRadar,
            label: 'Графік прогресу в розрізі напряму розвитку'
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

    new Chart('chart-1', {
        type: 'line',
        data: {
            labels: labelsLine,
            datasets: [{
                backgroundColor: '#f7e5b8',
                borderColor: '#ffcd56',
                data: valuesLine,
                label: 'Виконані завдання за місяцями',
                fill: 'fill'
            }]
        },
        scale: {
            ticks: {
                min: 0,
                max: 5,
                beginAtZero: true
            }
        }
    });

    var screenW = $(document).width();
    SiteCore.windowSize(screenW);
};
