@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @foreach($menu as $group)
                    <h6 class="text-muted text-uppercase tracking-wide sidebar-header @unless($loop->first) mt-4 @endif">
                        <span>
                            {{ $group['title'] }}

                            @unless(empty($group['help'] ?? ''))
                                <a href="{{ $group['help'] }}" class="small text-primary ml-2">Hilfe?</a>
                            @endif
                        </span>
                    </h6>

                    @foreach($group['links'] as $route => $link)
                        <a href="{{ $route }}"
                           class="sidebar-item @if(request()->routeIs($route))shadow-sm active @endif"
                        >{{ $link }}</a>
                    @endforeach
                @endforeach
            </div>
            <div class="col-md-9">
                @yield('main-content')
            </div>
        </div>
    </div>
@endsection
