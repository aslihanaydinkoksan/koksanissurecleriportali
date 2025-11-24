
<?php $__env->startSection('title', 'Genel KÖKSAN Takvimi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* === 1. GENEL SAYFA VE ARKA PLAN === */
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

        /* === 2. FULLCALENDAR MODERNİZASYONU (Hizalama Düzeltmeleri) === */
        #calendar {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 1.25rem;
            padding: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        /* Hücre Yükseklikleri Eşitlensin */
        .fc .fc-daygrid-day-frame {
            min-height: 120px;
            transition: background-color 0.2s;
        }

        /* Bugünün Rengi */
        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(102, 126, 234, 0.08) !important;
        }

        /* Etkinlik Kutuları - Tek Satır ve Düzenli */
        .fc-event {
            border: none !important;
            margin: 2px 4px !important;
            padding: 3px 6px;
            font-size: 0.85rem;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .fc-event:hover {
            transform: scale(1.02);
            z-index: 50;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Metin Taşmasını Engelle (...) */
        .fc-event-main {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block !important;
            font-weight: 600;
        }

        .fc-event-time {
            font-weight: 700;
            margin-right: 5px;
            opacity: 0.8;
            font-size: 0.8em;
        }

        /* "+ Daha Fazla" Linki */
        .fc-daygrid-more-link {
            font-weight: 700;
            color: #667EEA !important;
            background: rgba(102, 126, 234, 0.1);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
            margin-top: 2px;
        }

        /* FullCalendar Header Butonları */
        .fc .fc-button-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .fc .fc-button-primary:hover {
            background: linear-gradient(135deg, #764BA2, #667EEA);
            transform: translateY(-1px);
        }

        /* === 3. KART VE TABLO TASARIMLARI === */
        .create-shipment-card {
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
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

        /* === 4. MODAL TASARIMI (ESKİ CSS'LER GERİ GELDİ) === */
        .modal-backdrop.show {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
        }

        #detailModal .modal-content {
            border: none;
            border-radius: 24px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.98);
        }

        #detailModal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        #detailModal .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center / cover no-repeat;
            opacity: 0.3;
            pointer-events: none;
        }

        #detailModal .modal-title {
            font-weight: 700;
            font-size: 1.75rem;
            z-index: 1;
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        #detailModal .btn-close {
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            opacity: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        #detailModal .btn-close:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        #detailModal .modal-body {
            padding: 2.5rem;
            background: #fafbfc;
        }

        .modal-info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(102, 126, 234, 0.15);
            transition: all 0.3s ease;
        }

        .modal-info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        /* === 5. BUTON STİLLERİ (GERİ GETİRİLEN KISIM) === */
        .btn {
            border-radius: 0.75rem;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        /* Modal Edit Butonu */
        #modalEditButton {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 167, 38, 0.4);
        }

        #modalEditButton:hover {
            box-shadow: 0 8px 25px rgba(255, 167, 38, 0.5);
        }

        /* Modal Export Butonu */
        #modalExportButton {
            background: linear-gradient(135deg, #4fd1c5, #38b2ac);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 209, 197, 0.4);
        }

        #modalExportButton:hover {
            box-shadow: 0 8px 25px rgba(79, 209, 197, 0.5);
        }

        /* Modal Onay/Sil/İptal Butonları */
        #modalOnayForm .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.4);
        }

        #modalOnayKaldirForm .btn-warning {
            background: linear-gradient(135deg, #f6ad55, #ed8936);
            color: white;
            box-shadow: 0 4px 15px rgba(246, 173, 85, 0.4);
        }

        #modalDeleteForm .btn-danger {
            background: linear-gradient(135deg, #fc8181, #f56565);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: transparent;
        }

        /* Modal Notlar Alanı */
        .modal-notes-box {
            background: linear-gradient(135deg, rgba(67, 233, 123, 0.08), rgba(56, 249, 215, 0.08));
            border-left: 4px solid #43e97b;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .modal-notes-title {
            font-weight: 700;
            color: #43e97b;
            margin-bottom: 0.75rem;
        }

        /* Badge ve Checkbox */
        #modalOnayBadge {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
            animation: badgeSlideIn 0.5s ease;
        }

        @keyframes badgeSlideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        #modalImportantCheckboxContainer {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(255, 107, 107, 0.1));
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid rgba(220, 53, 69, 0.3);
        }

        #modalImportantCheckbox {
            accent-color: #dc3545;
            width: 20px;
            height: 20px;
        }

        /* Loading Spinner */
        .modal-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }

        .modal-spinner {
            border: 4px solid rgba(102, 126, 234, 0.2);
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Önemli Event Animasyonu */
        .event-important-pulse {
            border: 2px solid #ff4136 !important;
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 65, 54, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 65, 54, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 65, 54, 0);
            }
        }

        /* Tablo Stilleri */
        .table thead th {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            color: #2d3748;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }

        .fc-event-holiday {

            font-weight: 600;

            border: none !important;

            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;

            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);

            border-radius: 4px;

            position: relative;

            overflow: hidden;

            padding: 3px 6px;

            font-size: 11px;

            white-space: nowrap;

            text-overflow: ellipsis;

            max-width: 100%;

            display: block;

            transition: all 0.3s ease;

            cursor: pointer;

        }



        .fc-event-holiday::before {

            content: '';

            position: absolute;

            top: 0;

            left: -100%;

            width: 100%;

            height: 100%;

            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);

            animation: shine 3s infinite;

            pointer-events: none;

        }



        .fc-event-holiday:hover {

            transform: scale(1.05);

            z-index: 1000;

            white-space: normal;

            min-width: max-content;

            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.5);

            overflow: visible;

        }

        @keyframes shine {

            to {

                left: 100%;

            }

        }

        .custom-calendar-tooltip .tooltip-inner {
            background-color: #ffffff !important;
            /* Beyaz Arkaplan */
            color: #2d3748 !important;
            /* Koyu Gri Yazı */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            /* Yumuşak Gölge */
            border: 1px solid rgba(102, 126, 234, 0.2);
            /* İnce Mor Çerçeve */
            border-radius: 12px !important;
            /* Yuvarlak Köşeler */
            padding: 12px 16px !important;
            font-size: 0.9rem;
            max-width: 300px;
            /* Genişlik Sınırı */
            text-align: left;
            font-family: system-ui, -apple-system, sans-serif;
            z-index: 10000;
        }

        /* Tooltip Ok İşareti (Arrow) Rengini Beyaza Çevirme */
        .custom-calendar-tooltip .tooltip-arrow::before {
            border-top-color: #ffffff !important;
            /* Ok rengi beyaz olsun */
            border-bottom-color: #ffffff !important;
            border-left-color: #ffffff !important;
            border-right-color: #ffffff !important;
        }

        /* Tooltip İçindeki Başlık */
        .tooltip-title-styled {
            font-weight: 700;
            color: #667EEA;
            /* Senin tema rengin (Mor/Mavi) */
            margin-bottom: 4px;
            display: block;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 4px;
        }

        /* Tooltip İçindeki Açıklama */
        .tooltip-desc-styled {
            font-size: 0.8rem;
            color: #718096;
            line-height: 1.4;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card create-shipment-card">
                    <div class="card-header">📅 Genel KÖKSAN Takvimi</div>
                    <div class="card-body">
                        <div id='calendar' data-current-user-id="<?php echo e(Auth::id()); ?>"
                            data-is-authorized="<?php echo e(in_array(Auth::user()->role, ['admin', 'yönetici']) ? 'true' : 'false'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fas fa-info-circle"></i> <span>Detaylar</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalOnayBadge" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div><strong>✓ Onaylandı</strong>
                                <div class="small mt-1"><i class="fas fa-calendar-check me-1"></i> <span
                                        id="modalOnayBadgeTarih"></span></div>
                                <div class="small"><i class="fas fa-user me-1"></i> <span
                                        id="modalOnayBadgeKullanici"></span></div>
                            </div>
                            <i class="fas fa-check-circle fa-3x" style="opacity: 0.5;"></i>
                        </div>
                    </div>
                    <div id="modalImportantCheckboxContainer" class="d-flex align-items-center justify-content-between"
                        style="display: none;">
                        <label for="modalImportantCheckbox" class="form-check-label"><i
                                class="fas fa-exclamation-circle me-1"></i> Bu Etkinliği Önemli Olarak İşaretle</label>
                        <input type="checkbox" id="modalImportantCheckbox" class="form-check-input">
                    </div>
                    <div id="modalDynamicBody">
                        <div class="modal-loading">
                            <div class="modal-spinner"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="modalEditButton" class="btn" style="display: none;"><i
                            class="fas fa-edit me-2"></i> Düzenle</a>
                    <a href="#" id="modalExportButton" class="btn" style="display: none;"><i
                            class="fas fa-file-excel me-2"></i> Excel İndir</a>

                    <form method="POST" id="modalOnayForm" style="display: none;" class="d-inline"><?php echo csrf_field(); ?><button
                            type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i> Tesise Ulaştı</button>
                    </form>
                    <form method="POST" id="modalOnayKaldirForm" style="display: none;" class="d-inline"><?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?><button type="submit" class="btn btn-warning"><i class="fas fa-undo me-2"></i>
                            Onayı Kaldır</button></form>
                    <form method="POST" id="modalDeleteForm" style="display: none;" class="d-inline"
                        onsubmit="return confirm('Silmek istediğinize emin misiniz?');"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button
                            type="submit" class="btn btn-danger"><i class="fas fa-trash me-2"></i> Sil</button></form>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fas fa-times me-2"></i> Kapat</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('partials.calendar-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.13/index.global.min.js'></script>
    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // === DÜZELTME: URL Params en başta ===
            const urlParams = new URLSearchParams(window.location.search);
            const dateFromUrl = urlParams.get('date');

            // === KULLANICI BİLGİLERİ (Yetki İçin) ===
            const currentUserDepartment = "<?php echo e(Auth::user()->department?->slug); ?>";
            const currentUserRole = "<?php echo e(Auth::user()->role); ?>";
            const isSuperAdmin = (currentUserRole === 'admin' || currentUserRole === 'yönetici');

            var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

            function splitDateTime(dt) {
                dt = String(dt || '');
                const parts = dt.split(' ');
                return {
                    date: parts[0] || '-',
                    time: parts[1] || '-'
                };
            }

            // === UI HARD RESET (Hayalet Buton ve Yetki Temizliği) ===
            function hardResetModalUI() {
                const ids = ['modalEditButton', 'modalExportButton', 'modalOnayForm', 'modalOnayKaldirForm',
                    'modalDeleteForm', 'modalOnayBadge', 'modalImportantCheckboxContainer'
                ];
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.style.display = 'none';
                        el.classList.remove('d-inline', 'd-block');
                    }
                });
                document.getElementById('modalTitle').innerHTML = '';
                document.getElementById('modalDynamicBody').innerHTML =
                    '<div class="modal-loading"><div class="modal-spinner"></div></div>';
                const exportBtn = document.getElementById('modalExportButton');
                if (exportBtn) exportBtn.innerHTML = '<i class="fas fa-file-excel me-2"></i> Excel İndir';
            }

            function openUniversalModal(props) {
                hardResetModalUI();
                if (!props || !props.eventType) return;

                // Elementleri Seç
                const modalTitle = document.getElementById('modalTitle');
                const modalBody = document.getElementById('modalDynamicBody');
                const modalEditButton = document.getElementById('modalEditButton');
                const modalExportButton = document.getElementById('modalExportButton');
                const modalDeleteForm = document.getElementById('modalDeleteForm');
                const modalOnayForm = document.getElementById('modalOnayForm');
                const modalOnayKaldirForm = document.getElementById('modalOnayKaldirForm');
                const modalOnayBadge = document.getElementById('modalOnayBadge');
                const modalImportantContainer = document.getElementById('modalImportantCheckboxContainer');
                const modalImportantCheckbox = document.getElementById('modalImportantCheckbox');

                // Yetkiler
                const calendarEl = document.getElementById('calendar');
                const currentUserId = parseInt(calendarEl.dataset.currentUserId, 10);
                const isAuthorized = calendarEl.dataset.isAuthorized === 'true';

                // Checkbox
                if (isAuthorized) {
                    modalImportantContainer.style.display = 'flex';
                    modalImportantCheckbox.checked = props.is_important || false;
                    modalImportantCheckbox.dataset.modelType = props.model_type;
                    modalImportantCheckbox.dataset.modelId = props.id;
                }

                modalTitle.innerHTML = `<span>${props.title || 'Detaylar'}</span>`;

                // Düzenle/Sil Yetkisi
                let canModify = isAuthorized;
                if (props.user_id && props.user_id === currentUserId) canModify = true;

                if (canModify && props.editUrl && props.editUrl !== '#') {
                    modalEditButton.href = props.editUrl;
                    modalEditButton.style.display = 'inline-block';
                }
                if (canModify && props.deleteUrl && modalDeleteForm) {
                    modalDeleteForm.action = props.deleteUrl;
                    modalDeleteForm.style.display = 'inline-block';
                }

                let html = '';

                // --- SEVKİYAT (SHIPMENT) ---
                if (props.eventType === 'shipment') {
                    // 1. Yetki Kontrolü: Sadece Lojistikçiler veya Adminler işlem yapabilir
                    const canManageShipment = isSuperAdmin || currentUserDepartment === 'lojistik';

                    modalExportButton.href = props.exportUrl || '#';
                    modalExportButton.style.display =
                        'inline-block'; // Excel herkese açık olabilir veya buraya da canManageShipment koyabilirsin.

                    if (canManageShipment) {
                        if (props.details['Onay Durumu']) {
                            modalOnayBadge.style.display = 'block';
                            document.getElementById('modalOnayBadgeTarih').textContent = props.details[
                                'Onay Durumu'];
                            document.getElementById('modalOnayBadgeKullanici').textContent = props.details[
                                'Onaylayan'] || '';
                            if (modalOnayKaldirForm) {
                                modalOnayKaldirForm.action = props.onayKaldirUrl;
                                modalOnayKaldirForm.style.display = 'inline-block';
                            }
                        } else {
                            modalOnayForm.action = props.onayUrl;
                            modalOnayForm.style.display = 'inline-block';
                        }
                    } else {
                        // Yetkisi yoksa sadece Badge göster (onaylıysa)
                        if (props.details['Onay Durumu']) {
                            modalOnayBadge.style.display = 'block';
                            document.getElementById('modalOnayBadgeTarih').textContent = props.details[
                                'Onay Durumu'];
                            document.getElementById('modalOnayBadgeKullanici').textContent = props.details[
                                'Onaylayan'] || '';
                        }
                    }

                    const isGemi = (props.details['Araç Tipi'] || '').toLowerCase().includes('gemi');
                    html +=
                        `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3"><i class="fas fa-truck me-2"></i>Araç Bilgileri</h6><div class="row">
                        <div class="col-md-6"><p><strong>Araç Tipi:</strong> ${props.details['Araç Tipi'] || '-'}</p></div>`;
                    if (!isGemi) {
                        html +=
                            `<div class="col-md-6"><p><strong>Plaka:</strong> ${props.details['Plaka'] || '-'}</p></div>
                                 <div class="col-md-6"><p><strong>Dorse:</strong> ${props.details['Dorse Plakası'] || '-'}</p></div>
                                 <div class="col-md-6"><p><strong>Şoför:</strong> ${props.details['Şoför Adı'] || '-'}</p></div>`;
                    } else {
                        html +=
                            `<div class="col-md-6"><p><strong>IMO:</strong> ${props.details['IMO Numarası'] || '-'}</p></div>
                                 <div class="col-md-6"><p><strong>Gemi:</strong> ${props.details['Gemi Adı'] || '-'}</p></div>`;
                    }
                    html += `</div></div>`;

                    html +=
                        `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3"><i class="fas fa-route me-2"></i>Rota</h6><div class="row">
                             <div class="col-md-6"><p><strong>Kalkış:</strong> ${props.details['Kalkış Noktası'] || props.details['Kalkış Limanı'] || '-'}</p></div>
                             <div class="col-md-6"><p><strong>Varış:</strong> ${props.details['Varış Noktası'] || props.details['Varış Limanı'] || '-'}</p></div></div></div>`;

                    const cikis = splitDateTime(props.details['Çıkış Tarihi']);
                    const varis = splitDateTime(props.details['Tahmini Varış']);
                    html +=
                        `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3"><i class="fas fa-clock me-2"></i>Zaman</h6>
                             <div class="row"><div class="col-md-6"><p><strong>Çıkış:</strong> ${cikis.date} ${cikis.time}</p></div>
                             <div class="col-md-6"><p><strong>Varış:</strong> ${varis.date} ${varis.time}</p></div></div></div>`;

                    if (props.details['Dosya Yolu']) html +=
                        `<div class="text-center mt-3"><a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-paperclip me-2"></i> Dosya</a></div>`;
                }

                // --- SEVKİYAT (TRAVEL) ---
                else if (props.eventType === 'travel') {
                    if (modalOnayForm) modalOnayForm.style.display = 'none';
                    html += `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3"><i class="fas fa-plane-departure me-2"></i>Seyahat</h6>
                             <p><strong>Plan:</strong> ${props.details['Plan Adı'] || '-'}</p>
                             <p><strong>Kişi:</strong> ${props.details['Oluşturan'] || '-'}</p>
                             <p><strong>Tarih:</strong> ${props.details['Başlangıç']} - ${props.details['Bitiş']}</p>
                             <p><strong>Durum:</strong> ${props.details['Durum'] || '-'}</p></div>`;
                    if (props.url) {
                        modalExportButton.href = props.url;
                        modalExportButton.target = "_blank";
                        modalExportButton.innerHTML = '<i class="fas fa-plane-departure me-2"></i> Detaya Git';
                        modalExportButton.style.display = 'inline-block';
                    }
                }

                // --- DİĞERLERİ ---
                else {
                    if (props.eventType === 'service_event') {
                        const baslangic = splitDateTime(props.details['Başlangıç']);
                        html +=
                            `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3">Etkinlik</h6><p>${props.title}</p><p><strong>Tarih:</strong> ${baslangic.date} ${baslangic.time}</p><p><strong>Konum:</strong> ${props.details['Konum'] || '-'}</p></div>`;
                    } else if (props.eventType === 'production') {
                        html +=
                            `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3">Üretim Planı</h6><p>${props.details['Plan Başlığı'] || '-'}</p></div>`;
                        if (props.details['Plan Detayları']) {
                            html +=
                                '<div class="modal-info-card"><table class="table table-sm"><thead><tr><th>Makine</th><th>Ürün</th><th>Adet</th></tr></thead><tbody>';
                            props.details['Plan Detayları'].forEach(i => {
                                html +=
                                    `<tr><td>${i.machine}</td><td>${i.product}</td><td>${i.quantity}</td></tr>`;
                            });
                            html += '</tbody></table></div>';
                        }
                    } else if (props.eventType === 'vehicle_assignment') {
                        html +=
                            `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3">Araç Görevi</h6><p>${props.details['Araç'] || '-'}</p><p>${props.details['Görev'] || '-'}</p></div>`;
                    }
                }

                const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
                    'Açıklama'];
                if (aciklama) html +=
                    `<div class="modal-notes-box"><div class="modal-notes-title">Notlar</div><p class="mb-0">${aciklama}</p></div>`;

                modalBody.innerHTML = html;
                detailModal.show();
            }

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: dateFromUrl || new Date(),
                locale: 'tr',
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

                slotEventOverlap: false,
                dayMaxEvents: 3, // UI Düzeltmesi için
                height: 'auto',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                displayEventEnd: true,
                eventDisplay: 'list-item', // UI Düzeltmesi için (noktalı liste görünümü)

                eventSources: [{
                        url: '<?php echo e(route('web.calendar.events')); ?>',
                        failure: () => alert('Veri hatası!')
                    },
                    {
                        googleCalendarId: 'tr.turkish#holiday@group.v.calendar.google.com',
                        color: '#dc3545',
                        className: 'fc-event-holiday',
                        googleCalendarApiKey: 'AIzaSyAQmEWGR-krGzcCk1r8R69ER-NyZM2BeWM'
                    }
                ],

                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.extendedProps && info.event.extendedProps.eventType)
                        openUniversalModal(info.event.extendedProps);
                },

                eventDidMount: function(info) {
                    // 1. Önemli Etkinlik Çerçevesi
                    if (info.event.extendedProps && info.event.extendedProps.is_important) {
                        info.el.classList.add('event-important-pulse');
                    }

                    // 2. Modern Tooltip Oluşturma
                    try {
                        // İçerik Hazırlama
                        let title = info.event.title;
                        let desc = '';

                        // Açıklama varsa al
                        if (info.event.extendedProps && info.event.extendedProps.details && info.event
                            .extendedProps.details['Açıklama']) {
                            desc = info.event.extendedProps.details['Açıklama'];
                        }
                        // Yoksa ve tarih aralığı varsa tarihleri göster (Opsiyonel)
                        else if (info.event.start) {
                            let start = info.event.start.toLocaleTimeString('tr-TR', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            if (start !== '00:00') desc = `Saat: ${start}`;
                        }

                        // HTML İçeriği Oluşturma (Modern Görünüm İçin)
                        let tooltipContent = `
            <div class="text-start">
                <span class="tooltip-title-styled">${title}</span>
                ${desc ? `<span class="tooltip-desc-styled">${desc}</span>` : ''}
            </div>
        `;

                        if (typeof bootstrap !== 'undefined') {
                            new bootstrap.Tooltip(info.el, {
                                title: tooltipContent,
                                html: true, // HTML kullanımını açtık
                                placement: 'top', // Üstte göster
                                trigger: 'hover', // Üzerine gelince
                                container: 'body',
                                customClass: 'custom-calendar-tooltip', // CSS sınıfımızı bağladık
                                delay: {
                                    "show": 100,
                                    "hide": 100
                                } // Hafif gecikme daha doğal hissettirir
                            });
                        } else {
                            // Yedek (Bootstrap yoksa)
                            info.el.setAttribute('title', title + (desc ? ' - ' + desc : ''));
                        }

                    } catch (error) {
                        console.warn('Tooltip hatası:', error);
                    }
                },

                eventsSet: function(info) {
                    const mid = urlParams.get('open_modal_id');
                    const mtype = urlParams.get('open_modal_type');
                    if (mid && mtype) {
                        const target = calendar.getEvents().find(e => e.extendedProps.id == mid && e
                            .extendedProps.model_type == mtype);
                        if (target) {
                            openUniversalModal(target.extendedProps);
                            window.history.replaceState({}, document.title, window.location.pathname);
                        }
                    }
                }
            });

            calendar.render();
            setInterval(function() {
                console.log('Veriler arkaplanda güncelleniyor...');
                calendar.refetchEvents(); // FullCalendar'ın sihirli fonksiyonu
            }, 30000);

            // Listeners
            const btnOnay = document.getElementById('modalOnayForm');
            if (btnOnay) btnOnay.addEventListener('submit', e => {
                if (!confirm('Onaylıyor musunuz?')) e.preventDefault();
            });

            const btnSil = document.getElementById('modalDeleteForm');
            if (btnSil) btnSil.addEventListener('submit', e => {
                if (!confirm('Silinsin mi?')) e.preventDefault();
            });

            const chkImportant = document.getElementById('modalImportantCheckbox');
            if (chkImportant) {
                chkImportant.addEventListener('change', function() {
                    const isChecked = this.checked;
                    this.disabled = true;
                    fetch('<?php echo e(route('calendar.toggleImportant')); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                model_id: this.dataset.modelId,
                                model_type: this.dataset.modelType,
                                is_important: isChecked
                            })
                        })
                        .then(res => res.json())
                        .then(data => calendar.refetchEvents())
                        .catch(err => {
                            alert('Hata');
                            this.checked = !isChecked;
                        })
                        .finally(() => this.disabled = false);
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/general-calendar.blade.php ENDPATH**/ ?>