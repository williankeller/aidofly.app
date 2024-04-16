@props([
    'count' => 3,
    'columns' => false,
])
<div @class(['placeholders', 'row' => $columns])>
    @for ($i = 0; $i < $count; $i++)
        {{ $slot }}
    @endfor
</div>
