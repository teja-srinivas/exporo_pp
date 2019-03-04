<?php

use FormulaInterpreter\Command\CommandFactory\FunctionCommandFactory;

$closure = function() {
    return $this->functions;
};

$functions = Closure::bind(
    $closure,
    app(\FormulaInterpreter\Compiler::class)->functionCommandFactory,
    FunctionCommandFactory::class
);

?>
<div class="row mt-2">
    <div class="col-12 col-sm-10 offset-sm-2">
        <p>
            Grundrechenarten wie
            @foreach(['+', '-', '*', '/'] as $entry)
                <span class="badge badge-light">{{ $entry }}</span>
            @endforeach
            können ebenso wie Klammern
            (<span class="badge badge-light">(</span>, <span class="badge badge-light">)</span>)
            verwendet werden.
        </p>

        <p>
            Unterstützte Funktionen:

            @foreach(\Illuminate\Support\Arr::sort($functions()) as $function => $callback)
                <span class="badge badge-light">
                {{ $function }}<span class="text-muted">()</span>
            </span>
            @endforeach
            .

            Der jeweilige Parameter gehört ans Ende der Funktion
            (z.B. <span class="badge badge-light">max(pow(4, 2) * laufzeit, 3)</span>)
        </p>

        Unterstützte Variablen:

        @foreach(['investment', 'bonus', 'laufzeit', 'marge'] as $entry)
            <span class="badge badge-info">{{ $entry }}</span>
        @endforeach

        (Groß-/Kleinschreibung egal).
    </div>
</div>
