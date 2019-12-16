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
            <div class="{{ $project['type'] === 'equity' ? 'bg-light_green' : 'bg-light_blue' }} z-10 absolute top-0 left-0 text-white rounded-br-lg rounded-tl-lg py-1 px-4 shadow-inner text-sm fo-lgnt-bold">
                {{ __($project['status']) }}
            </div>
            <div class="{{ $project['type'] === 'equity' ? 'bg-light_green' : 'bg-light_blue' }} z-10 absolute left-0 text-white rounded-tr-lg py-1 px-2 shadow-inner text-2xs fo-lgnt-bold" style="top: 255px;">
                Warnhinweis beachten
            </div>
            <img class="rounded-tl-lg rounded-tr-lg img-small"
                data-lazy="{{ $project['image'] }}{{ strstr($project['image'], '?') !== false ? '&' : '?' }}w=345&h=275&fit=crop"
                alt="{{ $project['name'] }}"
            >
            <div class="p-3 py-2 border-b border-lighter_gray">
                <div class="text-base uppercase truncate font-bold text-gray">
                    {{ $project['name'] }}
                </div>
                <div class="text-light_gray text-xs">
                    {{ $project['location'] }}
                </div>
            </div>
            <div class="p-3 py-2 border-b border-lighter_gray">
                <div class="flex">
                    @if ($project['type'] === 'equity')
                        <div class="w-1/2">
                            <div class="text-base font-bold text-gray">{{ $project['coupon_rate'] }} %</div>
                            <div class="text-light_gray text-xs">Auschüttung pro Jahr</div>
                        </div>
                    @elseif ($project['type'] === 'finance')
                        <div class="w-1/2">
                            <div class="text-base font-bold text-gray">
                                <span class="
                                    inline-block bg-gray-200 rounded-full text-sm font-semibold text-white flex items-center justify-center h-6 w-6
                                    @if ($project['rating'] === 'AA'){{ 'bg-rating_aa' }}
                                    @elseif ($project['rating'] === 'A'){{ 'bg-rating_a' }}
                                    @elseif ($project['rating'] === 'B'){{ 'bg-rating_b' }}
                                    @elseif ($project['rating'] === 'C'){{ 'bg-rating_c' }}
                                    @elseif ($project['rating'] === 'D'){{ 'bg-rating_d' }}
                                    @elseif ($project['rating'] === 'E'){{ 'bg-rating_e' }}
                                    @elseif ($project['rating'] === 'F'){{ 'bg-rating_f' }}
                                    @endif
                                ">
                                    {{ $project['rating'] }}
                                </span>
                            </div>
                            <div class="text-light_gray text-xs">Exporo Klasse</div>
                        </div>
                    @endif
                    <div class="w-1/2 text-right">
                        <div class="text-base font-bold text-gray">{{ $project['interest_rate'] }} %</div>
                        <div class="text-light_gray text-xs">Gesamtrendite</div>
                    </div>
                </div>
            </div>
            <div class="p-3 py-2 border-b border-lighter_gray">
                <div class="flex">
                    <div class="w-1/3">
                        <div class="text-xs text-gray">{{ $investedFormatted }}% finanziert</div>
                    </div>
                    <div class="w-2/3 text-right">
                        <div class="text-light_gray text-xs">{{ $remainingFormatted }} verbleibend</div>
                    </div>
                </div>
                <div class="bg-lighter_gray w-full rounded">
                    <div class="{{ $project['type'] === 'equity' ? 'bg-green' : 'bg-blue' }} leading-none py-1 rounded-l" style="width: {{ $invested }}%"></div>
                </div>
            </div>
            <div class="p-3 py-2">
                <div class="text-sm font-bold text-gray">
                    {{ $project['intermediator'] }}
                </div>
                <div class="text-light_gray text-xs">
                    Vermittler
                </div>
            </div>
            <div class="p-3 py-2">
                <div class="text-sm {{ $project['type'] === 'equity' ? 'bg-green' : 'bg-blue' }} {{ $project['type'] === 'equity' ? 'hover:bg-light_green' : 'hover:bg-light_blue' }} w-full rounded-full py-1 shadow text-white text-center uppercase font-medium cursor-pointer">Zum Projekt</div>
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
        top: 230px;
        z-index: 10;
    }
    .slick-next{
        right: 5px;
        top: 230px;
        z-index: 10;
    }
    .slick-prev:before {
        color: gray;
    }
    .slick-next:before {
        color: gray;
    }
    .img-small {
        width: 345px;
        height: 275px;
        object-fit: cover;
    }
</style>
