<button type="button"
    {{ $attributes->merge(['class' => 'btn btn-' . $variant . ' d-flex align-items-center justify-content-center']) }}
    @click="modal.open('{{ $id }}')">
    {{ $slot }}
</button>
