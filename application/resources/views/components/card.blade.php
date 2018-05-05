<div class="card shadow-sm border-0 accent-primary">
    <div class="card-body">
        {{-- Card Header --}}
        @if(isset($title))
        <div class="card-title">
            <h4 @if(isset($subtitle))class="mb-1"@endif>{{ $title }}</h4>
            @if(isset($subtitle))
                {{ $subtitle }}
            @endif
        </div>
        @endif

        {{-- Card Contents --}}
        {{ $slot }}
    </div>

    @if(isset($footer))
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endif
</div>
