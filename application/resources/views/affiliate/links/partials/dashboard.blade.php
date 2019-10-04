@can('features.link-shortener.dashboard')
    @empty($series)
    @card
        <div class="py-5">
            <p class="lead text-muted text-center">
                Sie haben noch keine Klicks generiert, die grafisch dargestellt werden können.
            </p>

            <ol class="w-50 mx-auto mb-0">
                <li>Teilen sie die unten stehenden Links zB. auf ihren Seiten, E-Mails oder auf sozialen Netzwerken</li>
                <li>Interessenten klicken auf die Links und kommen auf Exporo</li>
                <li>Sie sehen grafisch, welcher Link wie gut ankommt</li>
            </ol>

            @can('features.link-shortener.links')
                <div class="mt-4 text-center">
                    <strong>
                        Sofern sie bereits Links geteilt haben, müssen diese mit den unten stehenden ersetzt werden.
                    </strong>
                </div>
            @endcan
        </div>
    @endcard
    @else
    @card
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <div class="nav-link disabled">
                    Klicks nach:
                </div>
            </li>
            @foreach($types as $type)
            <li class="nav-item">
                <a class="nav-link @if($type['isActive'])active @endif" href="{{ $type['url'] }}">
                    {{ $type['label'] }}
                </a>
            </li>
            @endforeach
        </ul>

        <div id="link-clicks-container" class="w-100" style="height:400px;"></div>
    @endcard

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>(function () {
        // Fill up empty time series data with zeros
        // source: https://jsfiddle.net/spd1tswo/2/ (via https://stackoverflow.com/a/46113343/1397894)
        var series = @json($series);
        var dayTick = 1000 * 60 * 60 * 24;

        // Find highest end date
        var startDay = Number.MAX_SAFE_INTEGER;
        var endDay = Number.MIN_SAFE_INTEGER;

        for (var d = 0; d < series.length; d++) {
            var data = series[d].data;

            var start = data[0][0];
            if (start < startDay) startDay = start;

            var end = data[data.length-1][0];
            if (end > endDay) endDay = end;
        }

        // Fill all dates without data up with zeroes
        for (d = 0; d < series.length; d++) {
            data = series[d].data;
            var isLastDay = startDay === endDay,
                localStart = startDay,
                temp = [];

            temp.push([localStart, 0]);

            while(!isLastDay) {
                localStart += dayTick;
                temp.push([localStart, 0]);

                isLastDay = localStart >= endDay;
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
    })();</script>
    @endempty
@endcan
