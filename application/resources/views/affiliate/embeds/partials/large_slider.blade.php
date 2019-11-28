<div class="slider">
    @foreach($projects as $project)
    @php
        $remaining = $project['funding_target'] - $project['funding_current_sum_invested'];
        $invested = $project['funding_current_sum_invested'] / $project['funding_target'] * 100;
        $remainingFormatted = number_format($remaining, 0, ',', '.');
        $investedFormatted = number_format($invested, 2, ',', '.');
    @endphp
    <div class="relative font-sans">
        <a href="{{ $data['link'] }}" target="_blank">
            <div class="flex">
                <div class="flex-none">
                    <div class="{{ $project['type'] === 'finance' ? 'bg-light_green' : 'bg-light_blue' }} z-10 absolute top-0 left-0 text-white rounded-br-lg rounded-tl-lg py-1 px-4 shadow-inner text-sm fo-lgnt-bold">
                        {{ __($project['status']) }}
                    </div>
                    <img class="rounded-bl-lg rounded-tl-lg"
                        data-lazy="{{ $project['image'] }}{{ strstr($project['image'], '?') !== false ? '&' : '?' }}w=480&h=530&fit=crop"
                        alt="{{ $project['name'] }}"
                        style="width: 480px; height: 530px;"
                    >
                </div>
                <div class="flex-grow relative">
                    <div class="p-3 border-b border-lighter_gray">
                        <div class="text-base uppercase truncate font-bold text-gray">
                            {{ $project['name'] }}
                        </div>
                        <div class="text-light_gray text-xs">
                            {{ $project['location'] }}
                        </div>
                    </div>
                    <div class="p-3 border-b border-lighter_gray">
                        <div class="flex">
                            @if ($project['type'] === 'finance')
                                <div class="w-1/2">
                                    <div class="text-base font-bold text-gray">{{ $project['coupon_rate'] }} %</div>
                                    <div class="text-light_gray text-xs">Auschüttung pro Jahr</div>
                                </div>
                            @elseif ($project['type'] === 'stock')
                                <div class="w-1/2">
                                    <div class="text-base font-bold text-gray">{{ $project['rating'] }}</div>
                                    <div class="text-light_gray text-xs">Bewertung</div>
                                </div>
                            @endif
                            <div class="w-1/2 text-right">
                                <div class="text-base font-bold text-gray">{{ $project['interest_rate'] }} %</div>
                                <div class="text-light_gray text-xs">Gesamtrendite</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border-b border-lighter_gray">
                        <div class="flex">
                            <div class="w-1/2">
                                <div class="text-xs text-gray">{{ $investedFormatted }}% finanziert</div>
                            </div>
                            <div class="w-1/2 text-right">
                                <div class="text-light_gray text-xs">{{ $remainingFormatted }} verbleibend</div>
                            </div>
                        </div>
                        <div class="bg-lighter_gray w-full rounded">
                            <div class="{{ $project['type'] === 'finance' ? 'bg-green' : 'bg-blue' }} leading-none py-1 rounded-l" style="width: {{ $invested }}%"></div>
                        </div>
                    </div>
                    <div class="p-3 border-b border-lighter_gray">
                        <div class="text-sm truncate font-bold text-gray">
                            {{ __($project['type']) }}
                        </div>
                        <div class="text-light_gray text-xs">
                            Projekt-Typ
                        </div>
                    </div>
                    <div class="p-3 border-b border-lighter_gray">
                        <div class="text-sm truncate font-bold text-gray">
                            Platzhalter
                        </div>
                        <div class="text-light_gray text-xs">
                            Platzhalter
                        </div>
                    </div>
                    <div class="p-3 border-b border-lighter_gray">
                        <div class="text-sm truncate font-bold text-gray">
                            Platzhalter
                        </div>
                        <div class="text-light_gray text-xs">
                            Platzhalter
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="text-sm font-bold text-gray">
                            {{ $project['intermediator'] }}
                        </div>
                        <div class="text-light_gray text-xs">
                            Vermittler
                        </div>
                    </div>
                    <div class="p-3 absolute bottom-0" style="width:100%;">
                        <div class="text-sm {{ $project['type'] === 'finance' ? 'bg-green' : 'bg-blue' }} {{ $project['type'] === 'finance' ? 'hover:bg-light_green' : 'hover:bg-light_blue' }} w-full rounded-full py-1 shadow text-white text-center uppercase font-medium cursor-pointer">Zum Projekt</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.slider').slick({
            lazyLoad: 'progressive',
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
        });
    });
</script>

<style>
    .slick-prev{
        left: 5px;
        z-index: 10;
    }
    .slick-next{
        right: 5px;
        z-index: 10;
    }
    .slick-prev:before {
        color: gray;
    }
    .slick-next:before {
        color: gray;
    }
</style>
