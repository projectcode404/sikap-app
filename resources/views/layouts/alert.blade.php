{{-- layouts/alert.blade.php --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
<script>
// 🔹 Theme detection for dark mode
function getSwalTheme() {
    const isDark = document.documentElement.getAttribute("data-bs-theme") === "dark";
    return {
        background: isDark ? '#1e1e2f' : '#fff',
        color: isDark ? '#f8f9fa' : '#212529',
        iconColor: isDark ? '#ffc107' : undefined
    };
}

// ✅ Toast Success
function showSuccessToast(message = 'Berhasil!') {
    const theme = getSwalTheme();
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        background: theme.background,
        color: theme.color,
        iconColor: theme.iconColor,
        timerProgressBar: true
    });
}

// ❌ Toast Error
function showErrorToast(message = 'Terjadi kesalahan.') {
    const theme = getSwalTheme();
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        background: theme.background,
        color: theme.color,
        iconColor: theme.iconColor,
        timerProgressBar: true
    });
}

// ℹ️ Toast Info
function showInfoToast(message = 'Informasi...') {
    const theme = getSwalTheme();
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        background: theme.background,
        color: theme.color,
        iconColor: theme.iconColor,
        timerProgressBar: true
    });
}

// ❓ Modal konfirmasi aksi
function confirmAction(message = 'Are you sure?', callback) {
    const theme = getSwalTheme();
    Swal.fire({
        title: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        background: theme.background,
        color: theme.color,
        iconColor: theme.iconColor,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}
</script>

@if(session('success'))
<script> showSuccessToast(@json(session('success'))); </script>
@endif

@if(session('error'))
<script> showErrorToast(@json(session('error'))); </script>
@endif

@if(session('info'))
<script> showInfoToast(@json(session('info'))); </script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ $errors->first() }}',
    });
</script>
@endif