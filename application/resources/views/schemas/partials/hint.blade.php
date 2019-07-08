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
            Kommawerte werden durch einen Punkt getrennt (z.B. <span class="badge badge-light">3.14</span>)
        </p>

        <p>
            Unterstützte Funktionen:

            @foreach(\Illuminate\Support\Arr::sort(array_keys($functions())) as $function)
                <span class="badge badge-light">
                {{ $function }}<span class="text-muted">()</span>
            </span>
            @endforeach.

            <br>

            Der jeweilige Parameter gehört ans Ende der Funktion
            (z.B. <span class="badge badge-light">max(pow(4, 2) * laufzeit, 3)</span>)
        </p>

        Unterstützte Variablen:

        @foreach(\Illuminate\Support\Arr::sort(\App\Models\Schema::VARS) as $entry)
            <span class="badge badge-info">{{ ucfirst($entry) }}</span>
        @endforeach

        (Groß-/Kleinschreibung egal).
    </div>
</div>
