<!-- Scripts -->
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/embed/app.js') }}"></script>
<!-- Styles -->
<link href="{{ mix('css/embed/app.css') }}" rel="stylesheet">

@if ($data['width'] == 770 && $data['height'] == 530)
    @include('affiliate.embeds.partials.large_slider')
@elseif ($data['width'] == 345 && $data['height'] == 530)
    @include('affiliate.embeds.partials.small_slider')
@endif