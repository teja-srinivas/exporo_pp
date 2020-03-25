@extends('layouts.sidebar')

@section('title', 'Übersicht')

@section('main-content')
    @if(count($campaigns) > 0)
        <div class="slider">
            @foreach($campaigns as $campaign)
                <div class="card accent-primary">
                    <h3 class="mt-3 ml-3">
                        {{ $campaign['title'] }}
                    </h3>
                    @if($campaign['image_url'] !== null)
                        <a href="{{ route('campaigns.show', $campaign['id']) }}">
                            <img src="{{ $campaign['image_url'] }}" alt="{{ $campaign['title'] }}" class="banner-img" />
                        </a>
                    @else
                        <div class="px-3 lead text-muted campaign-text">
                            {{ $campaign['description'] }}
                        </div>
                    @endif
                    <div class="d-flex flex-row-reverse p-3">
                        <a href="{{ route('campaigns.show', $campaign['id']) }}" class="btn btn-primary btn-sm">mehr erfahren</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($investmentCount > 0)
        @php($vueData = [
            'apiInvestments' => route('api.dashboard.investments'),
            'apiCommissions' => route('api.dashboard.commissions'),
        ])
        <vue v-cloak class="cloak-fade" data-is="investments-viewer" data-props='@json($vueData)' />
    @else
        @card
            <div class="lead text-muted">
                Das Partner Cockpit ist die zentrale Anlaufstelle für Ihre Tätigkeit
                als Partner. Sie können hier alle Aktivitäten Ihrer Kunden und Interessenten
                sehen und erhalten Statistiken zum Verhalten Ihrer Kunden. Zudem stehen
                Ihnen hier die monatlichen Abrechnungen und Provisionsansprüche zur Verfügung.
                Das Partner Cockpit bietet Ihnen auch einen direkten Zugang zu Werbemitteln
                und Tools für eine nahtlose digitale Integration.
            </div>
            <div>
              <h4 class="mt-3 mb-3">Sehen Sie auf einen Blick die Entwicklung Ihrer Provisionen</h4>
                <img
                    src="{{ url('/images/investment_placeholder.png') }}"
                    alt="Provisionsbeispiel"
                    style="width: 100%;"
                >
            </div>
        @endcard
    @endif
@endsection

<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
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
    .campaign-text {
        height: 300px;
        overflow: auto;
    }
    .slider {
        height: 418px;
    }
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
    .banner-img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
</style>
