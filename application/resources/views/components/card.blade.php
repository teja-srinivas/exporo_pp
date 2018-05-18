<div class="card my-3 shadow-sm border-0 accent-primary">
    <div class="card-body">
        {{-- Card Header --}}
        @if(isset($title) && !isset($info))
        <div class="card-title">
            <h5 @isset($subtitle)class="mb-1"@endisset>{{ $title }}</h5>
            {{ $subtitle or '' }}
        </div>
        @endif

        {{-- Card Contents --}}
        @isset($info)
            <div class="row">
                <div class="col-lg-4 mb-3 mb-lg-0 small text-dark">
                    @isset($title)
                        <h5 class="card-title">{{ $title }}</h5>
                    @endif

                    {{ $info }}
                </div>
                <div class="col-lg-8">
                    {{ $slot }}
                </div>
            </div>
        @else
            {{ $slot }}
        @endisset
    </div>

    @isset($footer)
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endisset
</div>
