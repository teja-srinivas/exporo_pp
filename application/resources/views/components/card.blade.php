<div class="card shadow-sm border-0">
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
</div>
