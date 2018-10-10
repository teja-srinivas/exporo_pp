@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3">
                <div class="sidebar sticky-top mb-3">
                    @foreach($menu as $group)
                        <h6 class="text-muted text-uppercase tracking-wide sidebar-header @unless($loop->first) mt-4 @endif">
                            {{ $group['title'] }}

                            @unless(empty($group['help'] ?? ''))
                                <a href="{{ $group['help'] }}" class="small text-primary ml-2 align-self-end">
                                    Hilfe?
                                </a>
                            @endif
                        </h6>

                        @foreach($group['links'] as $link)
                            <a href="{{ $link['url'] }}"
                               class="sidebar-item @if($link['isActive'] ?? false)shadow-sm active @endif"
                            >{{ $link['title'] }}</a>
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center
                            @if($__env->hasSection('actions') || $__env->hasSection('title')) mb-3 @endif">
                    <div class="flex-fill mr-2">
                        @hasSection('title')
                            <h3 class="mb-0">@yield('title')</h3>
                        @endif
                    </div>

                    @yield('actions')
                </div>

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-warning">
                        Es sind Fehler beim Speichern aufgetreten
                    </div>
                @endif

                {{-- Other status messages --}}
                @include('components.status')

                @yield('main-content')
            </div>
        </div>
    </div>
@endsection
