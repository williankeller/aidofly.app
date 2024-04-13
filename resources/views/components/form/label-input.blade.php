@props(['label', 'id', 'type' => 'text', 'required' => false])
<div>
    <label @class(['form-label', 'required' => $required]) for="{{ $id }}">{{ $label }}</label>
    <input type="{{ $type }}" class="form-control" id="{{ $id }}" @required($required) {{ $attributes }}>
    <div class="valid-feedback feedback-field-{{ $id }}"></div>
</div>
