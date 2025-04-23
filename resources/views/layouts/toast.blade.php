<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    @if(session('success'))
        <div id="toastSuccess" class="toast align-items-center text-white bg-success shadow-lg border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000" style="min-width: 360px; opacity: 0.95; border-radius: 0.75rem;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="toastError" class="toast align-items-center text-white bg-danger shadow-lg border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000" style="min-width: 360px; opacity: 0.95; border-radius: 0.75rem;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div id="toastInfo" class="toast align-items-center text-white bg-info shadow-lg border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" style="min-width: 360px; opacity: 0.95; border-radius: 0.75rem;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        ['Success', 'Error', 'Info'].forEach(function(type) {
            const toastEl = document.getElementById('toast' + type);
            if (toastEl) {
                new bootstrap.Toast(toastEl).show();
            }
        });
    });
</script>
@endpush