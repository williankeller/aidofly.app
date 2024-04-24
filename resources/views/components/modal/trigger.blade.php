<button type="button" class="btn btn-{{ $variant ?? 'primary' }} d-flex align-items-center justify-content-center"
    @click="modal.open('{{ $id }}')">
    {{ $slot }}
</button>
