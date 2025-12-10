<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KÖKSAN Misafirhane')</title>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">

    @include('layouts.navbar')

    <main class="flex-grow-1 py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer style="background-color: var(--bg-primary); border-top: 1px solid var(--border-color);"
        class="py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0 text-secondary" style="font-size: var(--text-sm);">
                &copy; {{ date('Y') }} <strong>KÖKSAN Misafirhane</strong>. Tüm hakları saklıdır.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. GLOBAL SİLME İŞLEMİ DİNLEYİCİSİ
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('delete-form')) {
                e.preventDefault();

                var message = e.target.getAttribute('data-message') || 'Bu işlem geri alınamaz!';
                var confirmBtnText = e.target.getAttribute('data-confirm-text') || 'Evet, Sil!';

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    // Renkleri senin custom.css'indeki primary ve danger tonlarına yaklaştırdım
                    confirmButtonColor: '#ef4444', // var(--danger-color)
                    cancelButtonColor: '#64748b', // var(--secondary-color)
                    confirmButtonText: confirmBtnText,
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        e.target.submit();
                    }
                });
            }
        });

        // 2. BAŞARI MESAJLARI
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#10b981' // var(--success-color)
            });
        @endif

        // 3. HATA MESAJLARI
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        @endif
    </script>

    @stack('scripts')
</body>

</html>
