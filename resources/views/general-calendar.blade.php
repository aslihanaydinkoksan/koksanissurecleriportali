@extends('layouts.app')



@section('title', 'Genel KÖKSAN Takvimi')



<style>
    /* === home.blade.php'den Kopyalanan Stiller Başlangıç === */



    /* Ana içerik alanına animasyonlu arka plan */

    #app>main.py-4 {

        padding: 2.5rem 0 !important;

        /* Padding'i home gibi yapalım */

        min-height: calc(100vh - 72px);

        background: linear-gradient(-45deg,

                #dbe4ff,

                #fde2ff,

                #d9fcf7,

                #fff0d9);

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



    /* Modern Frosted Glass Kart (Takvim için kullanılacak) */

    .create-shipment-card {

        /* Bu class adını kullanalım ki stil aynı olsun */

        border-radius: 1.25rem;

        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);

        border: 1px solid rgba(255, 255, 255, 0.4);

        background-color: rgba(255, 255, 255, 0.85);

        backdrop-filter: blur(10px);

        -webkit-backdrop-filter: blur(10px);

        transition: transform 0.2s ease, box-shadow 0.2s ease;

        margin-bottom: 1.5rem;

        /* Altına boşluk ekleyelim */

    }



    .create-shipment-card .card-header {

        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));

        border-bottom: 1px solid rgba(102, 126, 234, 0.2);

        font-weight: 700;

        font-size: 1.1rem;

        color: #2d3748;

        padding: 1.25rem 1.5rem;

        border-radius: 1.25rem 1.25rem 0 0;

    }



    .create-shipment-card .card-body {

        padding: 1.5rem;

        /* Kartın iç boşluğu */

        color: #2d3748;

    }



    /* FullCalendar Özelleştirmeleri (home.blade.php'den) */

    #calendar {

        /* Arka planı ve padding'i card-body'den alacak, bu yüzden sadeleştirelim */

        background: transparent;

        /* Arka planı şeffaf yap */

        border-radius: 0;

        /* Köşeyi card-body ayarlar */

        padding: 0;

        /* Padding'i card-body ayarlar */

    }



    .fc .fc-button-primary {

        background: linear-gradient(135deg, #667EEA, #764BA2);

        border: none;

        border-radius: 0.5rem;

        font-weight: 600;

        text-transform: uppercase;

        font-size: 0.85rem;

        letter-spacing: 0.5px;

        transition: all 0.2s ease;

        color: white;

    }



    .fc .fc-button-primary:hover {

        background: linear-gradient(135deg, #764BA2, #667EEA);

        transform: translateY(-1px);

        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);

        color: white;

    }



    .fc .fc-button-primary:not(:disabled).fc-button-active,

    .fc .fc-button-primary:not(:disabled):active {

        background: linear-gradient(135deg, #764BA2, #667EEA);

        color: white;

    }



    .fc-event {

        border-radius: 0.5rem;

        border: none !important;

        padding: 2px 6px;

        font-weight: 600;

        transition: transform 0.2s ease, box-shadow 0.2s ease;

        cursor: pointer;
        user-select: none;

        font-size: 0.8em;

    }



    .fc-event:hover {

        transform: scale(1.05);

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);

    }



    .fc .fc-daygrid-day-number {

        font-weight: 600;

        color: #4a5568;

    }



    .fc .fc-col-header-cell-cushion {

        /* Hafta günleri (Pzt, Salı vb.) */

        font-weight: 700;

        color: #2d3748;

        text-transform: uppercase;

        font-size: 0.85rem;

        letter-spacing: 0.5px;

        text-decoration: none;

    }



    .fc .fc-daygrid-day.fc-day-today {

        /* Bugünkü günün arka planı */

        background: rgba(102, 126, 234, 0.1) !important;

    }
</style>



@section('content')

    <div class="container">

        <div class="row justify-content-center">

            {{-- Sütun genişliği --}}

            <div class="col-md-11">

                {{-- Takvimi card yapısı içinde --}}

                <div class="card create-shipment-card">

                    {{-- Kart başlığı --}}

                    <div class="card-header">

                        📅 Genel KÖKSAN Takvimi

                    </div>

                    {{-- Takvimi card-body içinde --}}

                    <div class="card-body">

                        <div id='calendar' data-current-user-id="{{ Auth::id() }}"
                            data-is-authorized="{{ in_array(Auth::user()->role, ['admin', 'yönetici']) ? 'true' : 'false' }}">
                            ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @include('partials.calendar-modal')

@endsection
@section('page_scripts')
    {{-- Script kütüphaneleri ve başlatma kodu --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // === Evrensel Modal Elementleri ===
            var detailModalElement = document.getElementById('detailModal');
            if (!detailModalElement) {
                console.error("Hata: 'detailModal' elementi bulunamadı!");
                return; // Modal yoksa devam etme
            }
            var detailModal = new bootstrap.Modal(detailModalElement);
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalDynamicBody');
            const modalEditButton = document.getElementById('modalEditButton');
            const modalExportButton = document.getElementById('modalExportButton');
            const modalDeleteForm = document.getElementById('modalDeleteForm');
            const modalOnayForm = document.getElementById('modalOnayForm');
            const modalOnayKaldirForm = document.getElementById('modalOnayKaldirForm');
            const modalOnayBadge = document.getElementById('modalOnayBadge');

            var calendarElGlobal = document.getElementById('calendar'); // Global referans
            const currentUserId = parseInt(calendarElGlobal.dataset.currentUserId, 10);
            const isAuthorized = calendarElGlobal.dataset.isAuthorized === 'true';

            // === YARDIMCI FONKSİYON: Tarih/Saat Ayırıcı ===
            /**
             * Bir tarih-saat dizesini (örn: "19.05.2025 11:30") 
             * tarih ve saat olarak ayırır.
             * @@param {string} dateTimeString - Ayırılacak dize.
             * @@returns @{{ date: string, time: string }}
             */
            function splitDateTime(dateTimeString) {
                const dt = String(dateTimeString || ''); // String'e dönüştür ve null/undefined kontrolü yap
                const parts = dt.split(' ');
                const date = parts[0] || '-';
                let time = parts[1] || '-';

                // Eğer tarih yoksa (sadece '-' ise) veya saat kısmı boşsa ('') saati de gösterme
                if (date === '-' || time === '') {
                    time = '-';
                }

                return {
                    date: date,
                    time: time
                };
            }


            // === Evrensel Modal Açma Fonksiyonu  ===
            function openUniversalModal(props) {
                console.log('--- MODAL PROPS GELDİ ---', props);
                if (!props || !props.eventType) {
                    console.error("Modal için geçersiz veri:", props);
                    return;
                }
                modalTitle.textContent = props.title || 'Detaylar';
                let showButtons = false;
                if (props.eventType === 'production' || props.eventType === 'service_event' || props.eventType ===
                    'vehicle_assignment') {
                    if (isAuthorized) { // Admin veya Yönetici ise her zaman göster
                        showButtons = true;
                    } else if (props.user_id) { // Etkinlikte 'user_id' (oluşturan) varsa
                        showButtons = (props.user_id === currentUserId); // Sadece oluşturan kişi ise göster
                    } else {
                        // user_id prop'u eklenmemişse (ve admin değilse) güvenlik için gizle
                        showButtons = false;
                        console.warn(
                        `'${props.eventType}' etkinliğinde 'user_id' prop'u eksik. Butonlar gizlendi.`);
                    }
                } else {
                    // Diğer etkinlik türleri (örn: 'shipment') için varsayılan olarak göster
                    // (Bu türlerin kendi iç modal logikleri olabilir, örn: 'onay' butonu)
                    showButtons = true;
                }

                // Düzenle Butonu
                if (showButtons && props.editUrl && props.editUrl !== '#') {
                    modalEditButton.href = props.editUrl;
                    modalEditButton.style.display = 'inline-block';
                } else {
                    modalEditButton.style.display = 'none';
                }

                // Silme Butonu
                if (modalDeleteForm) {
                    if (showButtons && props.deleteUrl) {
                        modalDeleteForm.action = props.deleteUrl;
                        modalDeleteForm.style.display = 'inline-block';
                    } else {
                        modalDeleteForm.style.display = 'none';
                    }
                }
                let html = '<div class="row">';
                if (props.eventType === 'shipment') {
                    modalExportButton.href = props.exportUrl || '#';
                    modalExportButton.style.display = 'inline-block';
                    if (props.details['Onay Durumu']) {
                        modalOnayForm.style.display = 'none';
                        if (modalOnayKaldirForm) {
                            modalOnayKaldirForm.action = props.onayKaldirUrl;
                            modalOnayKaldirForm.style.display = 'inline-block';
                        }
                        modalOnayBadge.style.display = 'inline-block';
                        document.getElementById('modalOnayBadgeTarih').textContent = props.details['Onay Durumu'];
                        document.getElementById('modalOnayBadgeKullanici').textContent = props.details[
                            'Onaylayan'] || '';
                    } else {
                        modalOnayForm.action = props.onayUrl;
                        modalOnayForm.style.display = 'inline-block';
                        if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                        modalOnayBadge.style.display = 'none';
                    }
                    const isGemi = (props.details['Araç Tipi'] || '').toLowerCase().includes('gemi');
                    const col1 = [],
                        col2 = [];
                    col1.push(`<strong>🚛 Araç Tipi:</strong> ${props.details['Araç Tipi'] || '-'}`);
                    if (!isGemi) {
                        col1.push(`<strong>🔢 Plaka:</strong> ${props.details['Plaka'] || '-'}`);
                        col1.push(`<strong>🔢 Dorse Plakası:</strong> ${props.details['Dorse Plakası'] || '-'}`);
                        col1.push(`<strong>👨‍✈️ Şoför Adı:</strong> ${props.details['Şoför Adı'] || '-'}`);
                    } else {
                        col1.push(`<strong>⚓ IMO Numarası:</strong> ${props.details['IMO Numarası'] || '-'}`);
                        col1.push(`<strong>🚢 Gemi Adı:</strong> ${props.details['Gemi Adı'] || '-'}`);
                    }
                    if (!isGemi) {
                        col2.push(`<strong>📍 Kalkış Noktası:</strong> ${props.details['Kalkış Noktası'] || '-'}`);
                        col2.push(`<strong>📍 Varış Noktası:</strong> ${props.details['Varış Noktası'] || '-'}`);
                    } else {
                        col2.push(`<strong>🏁 Kalkış Limanı:</strong> ${props.details['Kalkış Limanı'] || '-'}`);
                        col2.push(`<strong>🎯 Varış Limanı:</strong> ${props.details['Varış Limanı'] || '-'}`);
                    }
                    col2.push(`<strong>🔄 Sevkiyat Türü:</strong> ${props.details['Sevkiyat Türü'] || '-'}`);
                    html += `<div class="col-md-6">${col1.map(item => `<p>${item}</p>`).join('')}</div>`;
                    html += `<div class="col-md-6">${col2.map(item => `<p>${item}</p>`).join('')}</div>`;
                    html += '</div><hr><div class="row">';
                    html +=
                        `<div class="col-md-12"><p><strong>📦 Kargo Yükü:</strong> ${props.details['Kargo Yükü'] || '-'}</p></div>`;
                    html +=
                        `<div class="col-md-6"><p><strong>🏷️ Kargo Tipi:</strong> ${props.details['Kargo Tipi'] || '-'}</p></div>`;
                    html +=
                        `<div class="col-md-6"><p><strong>⚖️ Kargo Miktarı:</strong> ${props.details['Kargo Miktarı'] || '-'}</p></div>`;
                    html += '</div><hr><div class="row">'; // Yeni satır

                    // === DEĞİŞİKLİK BAŞLANGIÇ: 'shipment' TÜRÜ İÇİN ===
                    // Tarih ve saatleri ayır
                    const cikis = splitDateTime(props.details['Çıkış Tarihi']);
                    const varis = splitDateTime(props.details['Tahmini Varış']);

                    // Ayrılmış HTML'i oluştur
                    html += '<div class="col-md-6">';
                    html += `    <p><strong>📅 Çıkış Tarihi:</strong> ${cikis.date}</p>`;
                    if (cikis.time !== '-') {
                        html += `    <p><strong>🕒 Çıkış Saati:</strong> ${cikis.time}</p>`;
                    }
                    html += '</div>';

                    html += '<div class="col-md-6">';
                    html += `    <p><strong>📅 Tahmini Varış:</strong> ${varis.date}</p>`;
                    if (varis.time !== '-') {
                        html += `    <p><strong>🕒 Varış Saati:</strong> ${varis.time}</p>`;
                    }
                    html += '</div>';
                    // === DEĞİŞİKLİK BİTİŞ ===

                } else {
                    modalExportButton.style.display = 'none';
                    modalOnayForm.style.display = 'none';
                    if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                    modalOnayBadge.style.display = 'none';
                    if (props.eventType === 'production') {
                        html +=
                            `<div class="col-md-12"><p><strong>📝Plan Başlığı:</strong> ${props.details['Plan Başlığı'] || '-'}</p><p><strong>Hafta Başlangıcı:</strong> ${props.details['Hafta Başlangıcı'] || '-'}</p><p><strong>Oluşturan:</strong> ${props.details['Oluşturan'] || '-'}</p>`;
                        if (props.details['Plan Detayları'] && props.details['Plan Detayları'].length > 0) {
                            html +=
                                '<strong>📋Plan Detayları:</strong><table class="table table-sm table-bordered mt-2" style="background: rgba(255,255,255,0.5);">';
                            html +=
                                '<thead><tr><th>⚙️Makine</th><th>📦📦Ürün</th><th>🧮Adet</th></tr></thead><tbody>';
                            props.details['Plan Detayları'].forEach(item => {
                                html +=
                                    `<tr><td>${item.machine || '-'}</td><td>${item.product || '-'}</td><td>${item.quantity || '-'}</td></tr>`;
                            });
                            html += '</tbody></table>';
                        }
                        html += `</div>`;
                    } else if (props.eventType === 'service_event') {
                        // === DEĞİŞİKLİK BAŞLANGIÇ: 'service_event' TÜRÜ İÇİN ===
                        html +=
                            `<div class="col-md-12"><p><strong>🤩Etkinlik Tipi:</strong> ${props.details['Etkinlik Tipi'] || '-'}</p><p><strong>📍Konum:</strong> ${props.details['Konum'] || '-'}</p></div>`;

                        // Tarih ve saatleri ayır
                        const baslangic = splitDateTime(props.details['Başlangıç']);
                        const bitis = splitDateTime(props.details['Bitiş']);

                        // İki sütunlu tarih/saat alanı oluştur
                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Başlangıç Tarihi:</strong> ${baslangic.date}</p>`;
                        if (baslangic.time !== '-') {
                            html += `    <p><strong>🕒 Başlangıç Saati:</strong> ${baslangic.time}</p>`;
                        }
                        html += '</div>';
                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Bitiş Tarihi:</strong> ${bitis.date}</p>`;
                        if (bitis.time !== '-') {
                            html += `    <p><strong>🕒 Bitiş Saati:</strong> ${bitis.time}</p>`;
                        }
                        html += '</div>';

                        html +=
                            `<div class="col-md-12 mt-3"><p><strong>👩🏻‍💻Kayıt Yapan:</strong> ${props.details['Kayıt Yapan'] || '-'}</p></div>`;
                        // === DEĞİŞİKLİK BİTİŞ ===

                    } else if (props.eventType === 'vehicle_assignment') {
                        // === DEĞİŞİKLİK BAŞLANGIÇ: 'vehicle_assignment' TÜRÜ İÇİN ===
                        html +=
                            `<div class="col-md-12">
                                <p><strong>🚘Araç:</strong> ${props.details['Araç'] || '-'}</p>
                                <p><strong>📋Görev:</strong> ${props.details['Görev'] || '-'}</p>
                                <p><strong>Yer:</strong> ${props.details['Yer'] || '-'}</p>
                                <p><strong>Talep Eden:</strong> ${props.details['Talep Eden'] || '-'}</p>
                            </div>`; // Tarihler hariç diğer bilgiler

                        // Tarih ve saatleri ayır
                        const baslangic = splitDateTime(props.details['Başlangıç']);
                        const bitis = splitDateTime(props.details['Bitiş']);

                        // İki sütunlu tarih/saat alanı
                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Başlangıç Tarihi:</strong> ${baslangic.date}</p>`;
                        if (baslangic.time !== '-') {
                            html += `    <p><strong>🕒 Başlangıç Saati:</strong> ${baslangic.time}</p>`;
                        }
                        html += '</div>';
                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Bitiş Tarihi:</strong> ${bitis.date}</p>`;
                        if (bitis.time !== '-') {
                            html += `    <p><strong>🕒 Bitiş Saati:</strong> ${bitis.time}</p>`;
                        }
                        html += '</div>';

                        html +=
                            `<div class="col-md-12 mt-3"><p><strong>Kayıt Yapan:</strong> ${props.details['Kayıt Yapan'] || '-'}</p></div>`; // Kalan bilgi
                        // === DEĞİŞİKLİK BİTİŞ ===
                    }
                }
                html += '</div>';
                const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
                    'Açıklama'];
                if (aciklama) {
                    html +=
                        `<hr><p><strong>📝 Notlar / Açıklamalar:</strong></p><p style="margin-left: 1rem; padding: 1rem; background: rgba(102, 126, 234, 0.05); border-radius: 0.5rem;">${aciklama}</p>`;
                }
                if (props.eventType === 'shipment' && props.details['Dosya Yolu']) {
                    html +=
                        `<hr><p><strong>📎 Ek Dosya:</strong></p><a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary btn-sm">📄 Dosyayı Görüntüle / İndir</a>`;
                }
                modalBody.innerHTML = html;
                detailModal.show();
            }

            // === FULLCALENDAR BAŞLATMA ===
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'tr',
                firstDay: 1,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Bugün',
                    month: 'Ay',
                    week: 'Hafta',
                    day: 'Gün',
                    list: 'Liste'
                },
                // BURASI ÖNEMLİ: Bu satırın Blade tarafından yorumlanması gerekiyor,
                // bu yüzden @@verbatim kullanmıyoruz.
                events: '{{ route('web.calendar.events') }}', // AJAX rotası
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                displayEventEnd: true,
                editable: false,
                selectable: false,
                height: 'auto',
                eventDisplay: 'list-item',

                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    openUniversalModal(info.event.extendedProps);
                }
            });
            calendar.render();

            // === Modal Formları için Event Listener'lar ===
            if (modalOnayForm) {
                modalOnayForm.addEventListener('submit', function(e) {
                    if (!confirm('Sevkiyatın tesise ulaştığını onaylıyor musunuz?')) e.preventDefault();
                    else this.querySelector('button[type=submit]').disabled = true;
                });
            }
            if (modalOnayKaldirForm) {
                modalOnayKaldirForm.addEventListener('submit', function(e) {
                    if (!confirm('Bu sevkiyatın onayını geri almak istediğinizden emin misiniz?')) e
                        .preventDefault();
                    else this.querySelector('button[type=submit]').disabled = true;
                });
            }
            if (modalDeleteForm) {
                modalDeleteForm.addEventListener('submit', function(e) {
                    this.querySelector('button[type=submit]').disabled = true;
                });
            }
        });
    </script>
@endsection
