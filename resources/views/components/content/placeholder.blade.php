@props([
    'count' => 3,
    'columns' => false,
])
<div @class(['placeholder-wave', 'row' => $columns])>
    @for ($i = 0; $i < $count; $i++)
        {{ $slot }}
    @endfor
</div>
