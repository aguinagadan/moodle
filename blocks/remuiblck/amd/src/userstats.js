define(['jquery', 'core/ajax', 'core/chartjs'], function($, Ajax){
    //----------------------------------
    //- BEGIN PIE CHART - DOUGHNUT
    //----------------------------------

    var myDoughnut = null;
    var legendtemplatestr1 = "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%>";
    var legendtemplatestr2 = "<span style=\"background-color:<%=segments[i].fillColor%>\"></span>";
    var legendtemplatestr3 = "<%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>";
    var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 1,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        /*animation: {
            duration: 2000,
            easing: "easeOutBounce"
        },*/
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive,
        // if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: legendtemplatestr1 + legendtemplatestr2 + legendtemplatestr3,
        //String - A tooltip template
        tooltipTemplate: "<%=value %> <%=label%> users",

        legend: {
            display: false,
        }
    };

    function render_pie_chart(response) {

        if (myDoughnut !== null) {
            myDoughnut.destroy();
        }

        var pieChartCanvas = $("#pieChartblock").get(0).getContext("2d");

        var doughnutData = {
            labels: response.labels,
            datasets: [{
                data: response.data,
                backgroundColor: response.background_color,
                hoverBackgroundColor: response.hoverBackground_color,
            }]
        };

        myDoughnut = new Chart(pieChartCanvas, { type: 'doughnut', data: doughnutData, options: pieOptions });
    }


    function createpiechart() {
        var category_id = $('#coursecategorylistblock option:selected').data('id');
        Ajax.call([{
            methodname: 'block_remuiblck_get_enrolled_users_by_category',
            args: {
                categoryid: category_id
            }
        }])[0].done(function(response) {
            if (response === null) {
                $('canvas#pieChartblock').hide();
                $('.enroll-stats-nouserserror').hide();
                $('.chart-legend').hide();
                $('.enroll-stats-error').show();
            } else {
                $('.enroll-stats-error').hide();
                $('.enroll-stats-nouserserror').hide();
                $('.chart-legend').show();
                $('canvas#pieChartblock').show();

                $('#enrolled_users_stats_block .chart-legend').empty();
                var colors = ['#2196f3', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#ffeb3b', '#ff9800', '#f44336', '#9c27b0', '#673ab7', '#3f51b5'];
                $.each(response.labels, function (index, value) {
                    $('#enrolled_users_stats_block .chart-legend').append('<li class="list-group-item p-0 pt-1">' + value + ': <span class="badge badge-round" style="background-color:' + colors[index] + ';">' + response.data[index] + '</span></li>');
                });

                render_pie_chart(response);
            }

        }).fail(function(ex) {
            $('canvas#pieChartblock').hide();
            $('.enroll-stats-error').show();
        });
    }

    // update pie chart on category selection
    if ($('#enrolled_users_stats_block select').length) {
        $('#enrolled_users_stats_block select#coursecategorylistblock').on('change', function () {
            createpiechart();
        });
        createpiechart();
    }
    //----------------------------------
    //- END PIE CHART - DOUGHNUT
    //----------------------------------

});
