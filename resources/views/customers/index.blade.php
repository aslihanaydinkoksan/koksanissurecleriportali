@extends('layouts.app')

@section('title', 'Müşteri Yönetimi')

@push('styles')
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        @keyframes gradientWave {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Modern kart tasarımı */
        .customer-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .customer-card:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        }

        /* Tablo hover efekti */
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.08) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Modern butonlar */
        .btn-primary-gradient {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-primary {
            border-color: #667EEA;
            color: #667EEA;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #667EEA;
            border-color: #667EEA;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Arama kutusu */
        .search-input {
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem 0.75rem 3rem;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .search-input:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background-color: white;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #667EEA;
        }

        /* Başarı mesajı animasyonu */
        .success-alert {
            animation: slideInDown 0.5s ease-out;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tablo styling */
        .table thead th {
            background-color: rgba(102, 126, 234, 0.08);
            border-bottom: 2px solid #667EEA;
            font-weight: 600;
            color: #444;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        /* Sayfa numaraları */
        .pagination .page-link {
            color: #667EEA;
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background-color: #667EEA;
            color: white;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background-color: #667EEA;
            border-color: #667EEA;
        }

        /* Responsive iyileştirmeleri */
        @media (max-width: 768px) {
            .table {
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card">
                    <div class="card-header bg-transparent border-0 px-4 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="mb-2 fw-bold" style="color: #2d3748;">
                                    <i class="fa-solid fa-users me-2" style="color: #667EEA;"></i>
                                    Müşteri Listesi
                                </h2>
                                <p class="text-muted mb-0">Tüm müşterilerinizi görüntüleyin ve yönetin</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button id="syncBtn" onclick="syncFromIaa()" class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    <i class="fa-solid fa-rotate me-2"></i>
                                    IAA'dan Senkronize Et
                                </button>
                                <a href="{{ route('customers.create') }}"
                                    class="btn btn-primary-gradient rounded-pill px-4 py-2">
                                    <i class="fa-solid fa-plus me-2"></i>
                                    Yeni Müşteri Ekle
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4">
                        <form method="GET" action="{{ route('customers.index') }}" class="mb-4" autocomplete="off">
                            <div class="position-relative">
                                <i class="fa-solid fa-search search-icon"></i>
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Müşteri adı, email veya telefona göre ara..." value="{{ $search ?? '' }}">
                            </div>
                        </form>

                        <!-- Dinamik Alert Kapsayıcısı -->
                        <div id="dynamicAlertContainer"></div>

                        <!-- Progress Bar Kapsayıcısı -->
                        <div id="syncProgressContainer" class="d-none mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-primary" id="syncProgressText">Senkronizasyon Başlatılıyor...</span>
                                <span class="fw-bold text-primary" id="syncProgressPercent">0%</span>
                            </div>
                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                <div id="syncProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert success-alert d-flex align-items-center mb-4 alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-circle-check me-3 fs-4"></i>
                                <div>
                                    <strong>Başarılı!</strong> {{ session('success') }}
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive" style="overflow-x: hidden;">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <i class="fa-solid fa-building me-2"></i>Müşteri Adı
                                        </th>
                                        <th scope="col">
                                            <i class="fa-solid fa-envelope me-2"></i>Email
                                        </th>
                                        <th scope="col">
                                            <i class="fa-solid fa-phone me-2"></i>Telefon
                                        </th>
                                        <th scope="col">
                                            <i class="fa-solid fa-user me-2"></i>İlgili Kişi
                                        </th>
                                        <th scope="col" class="text-end">
                                            <i class="fa-solid fa-cog me-2"></i>Eylemler
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $customer->name }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $primaryContact =
                                                        $customer->contacts->where('is_primary', true)->first() ??
                                                        $customer->contacts->first();
                                                    $displayEmail =
                                                        $customer->email ??
                                                        ($primaryContact ? $primaryContact->email : '-');
                                                @endphp
                                                <span class="text-muted">{{ $displayEmail }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $displayPhone =
                                                        $customer->phone ??
                                                        ($primaryContact ? $primaryContact->phone : '-');
                                                @endphp
                                                <span class="text-muted">{{ $displayPhone }}</span>
                                            </td>
                                            <td>
                                                @if ($primaryContact)
                                                    <span class="text-muted">
                                                        {{ $primaryContact->name }}
                                                        @if ($primaryContact->is_primary)
                                                            <i class="fa-solid fa-star text-warning small ms-1"
                                                                title="Ana Yetkili"></i>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('customers.show', $customer) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2"
                                                        title="Görüntüle">
                                                        <i class="fa-solid fa-eye me-1"></i>
                                                        Detaylar
                                                    </a>
                                                    <a href="{{ route('customers.edit', $customer) }}"
                                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                                        title="Düzenle">
                                                        <i class="fa-solid fa-pen me-1"></i>
                                                        Düzenle
                                                    </a>
                                                    @if(auth()->user()->hasRole(['super-admin', 'admin', 'yonetici']))
                                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-2" title="Sil">
                                                            <i class="fa-solid fa-trash me-1"></i>
                                                            Sil
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fa-solid fa-inbox fa-3x mb-3 d-block"
                                                        style="opacity: 0.3;"></i>
                                                    <p class="mb-0 fs-5">Kayıtlı müşteri bulunamadı.</p>
                                                    <p class="small">Yeni bir müşteri ekleyerek başlayın.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($customers->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $customers->appends(['search' => $search])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Sayfa yüklendiğinde, sessionStorage'da saklanan kalıcı bir mesaj var mı kontrol et
    const syncResult = sessionStorage.getItem('syncResult');
    if (syncResult) {
        try {
            const resultData = JSON.parse(syncResult);
            showPersistentAlert(resultData.type, resultData.message);
        } catch (e) {
            console.error("SessionStorage okuma hatası", e);
        }
        // Sayfa yenilendikten sonra bir daha görünmemesi için hemen temizliyoruz
        sessionStorage.removeItem('syncResult'); 
    }
});

function showPersistentAlert(type, message) {
    const container = document.getElementById('dynamicAlertContainer');
    // type: 'success' or 'danger'
    const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';
    const bgClass = type === 'success' ? 'success-alert' : 'bg-danger text-white rounded-3 shadow-sm border-0';
    const title = type === 'success' ? 'Başarılı!' : 'Hata!';
    
    // Mesajdaki newline karakterlerini <br> ile değiştir (Daha temiz görünüm için)
    const formattedMessage = message.replace(/\n/g, '<br>');

    container.innerHTML = `
        <div class="alert ${bgClass} d-flex align-items-center mb-4 alert-dismissible fade show" role="alert" style="animation: slideInDown 0.5s ease-out;">
            <i class="fa-solid ${iconClass} me-3 fs-3"></i>
            <div>
                <strong class="d-block mb-1 fs-5">${title}</strong>
                <span style="line-height: 1.5;">${formattedMessage}</span>
            </div>
            <button type="button" class="btn-close ${type === 'danger' ? 'btn-close-white' : ''}" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
}

function syncFromIaa() {
    console.log('Senkronizasyon başlatılıyor...');
    const btn = document.getElementById('syncBtn');
    if (!btn) return;
    
    // UI Elementleri
    const progressContainer = document.getElementById('syncProgressContainer');
    const progressBar = document.getElementById('syncProgressBar');
    const progressText = document.getElementById('syncProgressText');
    const progressPercent = document.getElementById('syncProgressPercent');
    
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> İşleniyor...';

    // Progress Bar Göster
    progressContainer.classList.remove('d-none');
    progressBar.style.width = '0%';
    progressPercent.innerText = '0%';
    progressText.innerText = 'Veriler toplanıyor ve aktarılıyor...';
    progressBar.classList.remove('bg-danger', 'bg-success');
    progressBar.classList.add('bg-primary');

    // Sahte (Simüle Edilmiş) İlerleme Animasyonu (Maks %90'a kadar)
    let progress = 0;
    const progressInterval = setInterval(() => {
        // Logaritmik yavaşlayan ilerleme simülasyonu
        const increment = (95 - progress) * 0.05 + 1;
        progress += increment;
        
        if (progress > 90) progress = Math.min(progress, 95); // 95'te takılı kalsın
        
        progressBar.style.width = Math.round(progress) + '%';
        progressPercent.innerText = Math.round(progress) + '%';
        
        if (progress > 30 && progress < 60) progressText.innerText = 'Şikayetler eşitleniyor...';
        if (progress >= 60) progressText.innerText = 'Müşteri formları güncelleniyor...';
    }, 400);

    let hostname = window.location.hostname;
    let iaaBaseUrl1 = '';
    let iaaBaseUrl2 = '';
    
    if (hostname === 'localhost' || hostname === '127.0.0.1') {
        iaaBaseUrl1 = window.location.protocol + '//' + hostname + ':8000';
        iaaBaseUrl2 = window.location.protocol + '//' + (hostname === '127.0.0.1' ? 'localhost' : '127.0.0.1') + ':8000';
    } else {
        iaaBaseUrl1 = window.location.protocol + '//' + hostname + '/iaa';
        iaaBaseUrl2 = iaaBaseUrl1;
    }

    const trySync = (url) => {
        return fetch(url + '/api/customers/bulk-sync', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
        });
    };

    // İlk deneme
    trySync(iaaBaseUrl1)
    .then(async response => {
        if (!response.ok) throw new Error('Status: ' + response.status);
        return response.json();
    })
    .catch(error => {
        return trySync(iaaBaseUrl2).then(async response => {
            if (!response.ok) throw new Error('Status: ' + response.status);
            return response.json();
        });
    })
    .then(data => {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        progressPercent.innerText = '100%';
        
        if (data.status === 'success') {
            progressBar.classList.remove('bg-primary');
            progressBar.classList.add('bg-success');
            progressText.innerText = 'Tamamlandı!';
            
            let msg = 'Senkronizasyon Mükemmel Şekilde Tamamlandı!\n\n';
            if (data.stats) {
                const s = data.stats;
                msg += `✅ Müşteriler: ${s.customer.success} başarılı`;
                if (s.customer.error > 0) msg += ` (${s.customer.error} hatalı)`;
                
                msg += `\n✅ İlgili Kişiler: ${s.contact.success} başarılı`;
                if (s.contact.error > 0) msg += ` (${s.contact.error} hatalı)`;
                
                msg += `\n✅ Kullanıcılar: ${s.user.success} başarılı`;
                if (s.user.error > 0) msg += ` (${s.user.error} hatalı)`;

                if (s.complaint && (s.complaint.success > 0 || s.complaint.error > 0)) {
                   msg += `\n✅ Şikayetler: ${s.complaint.success} başarılı`;
                   if (s.complaint.error > 0) msg += ` (${s.complaint.error} hatalı)`;
                }
            }

            // Sayfa yenilemesinden sonra mesajı gösterebilmek için Storage'a yaz
            sessionStorage.setItem('syncResult', JSON.stringify({ type: 'success', message: msg }));
            
            // Kullanıcı %100'ü 500ms görsün, sonra sayfa yenilensin
            setTimeout(() => {
                window.location.reload();
            }, 500);

        } else {
            progressBar.classList.remove('bg-primary');
            progressBar.classList.add('bg-danger');
            progressText.innerText = 'Hata Oluştu!';
            
            const errMsg = 'Senkronizasyon Başarısız!\nSebep: ' + (data.message || 'Bilinmeyen hata');
            sessionStorage.setItem('syncResult', JSON.stringify({ type: 'danger', message: errMsg }));
            setTimeout(() => { window.location.reload(); }, 500);
        }
    })
    .catch(error => {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        progressBar.classList.remove('bg-primary');
        progressBar.classList.add('bg-danger');
        progressPercent.innerText = 'X';
        progressText.innerText = 'Bağlantı Hatası';
        
        const errMsg = '!!! KRİTİK HATA: IAA UYGULAMASINA BAĞLANILAMADI !!!\n\n' + 
              'Hata Detayı: ' + error.message + '\n\n' +
              'Lütfen IAA uygulamasının ve adresinin doğru çalıştığından emin olun.';
              
        showPersistentAlert('danger', errMsg);
        
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        // Bağlantı hatasında sayfayı yenilemiyoruz, hatayı direkt ekranda görsün.
    });
}
</script>
@endsection
