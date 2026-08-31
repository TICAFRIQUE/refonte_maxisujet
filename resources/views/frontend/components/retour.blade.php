<a href="javascript:void(0);" id="frontGoBack" class="d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold flex-shrink-0" style="color: var(--ms-blue-dark);">
    <i class="bi bi-arrow-left"></i> Retour
</a>

<script>
    document.getElementById('frontGoBack')?.addEventListener('click', function (e) {
        e.preventDefault();
        const ref = document.referrer;
        if (ref && ref !== window.location.href) {
            window.location.href = ref;
        } else {
            window.history.back();
        }
    });
</script>
