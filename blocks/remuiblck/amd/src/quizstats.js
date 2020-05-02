define(['jquery', 'core/ajax', 'core/chartjs'], function($, Ajax){

    var SELECTORS = {
        CHART: "#barChart",
        CHART_AREA: '#quiz-chart-area',
        COURSE_LIST: '#quiz-course-list',
        COURSE_LIST_SELECTED: 'option:selected',
        ERROR: '.quiz-stats-error'
    };
    var barChart = null;
    var respBar;
    var getStepSize = function(datasets ) {
        let max = 0;
        let current;
        datasets.forEach(function(data, index) {
            current = Math.max.apply(Math, data.data);
            max = current > max ? current : max;
        });
        return Math.ceil(max / 20);
    };
    function createBarChart(root) {
        var course_id = $(root).find(SELECTORS.COURSE_LIST + ' ' + SELECTORS.COURSE_LIST_SELECTED).data('id');
        Ajax.call([{
            methodname: 'block_remuiblck_get_quiz_participation',
            args: {
                courseid: course_id
            }
        }])[0]
        .done(function(response) {
            if (response.datasets === undefined) {
                $(root).find(SELECTORS.CHART_AREA).hide();
                $(root).find(SELECTORS.ERROR).show();
            } else {
                if (barChart !== null) {
                    barChart.destroy();
                }
                var barcontext = $(root).find(SELECTORS.CHART).get(0).getContext("2d");
                barcontext.canvas.height = 400;

                var barData = {
                    labels: response.labels,
                    datasets: response.datasets
                };
                barChart = new Chart(barcontext, {
                    type: 'bar',
                    data: barData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: M.util.get_string('noofstudents', 'block_remuiblck')
                                },
                                ticks: {
                                    min: 0,
                                    stepSize: getStepSize(response.datasets)
                                }
                            }]
                        }
                    }
                });
            }
        })
        .fail(function(ex) {
            $(root).find(SELECTORS.CHART_AREA).hide();
            $(root).find(SELECTORS.ERROR).show();
        });
    }

    var initEvents = function(root) {
        $(root).find(SELECTORS.COURSE_LIST).on('change', function () {
            createBarChart(root);
        });
    };

    var init = function(root) {
        $(document).ready(function() {
            initEvents(root);
            if ($(root).find(SELECTORS.CHART).length) {
                createBarChart(root);
            }
        });
    }
    return {
        init: init
    };
});
