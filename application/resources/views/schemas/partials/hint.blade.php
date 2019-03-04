<div class="row mt-2">
    <div class="col-12 col-sm-10 offset-sm-2">
        Unterstützte Funktionen:

        <div class="row text-monospace mt-1 mb-3">
            @foreach(\Illuminate\Support\Arr::sort([
                'sqrt',
                'round',
                'ceil',
                'floor',
                'sinh',
                'cosh',
                'tanh',
                'coth',
                'sind',
                'cosd',
                'tand',
                'cotd',
                'sin',
                'cos',
                'tan',
                'cot',
                'arsinh',
                'arcosh',
                'artanh',
                'arcoth',
                'arsin',
                'arcos',
                'artan',
                'arcot',
                'exp',
                'log10',
                'log',
                'ln',
                'abs',
                'sgn',
            ]) as $function)
            <div class="col-6 col-md-3 col-lg-2 text-nowrap">
                <span class="badge badge-light">
                    {{ $function }}<span class="text-muted">()</span>
                </span>
            </div>
            @endforeach
        </div>

        Unterstützte Konstanten:

        <div class="row text-monospace mt-1">
            @foreach(\Illuminate\Support\Arr::sort([
                'pi',
                'e',
                'NAN',
                'INF',
            ]) as $constant)
            <div class="col-6 col-md-3 col-lg-2 text-nowrap">
                <span class="badge badge-light">
                    {{ $constant }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>
