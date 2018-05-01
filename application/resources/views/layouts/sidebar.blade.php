@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @foreach([
                    [
                        'title' => 'Meine Provisionen',
                        'help' => '#',
                        'links' => [
                            'home' => 'Übersicht',
                            url('#1') => 'Abrechnungen',
                            url('#2') => 'Provisionen',
                        ],
                    ],
                    [
                        'title' => 'Meine Kunden',
                        'links' => [
                            url('#1') => 'Kunden werben',
                            url('#2') => 'Meine Kunden',
                            url('#3') => 'Investments',
                            url('#4') => 'Auszahlungen',
                        ],
                    ],
                    [
                        'title' => 'Meine Partner',
                        'links' => [
                            url('#1') => 'Partner werben',
                            url('#2') => 'Meine Partner',
                        ],
                    ],
                    [
                        'title' => 'Werbemittel',
                        'links' => [
                            url('#1') => 'Banner',
                            url('#2') => 'Iframes',
                            url('#3') => 'Mailings',
                            url('#4') => 'Links',
                            url('#5') => 'Social Media Content',
                        ],
                    ],
                ] as $group)
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

            <!--<h6 class="mt-4 text-muted text-uppercase" style="letter-spacing: 0.4px">Meine Daten</h6>
            <div class="py-2 px-3 mb-3 small rounded bg-white shadow-sm">
                Herr Björn Maronde<br>
                Am Sandtorkai 70
                20457 Hamburg
                investor@exporo-investmentplan.de
                015256196196
            </div>
            <a href="#" class="d-block px-3 py-1 text-secondary">Daten bearbeiten</a>
            <a href="#" class="d-block px-3 py-1 text-secondary">Dokumente</a>
            <a href="#" class="d-block px-3 py-1 text-secondary">Provisionsschema</a>
            <a href="#" class="d-block px-3 py-1 text-secondary">zum Investment Cockpit</a>
            <a href="#" class="d-block px-3 py-1 text-secondary">Buchhaltung</a>-->
            </div>
            <div class="col-md-9">
                @yield('main-content')
            </div>
        </div>
    </div>
@endsection
