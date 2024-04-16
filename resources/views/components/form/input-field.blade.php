@props(['label', 'id', 'type' => 'text', 'required' => false, 'value' => ''])
<div class="position-relative">
    <label @class(['form-label', 'required' => $required]) for="{{ $id }}">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $id }}" value="{{ old($id, $value) }}"
        @class(['form-control', 'is-invalid' => $errors->has($id)]) {{ $attributes }} @required($required)>
    @if ($errors->has($id))
        <small class="valid-tooltip feedback-field-{{ $id }}">
            {{ $errors->first($id) }}
        </small>
    @endif
</div>
