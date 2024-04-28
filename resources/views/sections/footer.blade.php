<div class="container d-flex justify-content-between small text-muted">
    <div class="copyright">
        <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
    </div>
    <div class="version">
        <span class="badge bg-warning">BETA</span>
        <span>{{ versioning() }}</span>
    </div>
</div>
