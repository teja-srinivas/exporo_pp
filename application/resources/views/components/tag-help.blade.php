Folgende Textbausteine stehen zur verfügung:<br>
@foreach($tags as $name => $value)
    <span class="badge badge-secondary">{{ $name }}</span> <span class="badge badge-light">{{ value($value) }}</span><br>
@endforeach
