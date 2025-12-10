{{-- Veritabanındaki her bir satırı (Oda, Personel, Konum) temsil eden kart. Tablo görünümünden çok daha modern ve mobil uyumludur. --}}
@props(['title', 'subtitle' => null, 'badge' => null, 'badgeColor' => 'primary'])

{{-- 1. Kart Arka Planı ve Gölgelendirme Güncellendi --}}
<div class="card h-100 border-0 transition-base hover-lift"
    style="background-color: var(--card-bg, #ffffff); box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05), 0 1px 3px 0 rgba(0, 0, 0, 0.05);">

    {{-- 2. Header: Daha temiz ve belirgin başlık --}}
    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-start">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex flex-column">
                {{-- Başlık: Daha büyük ve koyu --}}
                <h5 class="fw-bolder text-dark mb-1" style="font-size: 1.2rem;">{{ $title }}</h5>
                @if ($subtitle)
                    {{-- Alt Başlık: Hafif gri ve küçük --}}
                    <small class="text-secondary" style="font-size: 0.85rem;">{{ $subtitle }}</small>
                @endif
            </div>
        </div>

        {{-- 3. Badge (Etiket): Daha okunaklı ve modern görünüm --}}
        @if ($badge)
            <span
                class="badge badge-subtle-{{ $badgeColor }} border-0 rounded-pill px-3 py-2 text-uppercase fw-semibold"
                style="font-size: 0.75rem;">
                {{ $badge }}
            </span>
        @endif
    </div>

    {{-- Card Body --}}
    <div class="card-body px-4 py-2">
        {{-- İçerik Metni: Daha yumuşak gri tonu --}}
        <div class="d-flex flex-column gap-3 text-body-secondary" style="font-size: 0.9rem;">
            {{ $slot }}
        </div>
    </div>

    {{-- 4. Footer: Hafif ayırıcı ve arka plan --}}
    <div class="card-footer bg-light-subtle border-top px-4 py-3"
        style="border-top: 1px solid rgba(0, 0, 0, 0.08) !important;">
        <div class="d-flex justify-content-end gap-2">
            {{ $actions ?? '' }}
        </div>
    </div>
</div>

{{-- 5. CSS Stilleri ve Fallback Güncellemeleri --}}
<style>
    /* Bootstrap 5.3 bg-subtle olmayanlar için fallback ve Badge Stili */
    /* Bu stil tanımının, projenizdeki genel CSS dosyasına taşınması daha iyidir. */
    :root {
        /* Örnek renkler, projenizdeki gerçek değişkenlerle değiştirin */
        --primary-light: #e0f7fa;
        /* primary-subtle için varsayım */
        --primary: #00bcd4;
        /* primary metin rengi için varsayım */
        --shadow-lift: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
    }

    /* Yeni Badge Stili: Metin rengi normal, arka plan süper hafif */
    .badge-subtle-primary {
        background-color: var(--primary-light) !important;
        color: var(--primary) !important;
    }

    .text-body-secondary {
        color: #6c757d !important;
        /* Hafif gri içerik metni */
    }

    /* Hover Efekti: Kartı hafifçe kaldır, belirgin bir gölge ekle */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    }

    /* Footer Arka Planı */
    .bg-light-subtle {
        background-color: rgba(0, 0, 0, 0.03) !important;
    }
</style>
