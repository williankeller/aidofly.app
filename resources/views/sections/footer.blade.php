<div class="container my-3">
    <div class="row d-flex justify-content-between small text-muted">
        <div class="copyright mb-2 mb-lg-0 col-md-12 col-lg-6 d-flex justify-content-center justify-content-lg-start">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
        </div>
        <div class="version col-md-12 col-lg-6 d-flex justify-content-center align-items-center justify-content-lg-end">
            <span class="badge bg-warning me-1 p-1">BETA</span>
            <small>{{ versioning() }}</small>
        </div>
    </div>
</div>
