@props(['label', 'id', 'required' => false, 'value' => ''])
<div class="position-relative">
    <label @class(['form-label', 'required' => $required]) for="{{ $id }}">{{ $label }}</label>
    <div class="input-password-group" x-data="{ isVisible: false }">
        <input :type="isVisible ? 'text' : 'password'" id="{{ $id }}" name="{{ $id }}"
            @class(['form-control', 'is-invalid' => $errors->has($id)]) {{ $attributes }} @required($required)>

        <button type="button" class="input-password-toggle" aria-label="Toggle password visibility"
            @click="isVisible = !isVisible">
            <i class="fs-4 ti ti-eye text-muted" :class="{ 'ti-eye-closed': isVisible, 'ti-eye': !isVisible }"></i>
        </button>
    </div>
    @if ($errors->has($id))
        <small class="valid-tooltip feedback-field-{{ $id }}">
            {{ $errors->first($id) }}
        </small>
    @endif
</div>
