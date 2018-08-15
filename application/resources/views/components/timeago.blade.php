@if($dateTime === null)
<small class="text-muted">(Unbekannt)</small>
@else
<abbr title="{{ $dateTime }}">
    {{ $dateTime->diffForHumans() }}
</abbr>
@endif
