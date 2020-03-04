@extends('layouts.sidebar')

@section('title', 'Übersicht')

@section('main-content')
    @if(count($campaigns) > 0)
        <div class="slider">
            @foreach($campaigns as $campaign)
                <div>
                    <a href="{{ route('campaigns.show', $campaign['id']) }}">
                        <img src="{{ $campaign['image_url'] }}" alt="{{ $campaign['title'] }}" class="banner-img" />
                    </a>
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
            <div class="lead text-center text-muted">
                Das Partner Cockpit ist die zentrale Anlaufstelle für Ihre Tätigkeit als Partner. Sie können hier alle Aktivitäten Ihrer Kunden und Interessenten sehen und erhalten Statistiken zum Verhalten Ihrer Kunden. Zudem stehen Ihnen hier die monatliche Abrechnungen und Provisionsansprüche zur Verfügung. Das Partner Cockpit bieten Ihnen auch auch einen direkten Zugang zu Werbemitteln und Tools für eine nahtlose digitale Integration.
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
    .slider {
        height: 300px;
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
