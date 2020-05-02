define([
    'jquery',
    'core/ajax',
    'core/chartjs',
    'core/custom_interaction_events',
    'block_remuiblck/events'
], function ($, Ajax, Chart, CustomEvents, RemuiblckEvents) {

    var SELECTORS = {
        PAGECOUNT: '[data-action-page-count]',
        PAGINATE: '[data-action-paginate]',
        NEXT: '[data-next]',
        PREVIOUS: '[data-previous]',
        CHARTPAGINATION: '.analysis-chart-pagination',
        PAGES: '[data-region-pages]',
        TOGGLELABELS: '#togglelabels',
        PER_PAGE_FILTER: '[data-region="per-page-filter"]',
        FILTER_OPTION: '[data-value]'
    };
    window['analysisChart'] = null;
    var pageNumber = 1;
    var maxPage = 1;
    /* Course Analytics Block */
    var analysisBar;

    /**
     * Get start and end index of bar data
     *
     * @param  {Number} perPage Total number of bars per page
     * @return {Object}         Object with start and end value of bars
     */
    function getStartEnd(perPage) {
        // default limit is 0
        var limit = {
            start: 0,
            end: 0
        };
        if (analysisBar.labels == undefined) {
            return limit;
        }
        // if number of bars is less or equal to per page then return limit with 1 and bars count
        if (analysisBar.labels.length <= perPage) {
            limit.start = 1;
            limit.end = analysisBar.labels.length;
            return limit;
        }
        limit.start = pageNumber == 1 ? 1 : (pageNumber - 1) * perPage + 1;
        limit.end = pageNumber * perPage;
        if (analysisBar.labels.length < limit.end) {
            limit.end = analysisBar.labels.length;
        }
        return limit;
    }

    /**
     * Update bars data in the chart based on start and end of bars
     *
     * @param {String} root      block root element id
     * @param {Number} totalBars Total bar which can be shown in the chart
     */
    function updateBars(root, totalBars) {
        // var {start, end} = getStartEnd(totalBars);
        var limit = getStartEnd(totalBars);
        // Update pagination label
        $(root).find(SELECTORS.PAGES).text(M.util.get_string('showingfromto', 'block_remuiblck', {
            start: limit.start,
            to: limit.end,
            total: analysisBar.labels.length
        }));

        // Remove previous bar data
        analysisChart.data.labels = [];
        analysisChart.data.datasets.forEach(function(dataset) {
            dataset.data = [];
        });
        analysisChart.update();

        // Add new bar data and re-render the chart
        for (var i = limit.start - 1; i <= limit.end; i++) {
            analysisChart.data.labels.push(analysisBar.labels[i]);
            analysisChart.data.datasets.forEach(function(dataset, index) {
                dataset.data.push(analysisBar.datasets[index].data[i]);
            });
            analysisChart.update();
        }
    }

    /**
     * Generate pagination data on page load
     *
     * @param {String} root    block root element id
     * @param {Number} perpage bars to be shown per page
     */
    function generatePagination(root, perPage) {
        // If there is no data then hide pagination and return
        if (analysisBar.labels == undefined) {
            $(root).find(SELECTORS.CHARTPAGINATION).addClass('d-none');
            return;
        }
        pageNumber = 1;
        // var {start, end} = getStartEnd(perPage);

        // Show pagination
        $(root).find(SELECTORS.CHARTPAGINATION).removeClass('d-none');
        maxPage = analysisBar.labels.length > perPage ? Math.round(analysisBar.labels.length / perPage) : 1;
        updateBars(root, perPage);
    }

    /**
     * Get Analysis Data using ajax
     *
     * @param  {String} root block root element id
     * @return {Ajax}   Ajax promise
     */
    function getAnalysisData(root) {
        var course_id = $(root).find('#coursecategorylist option:selected').data('id');
        return Ajax.call([{
            methodname: 'block_remuiblck_get_course_analytics',
            args: {
                courseid: course_id
            }
        }])[0];
    }

    /**
     * Create analysis chart from ajax data
     *
     * @param {String} root block root element id
     */
    function createAnalysisChart(root) {
        getAnalysisData(root)
        .done(function (response) {
            analysisBar = response;
            pageNumber = 1;
            if (analysisChart !== null) {
                analysisChart.destroy();
            }

            if (response.error) {
                $(root).find("#highestactivity").html("");
                $(root).find("#lowestactivity").html("");

                $(root).find("#highestgrade").html("0");
                $(root).find("#lowestgrade").html("0");
                $(root).find("#averagegrade").html("0");
            } else {
                $(root).find("#highestactivity").html(analysisBar.maxactivityname);
                $(root).find("#lowestactivity").html(analysisBar.minactivityname);

                $(root).find("#highestgrade").html(analysisBar.highest);
                $(root).find("#lowestgrade").html(analysisBar.lowest);
                $(root).find("#averagegrade").html(analysisBar.average);
            }

            var context = $(root).find("#analysischart").get(0).getContext("2d");
            var datasets = [];
            if (response.datasets != undefined) {
                response.datasets.forEach(function(dataset) {
                    datasets.push({
                        data: [],
                        backgroundColor: dataset.backgroundColor,
                        label: dataset.label
                    });
                });
            }
            context.canvas.height = 400;
            analysisChart = new Chart(context, {
                data: {
                    labels: [],
                    datasets: datasets
                },
                type: 'bar',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true
                    },
                    hover: {
                        animationDuration: 0
                    },
                    layout: {
                        padding: {
                            top: 20
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            fontSize: 12
                        }

                    },
                    animation: {
                        duration: 300,
                        easing: 'easeInOutQuad',
                        onComplete: function () {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;
                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                if (meta.hidden != true) {
                                    meta.data.forEach(function (bar, index) {
                                        var data = dataset.data[index];
                                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                    });
                                }
                            });
                        }
                    },
                    scales:
                    {
                        xAxes: [{
                            display: false,
                            gridLines: {
                                display: true,
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            },
                            gridLines: {
                                display: true
                            },
                        }]
                    }
                }
            });
            generatePagination(root, getCourseAnalyticsPerPage(root));
        })
        .fail(function (xhr, status, error) {
            $(root).find('div#analysis-chart-area').hide();
        });
    }

    /**
     * Event listener for the per page selector
     *
     * @param {object} root The root element for the manage courses block
     */
    var registerCourseAnalyticsPerPageFilter = function(root) {
        var courseAnalyticsPerPageFilterContainer = $(root).find(SELECTORS.PER_PAGE_FILTER);
        CustomEvents.define(courseAnalyticsPerPageFilterContainer, [CustomEvents.events.activate]);
        courseAnalyticsPerPageFilterContainer.on(
            CustomEvents.events.activate,
            SELECTORS.FILTER_OPTION,
            function(e, data) {
                data.originalEvent.preventDefault();

                var option = $(e.target).closest(SELECTORS.FILTER_OPTION);

                if (option.hasClass('active')) {
                    // If it's already active then we don't need to do anything.
                    return;
                }

                $(e.target).trigger(RemuiblckEvents.COURSE_ANALYTICS_PAGE_FILTER_CHANGE);
                M.util.set_user_preference('courseanalyticsperpage', option.data('value'));
                generatePagination(root, option.data('value'));
            }
        );
    };

    /**
     * Get manage courses filter dropdown selection
     *
     * @param  DOM    root block root element id
     * @return string      selected per page courses
     */
    var getCourseAnalyticsPerPage = function(root) {
        return $(root).find(SELECTORS.PER_PAGE_FILTER).find(SELECTORS.FILTER_OPTION + '.active').data('value');
    }

    /**
     * Initialize event listerns
     *
     * @param  {String} root block root element id
     */
    function initEvents(root) {
        // Initialize per page filter events
        registerCourseAnalyticsPerPageFilter(root);

        // Traverse through page
        $('body').on('click', root + ' ' + SELECTORS.PAGINATE, function() {
            if ($(this).is(SELECTORS.NEXT)) {
                if (pageNumber == maxPage) {
                    return;
                }
                pageNumber++;
            } else if($(this).is(SELECTORS.PREVIOUS)) {
                if (pageNumber == 1) {
                    return;
                }
                pageNumber--;
            } else {
                return;
            }
            var perPage = getCourseAnalyticsPerPage(root);
            updateBars(root, perPage);
        });

        //Update chart on courses dropdown change
        $('body').on('change', root + ' #coursecategorylist', function () {
            createAnalysisChart(root);
        });
    }

    /**
     * Main method to be initialised for course analytics block
     *
     * @param  {String} root block root element id
     */
    var init = function(root) {
        $(document).ready(function() {
            initEvents(root);
            if ($(root).find('#analysischart').length) {
                createAnalysisChart(root);
            }
        });
    };

    return {
        init: init
    }

});
