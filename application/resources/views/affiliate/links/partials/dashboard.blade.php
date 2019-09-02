@can('features.link-shortener.dashboard')
    @card
        <div id="link-clicks-container" class="w-100" style="height:400px;"></div>
    @endcard

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
        // Fill up empty time series data with zeros
        // source: https://jsfiddle.net/spd1tswo/2/ (via https://stackoverflow.com/a/46113343/1397894)
        var series = @json($series);

        for (var d = 0; d < series.length; d++) {
            var data = series[d].data;
            var startDay = data[0][0],
                endDay = data[data.length-1][0],
                isLastDay = startDay === endDay,
                dayTick = 1000 * 60 * 60 * 24,
                temp = [];

            temp.push([startDay, 0]);

            while(!isLastDay) {
                startDay += dayTick;
                temp.push([startDay, 0]);

                isLastDay = startDay >= endDay;
            }

            for (var i = 0; i < data.length; i++) {
                for(var j = 0; j < temp.length; j++) {
                    if (temp[j][0] === data[i][0]) {
                        temp[j][1] = data[i][1];
                        break;
                    }
                }
            }

            series[d].data = temp;
        }

        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('link-clicks-container', {
                chart: {
                    type: 'area',
                    animation: false,
                },
                title: {
                    text: undefined
                },
                xAxis: {
                    type: 'datetime',
                },
                yAxis: {
                    title: {
                        text: 'Anzahl'
                    }
                },
                plotOptions: {
                    series: {
                        animation: false,
                        connectNulls: false,
                        stacking: 'normal',
                        turboThreshold: 0,
                    },
                    area: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: series
            });
        });
    </script>
@endcan
