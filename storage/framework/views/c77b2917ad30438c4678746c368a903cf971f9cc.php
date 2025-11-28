
<?php $__env->startSection('title', 'Genel KÖKSAN Takvimi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* === 1. SAYFA VE LAYOUT === */
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

        .wide-container {
            max-width: 95% !important;
            margin-left: auto;
            margin-right: auto;
        }

        /* === 2. KART TASARIMLARI === */
        .create-shipment-card {
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .create-shipment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
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
        }

        /* === 3. FULLCALENDAR İYİLEŞTİRMELERİ === */
        #calendar {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 1rem;
            padding: 10px;
        }

        .fc .fc-daygrid-day-frame {
            min-height: 160px !important;
            transition: background-color 0.2s;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(102, 126, 234, 0.08) !important;
        }

        /* === TAKVİM YAZI BOYUTLARI === */
        .fc .fc-col-header-cell-cushion {
            /* Gün isimlerini (Pzt, Sal) büyütelim */
            font-size: 1.1rem;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .fc-daygrid-day-number {
            /* Ayın gün numaralarını büyütelim */
            font-size: 1.1rem;
            font-weight: bold;
            color: #4a5568;
            padding: 8px !important;
        }

        .fc-event {
            border: none !important;
            margin: 1px 2px !important;
            padding: 4px 8px;
            font-size: 0.9rem;
            margin-bottom: 2px !important;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .fc-event:hover {
            transform: scale(1.03);
            z-index: 50;
        }

        .fc-event-main {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block !important;
        }

        .fc-daygrid-more-link {
            color: #667EEA !important;
            font-weight: 700;
            font-size: 0.75rem;
            text-decoration: none;
        }

        .fc .fc-button-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border: none;
            font-weight: 600;
        }

        /* === 4. BUTON VE ALERT STİLLERİ === */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-info {
            background: linear-gradient(135deg, #4FD1C5, #38B2AC);
            color: white !important;
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #FC8181, #F56565);
            color: white;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 1rem;
            border: none;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.15);
            color: #2f855a;
            border-left: 4px solid #48bb78;
        }

        .alert-danger {
            background: rgba(245, 101, 101, 0.15);
            color: #c53030;
            border-left: 4px solid #f56565;
        }

        /* === 5. MODAL STİLLERİ === */
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

        #detailModal .btn-close {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            opacity: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #detailModal .btn-close:hover {
            background-color: rgba(255, 255, 255, 0.4);
            transform: rotate(90deg);
        }

        .modal-info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(102, 126, 234, 0.15);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .modal-info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        #modalEditButton {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 167, 38, 0.4);
        }

        #modalExportButton {
            background: linear-gradient(135deg, #4fd1c5, #38b2ac);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 209, 197, 0.4);
        }

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

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

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

        .custom-calendar-tooltip .tooltip-inner {
            background-color: #ffffff !important;
            color: #2d3748 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-size: 0.9rem;
            max-width: 300px;
            text-align: left;
            font-family: system-ui, -apple-system, sans-serif;
            z-index: 10000;
        }

        .custom-calendar-tooltip .tooltip-arrow::before {
            border-top-color: #ffffff !important;
            border-bottom-color: #ffffff !important;
            border-left-color: #ffffff !important;
            border-right-color: #ffffff !important;
        }

        .tooltip-title-styled {
            font-weight: 700;
            color: #667EEA;
            margin-bottom: 4px;
            display: block;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 4px;
        }

        .tooltip-desc-styled {
            font-size: 0.8rem;
            color: #718096;
            line-height: 1.4;
        }

        /* === MOBİL İÇİN ÖZEL AYARLAR (RESPONSIVE KORUMA) === */
        @media (max-width: 768px) {
            .wide-container {
                max-width: 100% !important;
                padding: 0 10px;
            }

            /* Mobilde hücreler çok uzun olursa ekran kaydırmak zorlaşır, mobilde kısalım */
            .fc .fc-daygrid-day-frame {
                min-height: 80px !important;
            }

            .fc-event {
                font-size: 0.75rem !important;
                /* Mobilde fontu küçültelim */
                padding: 2px 4px !important;
            }

            .fc .fc-toolbar-title {
                font-size: 1.2rem !important;
                /* Başlığı mobilde taşmaması için küçültelim */
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid wide-container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card create-shipment-card">
                    <div class="card-header">📅 Genel KÖKSAN Takvimi</div>
                    <div class="card-body">
                        
                        <?php
                            // Admin veya Departmanı Olmayan Yönetici ise TRUE
                            $canFilter =
                                Auth::user()->role === 'admin' ||
                                (Auth::user()->role === 'yönetici' && is_null(Auth::user()->department_id));
                        ?>

                        <?php if($canFilter): ?>
                            <div class="calendar-filters p-3 mb-3"
                                style="background: rgba(102, 126, 234, 0.05); border-radius: 0.75rem;">
                                <strong class="me-3"><i class="fa-solid fa-filter"></i> Filtrele:</strong>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="lojistik" id="filterLojistik"
                                        checked>
                                    <label class="form-check-label" for="filterLojistik"
                                        style="color: #667EEA;">Lojistik</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="uretim" id="filterUretim"
                                        checked>
                                    <label class="form-check-label" for="filterUretim"
                                        style="color: #4FD1C5;">Üretim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="hizmet" id="filterHizmet"
                                        checked>
                                    <label class="form-check-label" for="filterHizmet" style="color: #F093FB;">İdari
                                        İşler</label>
                                </div>
                                
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="bakim" id="filterBakim" checked>
                                    <label class="form-check-label" for="filterBakim" style="color: #ED8936;">Bakım</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="important" id="filterImportant">
                                    <label class="form-check-label" for="filterImportant"
                                        style="color: #dc3545;"><strong>Sadece
                                            Önemliler</strong></label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div id='calendar' data-current-user-id="<?php echo e(Auth::id()); ?>"
                            data-user-role="<?php echo e(Auth::user()->role); ?>" data-user-dept="<?php echo e(Auth::user()->department_id); ?>"
                            
                            data-can-mark-important="<?php echo e(in_array(mb_strtolower(Auth::user()->role), ['admin', 'yönetici', 'müdür']) ? 'true' : 'false'); ?>">
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></button>
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
                                class="fas fa-exclamation-circle me-1"></i> Bu Veriyi Önemli Olarak İşaretle</label>
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
                            type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i> Tesise
                            Ulaştı</button></form>
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
            const urlParams = new URLSearchParams(window.location.search);
            const dateFromUrl = urlParams.get('date');

            // URL'den Modal Parametrelerini Al
            const urlModalId = urlParams.get('open_modal_id');
            const urlModalType = urlParams.get('open_modal_type');

            const currentUserRole = "<?php echo e(Auth::user()->role); ?>";
            const calendarEventsUrl = "<?php echo e(route('web.calendar.events')); ?>";
            const toggleImportantUrl = "<?php echo e(route('calendar.toggleImportant')); ?>";

            var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

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
                console.log('--- MODAL AÇILIYOR (GENEL) ---', props.eventType);
                hardResetModalUI();

                if (!props || !props.eventType) return;

                // === 1. DİNAMİK ÇEVİRİ VE STİL HARİTASI ===
                const statusMap = {
                    'Critical': {
                        text: 'Kritik',
                        class: 'bg-danger text-white'
                    },
                    'High': {
                        text: 'Yüksek',
                        class: 'bg-warning text-dark'
                    },
                    'Medium': {
                        text: 'Orta',
                        class: 'bg-info text-white'
                    },
                    'Low': {
                        text: 'Düşük',
                        class: 'bg-success text-white'
                    },
                    'Normal': {
                        text: 'Normal',
                        class: 'bg-secondary text-white'
                    },
                    'Pending': {
                        text: 'Beklemede',
                        class: 'bg-warning text-dark'
                    },
                    'Active': {
                        text: 'Aktif',
                        class: 'bg-success text-white'
                    }
                };

                // DOM Elementleri
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
                const calendarEl = document.getElementById('calendar');

                // === YENİ YETKİLENDİRME VE GÜVENLİK MANTIĞI ===
                const currentUserId = parseInt(calendarEl.dataset.currentUserId, 10);
                const currentUserRole = calendarEl.dataset.userRole;
                const currentUserDept = calendarEl.dataset.userDept;
                const canMarkImportant = calendarEl.dataset.canMarkImportant === 'true';

                const eventOwnerId = props.user_id;
                const eventDeptId = props.department_id;

                let canModify = false;

                if (currentUserRole === 'admin') {
                    canModify = true;
                } else if (eventOwnerId && eventOwnerId === currentUserId) {
                    canModify = true;
                } else if (currentUserRole === 'yönetici') {
                    if (!currentUserDept || (eventDeptId && String(currentUserDept) === String(eventDeptId))) {
                        canModify = true;
                    }
                }

                // === UI GÜNCELLEMELERİ: ÖNEMLİ BUTONU GÖRÜNÜRLÜĞÜ ===
                if (modalImportantContainer) {
                    const isVehicleTask = (props.model_type === 'vehicle_assignment');
                    let shouldShowCheckbox = false;

                    if (canMarkImportant) {
                        shouldShowCheckbox = true;
                    } else if (isVehicleTask && canModify) {
                        shouldShowCheckbox = true;
                    }

                    if (shouldShowCheckbox) {
                        modalImportantContainer.style.display = 'flex';
                        modalImportantCheckbox.checked = props.is_important || false;
                        modalImportantCheckbox.disabled = false;
                        modalImportantCheckbox.dataset.modelType = props.model_type;
                        modalImportantCheckbox.dataset.modelId = props.id;
                    } else {
                        modalImportantContainer.style.setProperty('display', 'none', 'important');
                    }
                }

                modalTitle.innerHTML = `<span>${props.title || 'Detaylar'}</span>`;

                if (canModify && props.editUrl && props.editUrl !== '#') {
                    modalEditButton.href = props.editUrl;
                    modalEditButton.style.display = 'inline-block';
                }
                if (canModify && props.deleteUrl && modalDeleteForm) {
                    modalDeleteForm.action = props.deleteUrl;
                    modalDeleteForm.style.display = 'inline-block';
                }

                // === DİNAMİK İÇERİK OLUŞTURMA (HTML) ===
                let html = '';
                let icon = 'fa-info-circle';
                let typeTitle = 'Etkinlik Detayları';

                if (props.eventType === 'shipment') {
                    icon = 'fa-truck';
                    typeTitle = 'Sevkiyat Bilgileri';
                } else if (props.eventType === 'travel') {
                    icon = 'fa-plane';
                    typeTitle = 'Seyahat Bilgileri';
                } else if (props.eventType === 'maintenance') {
                    icon = 'fa-screwdriver-wrench';
                    typeTitle = 'Bakım Bilgileri';
                } else if (props.eventType === 'production') {
                    icon = 'fa-industry';
                    typeTitle = 'Üretim Bilgileri';
                }

                html += `<div class="modal-info-card">
                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                    <i class="fas ${icon} me-2"></i>${typeTitle}
                </h6>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm m-0 align-middle"><tbody>`;

                const excludeKeys = ['Açıklama', 'Notlar', 'Açıklamalar', 'Dosya Yolu', 'Plan Detayları',
                    'Onay Durumu', 'Onaylayan'
                ];

                if (props.details && typeof props.details === 'object') {
                    Object.entries(props.details).forEach(([key, value]) => {
                        if (excludeKeys.includes(key)) return;
                        if (value === null || value === undefined || value === '' || value === '-') return;

                        let displayValue = '-';
                        if (value && typeof value === 'object' && value.is_badge) {
                            displayValue =
                                `<span class="badge ${value.class} px-3 py-2 rounded-pill fw-normal">${value.text}</span>`;
                        } else if (value !== null) {
                            const strValue = String(value).trim();
                            if (statusMap[strValue]) {
                                const mapItem = statusMap[strValue];
                                displayValue =
                                    `<span class="badge ${mapItem.class} px-3 py-2 rounded-pill fw-normal">${mapItem.text}</span>`;
                            } else {
                                const dateRegex = /^(\d{1,2}\.\d{1,2}\.\d{4})\s(\d{1,2}:\d{2})$/;
                                const match = strValue.match(dateRegex);
                                if (match) {
                                    displayValue = `<div class="d-flex gap-2">
                                            <span class="badge bg-light text-dark border border-secondary fw-normal"><i class="fas fa-calendar-alt text-primary me-1"></i> ${match[1]}</span>
                                            <span class="badge bg-light text-dark border fw-normal"><i class="fas fa-clock text-warning me-1"></i> ${match[2]}</span>
                                    </div>`;
                                } else {
                                    displayValue = strValue;
                                }
                            }
                        }
                        html +=
                            `<tr><td class="text-dark fw-bolder" style="width: 35%;">${key}:</td><td class="text-dark">${displayValue}</td></tr>`;
                    });
                }
                html += `</tbody></table></div></div>`;

                if (props.eventType === 'production' && props.details['Plan Detayları']) {
                    html +=
                        '<div class="modal-info-card"><h6 class="text-primary fw-bold mb-2">Üretim Kalemleri</h6><table class="table table-sm table-striped"><thead><tr><th>Makine</th><th>Ürün</th><th>Adet</th></tr></thead><tbody>';
                    props.details['Plan Detayları'].forEach(i => {
                        html += `<tr><td>${i.machine}</td><td>${i.product}</td><td>${i.quantity}</td></tr>`;
                    });
                    html += '</tbody></table></div>';
                }

                if (props.details && props.details['Dosya Yolu']) {
                    html +=
                        `<div class="text-center mt-3 mb-3"><a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-paperclip me-2"></i> Dosyayı Görüntüle</a></div>`;
                }

                const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
                    'Açıklama'];
                if (aciklama) {
                    html +=
                        `<div class="modal-notes-box mt-3 p-3 bg-light rounded border"><div class="modal-notes-title fw-bold mb-2 text-primary"><i class="fas fa-sticky-note me-1"></i> Açıklama / Notlar</div><p class="mb-0 text-secondary" style="white-space: pre-wrap;">${aciklama}</p></div>`;
                }

                // Butonlar (Onay, Export vb.)
                if (props.eventType === 'shipment') {
                    modalExportButton.href = props.exportUrl || '#';
                    modalExportButton.style.display = 'inline-block';
                    if (props.details['Onay Durumu']) {
                        modalOnayBadge.style.display = 'block';
                        document.getElementById('modalOnayBadgeTarih').textContent = props.details['Onay Durumu'];
                        document.getElementById('modalOnayBadgeKullanici').textContent = props.details[
                            'Onaylayan'] || '';
                        if (modalOnayKaldirForm) {
                            modalOnayKaldirForm.action = props.onayKaldirUrl;
                            modalOnayKaldirForm.style.display = 'inline-block';
                        }
                    } else {
                        if (modalOnayForm) {
                            modalOnayForm.action = props.onayUrl;
                            modalOnayForm.style.display = 'inline-block';
                        }
                    }
                } else if (props.eventType === 'travel') {
                    if (modalOnayForm) modalOnayForm.style.display = 'none';
                    if (canModify && props.url) {
                        modalExportButton.href = props.url;
                        modalExportButton.target = "_blank";
                        modalExportButton.innerHTML = '<i class="fas fa-plane-departure me-2"></i> Detaya Git';
                        modalExportButton.style.display = 'inline-block';
                    }
                } else if (props.eventType === 'maintenance') {
                    if (canModify && props.url) {
                        modalExportButton.href = props.url;
                        modalExportButton.innerHTML = '<i class="fas fa-eye me-2"></i> Detaya Git';
                        modalExportButton.style.display = 'inline-block';
                    }
                }

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
                dayMaxEvents: 5,
                height: 'auto',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                displayEventEnd: true,
                eventDisplay: 'list-item',
                eventSources: [{
                    url: calendarEventsUrl,
                    failure: () => alert('Veri hatası!')
                }, {
                    googleCalendarId: 'tr.turkish#holiday@group.v.calendar.google.com',
                    color: '#dc3545',
                    className: 'fc-event-holiday',
                    googleCalendarApiKey: 'AIzaSyAQmEWGR-krGzcCk1r8R69ER-NyZM2BeWM'
                }],
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.extendedProps && info.event.extendedProps.eventType)
                        openUniversalModal(info.event.extendedProps);
                },
                eventDidMount: function(info) {
                    if (info.event.extendedProps && info.event.extendedProps.is_important)
                        info.el.classList.add('event-important-pulse');

                    try {
                        let title = info.event.title;
                        let desc = '';
                        if (info.event.extendedProps?.details?.['Açıklama']) desc = info.event
                            .extendedProps.details['Açıklama'];
                        else if (info.event.start) {
                            let start = info.event.start.toLocaleTimeString('tr-TR', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            if (start !== '00:00') desc = `Saat: ${start}`;
                        }
                        let tooltipContent =
                            `<div class="text-start"><span class="tooltip-title-styled">${title}</span>${desc ? `<span class="tooltip-desc-styled">${desc}</span>` : ''}</div>`;
                        if (typeof bootstrap !== 'undefined') {
                            new bootstrap.Tooltip(info.el, {
                                title: tooltipContent,
                                html: true,
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body',
                                customClass: 'custom-calendar-tooltip',
                                delay: {
                                    "show": 100,
                                    "hide": 100
                                }
                            });
                        } else {
                            info.el.setAttribute('title', title + (desc ? ' - ' + desc : ''));
                        }
                    } catch (error) {
                        console.warn('Tooltip hatası:', error);
                    }
                }
            });

            calendar.render();
            setInterval(function() {
                console.log('Veriler arkaplanda güncelleniyor...');
                calendar.refetchEvents();
            }, 30000);

            // === YENİ EKLENEN KISIM: URL'DEN MODAL AÇMA ===
            // Veriler tam yüklendiğinde (Ajax veya Google Calendar fark etmez) tetiklenir
            function checkAndOpenModalFromUrl(events) {
                if (urlModalId && urlModalType) {
                    const idNum = parseInt(urlModalId, 10);
                    const foundEvent = events.find(e => {
                        // FullCalendar event objesi içinde extendedProps'a bakıyoruz
                        const props = e.extendedProps || {};
                        return props.id === idNum && props.model_type === urlModalType;
                    });

                    if (foundEvent) {
                        console.log('URL Modalı Bulundu:', foundEvent);
                        openUniversalModal(foundEvent.extendedProps);

                        // Modalı bir daha açmamak için URL'i temizle
                        const newUrl = window.location.pathname + window.location.search.replace(
                            /[\?&]open_modal_id=[^&]+/, '').replace(/[\?&]open_modal_type=[^&]+/, '');
                        window.history.replaceState({}, document.title, newUrl);
                    }
                }
            }

            // FullCalendar eventsSet hook'u yerine manuel bir listener ekleyebiliriz ama
            // en garantisi, veri kaynağı yüklendiğinde (loading: false olduğunda) kontrol etmektir.
            // Ancak FullCalendar v6'da bu biraz karmaşık olabilir. 
            // Basit çözüm: İlk yüklemeden sonra (render'dan hemen sonra) mevcut eventlere bakmak,
            // yetmezse 1-2 saniye sonra tekrar bakmak.

            // 1. Yöntem: Hemen bak (Eğer veriler HTML içinde geldiyse çalışır, AJAX ise boş döner)
            checkAndOpenModalFromUrl(calendar.getEvents());

            // 2. Yöntem: Loading bittiğinde tetiklenecek bir listener eklemek daha sağlıklı.
            // Ancak yukarıdaki konfigürasyonda 'loading' callback yok. 
            // O yüzden basit bir timeout ile deneyelim (Ajax bitişini beklemek için).
            setTimeout(() => {
                checkAndOpenModalFromUrl(calendar.getEvents());
            }, 1500); // 1.5 saniye bekle ve dene

            // === SON ===

            function applyCalendarFilters() {
                const isChecked = (id) => {
                    const el = document.getElementById(id);
                    return el ? el.checked : true;
                };
                const showLojistik = isChecked('filterLojistik');
                const showUretim = isChecked('filterUretim');
                const showHizmet = isChecked('filterHizmet');
                const showBakim = isChecked('filterBakim');
                const showImportant = isChecked('filterImportant');

                let eventSource = calendar.getEventSources()[0];
                if (eventSource) {
                    eventSource.remove();
                }

                calendar.addEventSource({
                    url: calendarEventsUrl,
                    extraParams: {
                        lojistik: showLojistik ? 1 : 0,
                        uretim: showUretim ? 1 : 0,
                        hizmet: showHizmet ? 1 : 0,
                        bakim: showBakim ? 1 : 0,
                        important_only: showImportant ? 1 : 0
                    },
                    // Ajax başarılı olduğunda da modal kontrolü yapalım
                    success: function(rawEvents) {
                        // FullCalendar rawEvents'i işlemeden önce buraya düşer,
                        // ama biz işlenmiş eventlere (getEvents) ihtiyaç duyuyoruz.
                        // O yüzden yine küçük bir timeout ile UI'ın oturmasını bekleyelim.
                        setTimeout(() => {
                            checkAndOpenModalFromUrl(calendar.getEvents());
                        }, 500);
                    }
                });
            }

            const filters = document.querySelectorAll('.calendar-filters .form-check-input');
            filters.forEach(filter => filter.addEventListener('change', applyCalendarFilters));

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
                    fetch(toggleImportantUrl, {
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
                        .then(data => {
                            if (data.success === false) {
                                alert('HATA: ' + (data.message || 'Yetkiniz yok.'));
                                this.checked = !isChecked;
                            } else {
                                calendar.refetchEvents();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Bir bağlantı hatası oluştu.');
                            this.checked = !isChecked;
                        })
                        .finally(() => {
                            this.disabled = false;
                        });
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/general-calendar.blade.php ENDPATH**/ ?>