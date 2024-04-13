@props(['label', 'id', 'type' => 'text', 'required' => false])
<div>
    <label @class(['form-label', 'required' => $required]) for="{{ $id }}">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $id }}" class="form-control" {{ $attributes }} @required($required)>
    <div class="valid-feedback feedback-field-{{ $id }}"></div>
</div>
