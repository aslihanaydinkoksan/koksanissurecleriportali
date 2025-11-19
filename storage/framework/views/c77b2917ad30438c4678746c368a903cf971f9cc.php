
<?php $__env->startSection('title', 'Genel KÖKSAN Takvimi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
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

        .event-important-pulse {
            /* "Kutucuk" görünümü için bir kenarlık veya gölge */
            border: 2px solid #ff4136 !important;
            /* !important, fc-event'i ezmek için */
            box-shadow: 0 0 0 rgba(255, 65, 54, 0.4);
            /* Gölgenin başlangıç durumu */

            /* Animasyon tanımı */
            animation: pulse-animation 2s infinite;
        }

        /* Animasyon Keyframes */
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

        .fc-event-holiday {
            font-weight: 600;
            border: none !important;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
            border-radius: 4px;
            position: relative;
            overflow: hidden;

            /* Taşmayı önle */
            padding: 3px 6px;
            font-size: 11px;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;

            /* Hover efekti */
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
            /* Hover'ı engellemez */
        }

        /* Hover'da tam metin göster */
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

        /* Mobil için optimizasyon */
        @media (max-width: 768px) {
            .fc-event-holiday {
                font-size: 9px;
                padding: 2px 4px;
            }
        }

        /* Event'leri dengeli göster */
        .fc-timegrid-event {
            font-size: 0.85em !important;
            padding: 5px 8px !important;
            line-height: 1.3 !important;
            border-radius: 5px !important;
            font-weight: 600 !important;

            /* Minimum yükseklik - okunaklı */
            min-height: 30px !important;

            /* MAKSIMUM yükseklik - ~2 saat (event çok uzun olsa bile max bu kadar) */
            max-height: 65px !important;

            overflow: hidden !important;
            transition: all 0.2s ease !important;
        }

        /* Event başlığı - 2-3 satır göster */
        .fc-event-title {
            white-space: normal !important;
            overflow: hidden !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            /* Maksimum 2 satır başlık */
            -webkit-box-orient: vertical !important;
            line-height: 1.3 !important;
        }

        /* Event zamanı */
        .fc-event-time {
            font-size: 0.85em !important;
            font-weight: 700 !important;
            opacity: 0.95 !important;
            display: block !important;
            margin-bottom: 2px !important;
        }

        /* Event container */
        .fc-timegrid-event-harness {
            margin-bottom: 2px !important;
        }

        .fc-timegrid-event-harness-inset {
            /* Event'in maksimum yüksekliğini sınırla */
            max-height: 65px !important;
            overflow: hidden !important;
        }

        /* Slot yüksekliği - rahat görünsün */
        .fc-timegrid-slot {
            height: 2em !important;
        }

        /* Hover durumunda tam detayı göster */
        .fc-timegrid-event:hover {
            max-height: none !important;
            z-index: 1000 !important;
            transform: scale(1.05) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25) !important;
            -webkit-line-clamp: unset !important;
            cursor: pointer !important;
        }

        /* "+X more" linki */
        .fc-more-link {
            font-size: 0.8em !important;
            font-weight: 600 !important;
            color: #667EEA !important;
            background: rgba(102, 126, 234, 0.15) !important;
            padding: 4px 8px !important;
            border-radius: 4px !important;
            margin-top: 2px !important;
            display: inline-block !important;
        }

        .fc-more-link:hover {
            background: rgba(102, 126, 234, 0.25) !important;
            transform: translateY(-1px) !important;
        }

        /* Mobil optimizasyon */
        @media (max-width: 768px) {
            .fc-timegrid-event {
                font-size: 0.75em !important;
                padding: 4px 6px !important;
                min-height: 26px !important;
                max-height: 55px !important;
            }

            .fc-timegrid-slot {
                height: 1.75em !important;
            }
        }

        /* Modern Modal Overlay */
        .modal-backdrop.show {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Modern Modal Container */
        #detailModal .modal-dialog {
            max-width: 700px;
        }

        #detailModal .modal-content {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-40px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Modal Header - Gradient ve Modern */
        #detailModal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
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
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        #detailModal .btn-close {
            background: rgba(255, 255, 255, 0.25);
            opacity: 1;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        #detailModal .btn-close:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: rotate(90deg) scale(1.1);
        }

        /* Modal Body - Modern Kartlar */
        #detailModal .modal-body {
            padding: 2.5rem;
            color: #2d3748;
            background: #fafbfc;
        }

        #detailModal .modal-body .row {
            margin-bottom: 1rem;
        }

        #detailModal .modal-body p {
            margin-bottom: 1rem;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        #detailModal .modal-body strong {
            color: #667eea;
            font-weight: 700;
            display: inline-block;
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }

        /* İnfo Kartları */
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
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        /* Onay Badge - Modern */
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

        #modalOnayBadge strong {
            color: white;
            font-size: 1rem;
        }

        /* Önemli Checkbox Container */
        #modalImportantCheckboxContainer {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(255, 107, 107, 0.1));
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid rgba(220, 53, 69, 0.3);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        #modalImportantCheckboxContainer label {
            margin: 0;
            font-weight: 600;
            color: #dc3545;
            cursor: pointer;
        }

        #modalImportantCheckbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #dc3545;
        }

        /* HR Ayırıcı */
        #detailModal .modal-body hr {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
            margin: 2rem 0;
        }

        /* Tablo Stilleri */
        #detailModal .table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            margin-top: 1rem;
        }

        #detailModal .table thead {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
        }

        #detailModal .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        #detailModal .table tbody td {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem;
            vertical-align: middle;
        }

        #detailModal .table tbody tr:last-child td {
            border-bottom: none;
        }

        #detailModal .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        /* Notlar Bölümü */
        .modal-notes-box {
            background: linear-gradient(135deg, rgba(67, 233, 123, 0.08), rgba(56, 249, 215, 0.08));
            border-left: 4px solid #43e97b;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            color: #2d3748;
            line-height: 1.7;
        }

        .modal-notes-title {
            font-weight: 700;
            color: #43e97b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Modal Footer - Modern Butonlar */
        #detailModal .modal-footer {
            background: white;
            border: none;
            padding: 1.5rem 2.5rem;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Buton Stilleri */
        #detailModal .btn {
            border-radius: 12px;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        #detailModal .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        #detailModal .btn:hover::before {
            left: 100%;
        }

        #detailModal .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        #detailModal .btn:active {
            transform: translateY(0);
        }

        /* Düzenle Butonu */
        #modalEditButton {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 167, 38, 0.4);
        }

        #modalEditButton:hover {
            box-shadow: 0 8px 25px rgba(255, 167, 38, 0.5);
        }

        /* Export Butonu */
        #modalExportButton {
            background: linear-gradient(135deg, #4fd1c5, #38b2ac);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 209, 197, 0.4);
        }

        #modalExportButton:hover {
            box-shadow: 0 8px 25px rgba(79, 209, 197, 0.5);
        }

        /* Onay Butonları */
        #modalOnayForm .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.4);
        }

        #modalOnayForm .btn-success:hover {
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.5);
        }

        #modalOnayKaldirForm .btn-warning {
            background: linear-gradient(135deg, #f6ad55, #ed8936);
            color: white;
            box-shadow: 0 4px 15px rgba(246, 173, 85, 0.4);
        }

        #modalOnayKaldirForm .btn-warning:hover {
            box-shadow: 0 8px 25px rgba(246, 173, 85, 0.5);
        }

        /* Sil Butonu */
        #modalDeleteForm .btn-danger {
            background: linear-gradient(135deg, #fc8181, #f56565);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
        }

        #modalDeleteForm .btn-danger:hover {
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.5);
        }

        /* Kapat Butonu */
        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
            box-shadow: 0 4px 15px rgba(113, 128, 150, 0.4);
        }

        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(113, 128, 150, 0.5);
        }

        /* Dosya Görüntüle Butonu */
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            font-weight: 700;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: transparent;
            color: white;
        }

        /* Responsive Optimizasyon */
        @media (max-width: 768px) {
            #detailModal .modal-dialog {
                margin: 1rem;
            }

            #detailModal .modal-content {
                border-radius: 16px;
            }

            #detailModal .modal-header {
                padding: 1.5rem;
            }

            #detailModal .modal-title {
                font-size: 1.35rem;
            }

            #detailModal .modal-body {
                padding: 1.5rem;
            }

            #detailModal .modal-footer {
                padding: 1rem 1.5rem;
            }

            #detailModal .btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.8rem;
            }

            .modal-info-card {
                padding: 1.25rem;
            }
        }

        /* Loading Spinner için */
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
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card create-shipment-card">
                    <div class="card-header">
                        📅 Genel KÖKSAN Takvimi
                    </div>
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
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-info-circle"></i>
                        <span>Detaylar</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <!-- Onay Badge (Sevkiyat için) -->
                    <div id="modalOnayBadge" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <strong>✓ Onaylandı</strong>
                                <div class="small mt-1">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    <span id="modalOnayBadgeTarih"></span>
                                </div>
                                <div class="small">
                                    <i class="fas fa-user me-1"></i>
                                    <span id="modalOnayBadgeKullanici"></span>
                                </div>
                            </div>
                            <i class="fas fa-check-circle fa-3x" style="opacity: 0.5;"></i>
                        </div>
                    </div>

                    <!-- Önemli Checkbox (Yönetici için) -->
                    <div id="modalImportantCheckboxContainer" style="display: none;">
                        <input type="checkbox" id="modalImportantCheckbox" class="form-check-input">
                        <label for="modalImportantCheckbox" class="form-check-label">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Bu Etkinliği Önemli Olarak İşaretle
                        </label>
                    </div>

                    <!-- Dinamik İçerik -->
                    <div id="modalDynamicBody">
                        <!-- JavaScript ile doldurulacak -->
                        <div class="modal-loading">
                            <div class="modal-spinner"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <!-- Düzenle Butonu -->
                    <a href="#" id="modalEditButton" class="btn" style="display: none;">
                        <i class="fas fa-edit me-2"></i> Düzenle
                    </a>

                    <!-- Excel Export Butonu -->
                    <a href="#" id="modalExportButton" class="btn" style="display: none;">
                        <i class="fas fa-file-excel me-2"></i> Excel İndir
                    </a>

                    <!-- Onay Formu -->
                    <form method="POST" id="modalOnayForm" style="display: none;" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i> Tesise Ulaştı
                        </button>
                    </form>

                    <!-- Onay Kaldırma Formu -->
                    <form method="POST" id="modalOnayKaldirForm" style="display: none;" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-undo me-2"></i> Onayı Kaldır
                        </button>
                    </form>

                    <!-- Silme Formu -->
                    <form method="POST" id="modalDeleteForm" style="display: none;" class="d-inline"
                        onsubmit="return confirm('Bu kaydı silmek istediğinizden emin misiniz?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i> Sil
                        </button>
                    </form>

                    <!-- Kapat Butonu -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Kapat
                    </button>
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
            var detailModalElement = document.getElementById('detailModal');
            if (!detailModalElement) {
                console.error("Hata: 'detailModal' elementi bulunamadı!");
                return;
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
            const modalImportantContainer = document.getElementById('modalImportantCheckboxContainer');
            const modalImportantCheckbox = document.getElementById('modalImportantCheckbox');

            var calendarElGlobal = document.getElementById('calendar'); // Global referans
            const currentUserId = parseInt(calendarElGlobal.dataset.currentUserId, 10);
            const isAuthorized = calendarElGlobal.dataset.isAuthorized === 'true';

            const urlParams = new URLSearchParams(window.location.search);
            const dateFromUrl = urlParams.get('date');

            // === YARDIMCI FONKSİYON: Tarih/Saat Ayırıcı ===
            /**
             * Bir tarih-saat dizesini (örn: "19.05.2025 11:30") 
             * tarih ve saat olarak ayırır.
             * @param {string} dateTimeString - Ayırılacak dize.
             * @returns {{ date: string, time: string }}
             */
            function splitDateTime(dateTimeString) {
                const dt = String(dateTimeString || '');
                const parts = dt.split(' ');
                const date = parts[0] || '-';
                let time = parts[1] || '-';
                if (date === '-' || time === '') {
                    time = '-';
                }

                return {
                    date: date,
                    time: time
                };
            }

            // openUniversalModal fonksiyonunu şu şekilde güncelleyin:

            function openUniversalModal(props) {
                console.log('--- MODAL PROPS GELDİ ---', props);
                if (!props || !props.eventType) {
                    console.error("Modal için geçersiz veri:", props);
                    return;
                }

                // Önemli checkbox ayarları
                if (isAuthorized) {
                    modalImportantContainer.style.display = 'block';
                    modalImportantCheckbox.checked = props.is_important || false;
                    modalImportantCheckbox.dataset.modelType = props.model_type;
                    modalImportantCheckbox.dataset.modelId = props.id;
                } else {
                    modalImportantContainer.style.display = 'none';
                }

                // Başlık ikonları
                const iconMap = {
                    'shipment': 'fa-truck',
                    'production': 'fa-industry',
                    'service_event': 'fa-calendar-star',
                    'vehicle_assignment': 'fa-car',
                    'travel': 'fa-plane-departure'
                };

                const icon = iconMap[props.eventType] || 'fa-info-circle';
                modalTitle.innerHTML = `<i class="fas ${icon}"></i> <span>${props.title || 'Detaylar'}</span>`;

                // Buton görünürlük kontrolü
                let showButtons = false;
                if (props.eventType === 'production' || props.eventType === 'service_event' || props.eventType ===
                    'vehicle_assignment') {
                    if (isAuthorized) {
                        showButtons = true;
                    } else if (props.user_id) {
                        showButtons = (props.user_id === currentUserId);
                    } else {
                        showButtons = false;
                    }
                } else {
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

                let html = '';

                // === SEVKİYAT (SHIPMENT) ===
                if (props.eventType === 'shipment') {
                    modalExportButton.href = props.exportUrl || '#';
                    modalExportButton.style.display = 'inline-block';

                    // Onay durumu
                    if (props.details['Onay Durumu']) {
                        modalOnayForm.style.display = 'none';
                        if (modalOnayKaldirForm) {
                            modalOnayKaldirForm.action = props.onayKaldirUrl;
                            modalOnayKaldirForm.style.display = 'inline-block';
                        }
                        modalOnayBadge.style.display = 'block';
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

                    // Araç Bilgileri Kartı
                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-truck me-2"></i>Araç Bilgileri</h6>';
                    html += '<div class="row">';
                    html +=
                        `<div class="col-md-6"><p><strong>🚛 Araç Tipi:</strong> ${props.details['Araç Tipi'] || '-'}</p></div>`;

                    if (!isGemi) {
                        html +=
                            `<div class="col-md-6"><p><strong>🔢 Plaka:</strong> ${props.details['Plaka'] || '-'}</p></div>`;
                        html +=
                            `<div class="col-md-6"><p><strong>🔢 Dorse Plakası:</strong> ${props.details['Dorse Plakası'] || '-'}</p></div>`;
                        html +=
                            `<div class="col-md-6"><p><strong>👨‍✈️ Şoför Adı:</strong> ${props.details['Şoför Adı'] || '-'}</p></div>`;
                    } else {
                        html +=
                            `<div class="col-md-6"><p><strong>⚓ IMO Numarası:</strong> ${props.details['IMO Numarası'] || '-'}</p></div>`;
                        html +=
                            `<div class="col-md-6"><p><strong>🚢 Gemi Adı:</strong> ${props.details['Gemi Adı'] || '-'}</p></div>`;
                    }
                    html += '</div></div>';

                    // Rota Bilgileri Kartı
                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-route me-2"></i>Rota Bilgileri</h6>';
                    html += '<div class="row">';

                    if (!isGemi) {
                        html +=
                            `<div class="col-md-6"><p><strong>📍 Kalkış Noktası:</strong> ${props.details['Kalkış Noktası'] || '-'}</p></div>`;
                        html +=
                            `<div class="col-md-6"><p><strong>📍 Varış Noktası:</strong> ${props.details['Varış Noktası'] || '-'}</p></div>`;
                    } else {
                        html +=
                            `<div class="col-md-6"><p><strong>🏁 Kalkış Limanı:</strong> ${props.details['Kalkış Limanı'] || '-'}</p></div>`;
                        html +=
                            `<div class="col-md-6"><p><strong>🎯 Varış Limanı:</strong> ${props.details['Varış Limanı'] || '-'}</p></div>`;
                    }

                    html +=
                        `<div class="col-md-12"><p><strong>🔄 Sevkiyat Türü:</strong> ${props.details['Sevkiyat Türü'] || '-'}</p></div>`;
                    html += '</div></div>';

                    // Kargo Bilgileri Kartı
                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-box me-2"></i>Kargo Bilgileri</h6>';
                    html += `<p><strong>📦 Kargo Yükü:</strong> ${props.details['Kargo Yükü'] || '-'}</p>`;
                    html += '<div class="row">';
                    html +=
                        `<div class="col-md-6"><p><strong>🏷️ Kargo Tipi:</strong> ${props.details['Kargo Tipi'] || '-'}</p></div>`;
                    html +=
                        `<div class="col-md-6"><p><strong>⚖️ Kargo Miktarı:</strong> ${props.details['Kargo Miktarı'] || '-'}</p></div>`;
                    html += '</div></div>';

                    // Zaman Bilgileri Kartı
                    const cikis = splitDateTime(props.details['Çıkış Tarihi']);
                    const varis = splitDateTime(props.details['Tahmini Varış']);

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-clock me-2"></i>Zaman Bilgileri</h6>';
                    html += '<div class="row">';
                    html += '<div class="col-md-6">';
                    html += `<p><strong>📅 Çıkış Tarihi:</strong> ${cikis.date}</p>`;
                    if (cikis.time !== '-') {
                        html += `<p><strong>🕒 Çıkış Saati:</strong> ${cikis.time}</p>`;
                    }
                    html += '</div>';
                    html += '<div class="col-md-6">';
                    html += `<p><strong>📅 Tahmini Varış:</strong> ${varis.date}</p>`;
                    if (varis.time !== '-') {
                        html += `<p><strong>🕒 Varış Saati:</strong> ${varis.time}</p>`;
                    }
                    html += '</div>';
                    html += '</div></div>';
                }
                // === ÜRETİM (PRODUCTION) ===
                else if (props.eventType === 'production') {
                    modalExportButton.style.display = 'none';
                    modalOnayForm.style.display = 'none';
                    if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                    modalOnayBadge.style.display = 'none';

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-industry me-2"></i>Üretim Planı Bilgileri</h6>';
                    html += `<p><strong>📝 Plan Başlığı:</strong> ${props.details['Plan Başlığı'] || '-'}</p>`;
                    html +=
                        `<p><strong>📅 Hafta Başlangıcı:</strong> ${props.details['Hafta Başlangıcı'] || '-'}</p>`;
                    html += `<p><strong>👤 Oluşturan:</strong> ${props.details['Oluşturan'] || '-'}</p>`;
                    html += '</div>';

                    if (props.details['Plan Detayları'] && props.details['Plan Detayları'].length > 0) {
                        html += '<div class="modal-info-card">';
                        html +=
                            '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-list-check me-2"></i>Plan Detayları</h6>';
                        html += '<table class="table table-sm mb-0">';
                        html += '<thead><tr><th>⚙️ Makine</th><th>📦 Ürün</th><th>🧮 Adet</th></tr></thead><tbody>';
                        props.details['Plan Detayları'].forEach(item => {
                            html +=
                                `<tr><td>${item.machine || '-'}</td><td>${item.product || '-'}</td><td>${item.quantity || '-'}</td></tr>`;
                        });
                        html += '</tbody></table></div>';
                    }
                }
                // === HİZMET ETKİNLİĞİ (SERVICE EVENT) ===
                else if (props.eventType === 'service_event') {
                    modalExportButton.style.display = 'none';
                    modalOnayForm.style.display = 'none';
                    if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                    modalOnayBadge.style.display = 'none';

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-calendar-star me-2"></i>Etkinlik Bilgileri</h6>';
                    html += `<p><strong>🎯 Etkinlik Tipi:</strong> ${props.details['Etkinlik Tipi'] || '-'}</p>`;
                    html += `<p><strong>📍 Konum:</strong> ${props.details['Konum'] || '-'}</p>`;
                    html += '</div>';

                    const baslangic = splitDateTime(props.details['Başlangıç']);
                    const bitis = splitDateTime(props.details['Bitiş']);

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-clock me-2"></i>Zaman Bilgileri</h6>';
                    html += '<div class="row">';
                    html += '<div class="col-md-6">';
                    html += `<p><strong>📅 Başlangıç Tarihi:</strong> ${baslangic.date}</p>`;
                    if (baslangic.time !== '-') {
                        html += `<p><strong>🕒 Başlangıç Saati:</strong> ${baslangic.time}</p>`;
                    }
                    html += '</div>';
                    html += '<div class="col-md-6">';
                    html += `<p><strong>📅 Bitiş Tarihi:</strong> ${bitis.date}</p>`;
                    if (bitis.time !== '-') {
                        html += `<p><strong>🕒 Bitiş Saati:</strong> ${bitis.time}</p>`;
                    }
                    html += '</div>';
                    html += '</div>';
                    html +=
                        `<p class="mt-2"><strong>👩‍💻 Kayıt Yapan:</strong> ${props.details['Kayıt Yapan'] || '-'}</p>`;
                    html += '</div>';
                }
                // === ARAÇ ATAMA (VEHICLE ASSIGNMENT) ===
                else if (props.eventType === 'vehicle_assignment') {
                    modalExportButton.style.display = 'none';
                    modalOnayForm.style.display = 'none';
                    if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                    modalOnayBadge.style.display = 'none';

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-car me-2"></i>Görev Bilgileri</h6>';
                    html += `<p><strong>🚘 Araç:</strong> ${props.details['Araç'] || '-'}</p>`;
                    html += `<p><strong>📋 Görev:</strong> ${props.details['Görev'] || '-'}</p>`;
                    html += `<p><strong>📍 Yer:</strong> ${props.details['Yer'] || '-'}</p>`;
                    html += `<p><strong>👤 Talep Eden:</strong> ${props.details['Talep Eden'] || '-'}</p>`;
                    html += '</div>';

                    const baslangic = splitDateTime(props.details['Başlangıç']);
                    const bitis = splitDateTime(props.details['Bitiş']);

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-clock me-2"></i>Zaman Bilgileri</h6>';
                    html += '<div class="row">';
                    html += '<div class="col-md-6">';
                    html += `<p><strong>📅 Başlangıç Tarihi:</strong> ${baslangic.date}</p>`;
                    if (baslangic.time !== '-') {
                        html += `<p><strong>🕒 Başlangıç Saati:</strong> ${baslangic.time}</p>`;
                    }
                    html += '</div>';
                    html += '<div class="col-md-6">';
                    html += `<p><strong>📅 Bitiş Tarihi:</strong> ${bitis.date}</p>`;
                    if (bitis.time !== '-') {
                        html += `<p><strong>🕒 Bitiş Saati:</strong> ${bitis.time}</p>`;
                    }
                    html += '</div>';
                    html += '</div>';
                    html +=
                        `<p class="mt-2"><strong>👩‍💻 Kayıt Yapan:</strong> ${props.details['Kayıt Yapan'] || '-'}</p>`;
                    html += '</div>';
                }
                // === SEYAHAT (TRAVEL) ===
                else if (props.eventType === 'travel') {
                    modalExportButton.style.display = 'none';
                    modalOnayForm.style.display = 'none';
                    if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                    modalOnayBadge.style.display = 'none';

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-plane-departure me-2"></i>Seyahat Bilgileri</h6>';
                    html += `<p><strong>✈️ Plan Adı:</strong> ${props.details['Plan Adı'] || '-'}</p>`;
                    html += `<p><strong>👤 Oluşturan:</strong> ${props.details['Oluşturan'] || '-'}</p>`;
                    html += `<p><strong>📅 Başlangıç:</strong> ${props.details['Başlangıç'] || '-'}</p>`;
                    html += `<p><strong>📅 Bitiş:</strong> ${props.details['Bitiş'] || '-'}</p>`;
                    html += `<p><strong>📊 Durum:</strong> ${props.details['Durum'] || '-'}</p>`;
                    html += '</div>';

                    if (props.url) {
                        modalExportButton.href = props.url;
                        modalExportButton.target = "_blank";
                        modalExportButton.innerHTML =
                            '<i class="fas fa-plane-departure me-2"></i> Seyahat Detayına Git';
                        modalExportButton.style.display = 'inline-block';
                    }
                }

                // Notlar / Açıklamalar
                const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
                    'Açıklama'];
                if (aciklama) {
                    html += '<div class="modal-notes-box">';
                    html +=
                        '<div class="modal-notes-title"><i class="fas fa-sticky-note"></i> Notlar / Açıklamalar</div>';
                    html += `<p class="mb-0">${aciklama}</p>`;
                    html += '</div>';
                }

                // Ek Dosya (Sevkiyat için)
                if (props.eventType === 'shipment' && props.details['Dosya Yolu']) {
                    html += '<div class="text-center mt-3">';
                    html +=
                        `<a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary">`;
                    html += '<i class="fas fa-paperclip me-2"></i> Ek Dosyayı Görüntüle / İndir';
                    html += '</a></div>';
                }

                modalBody.innerHTML = html;
                detailModal.show();
            }

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: dateFromUrl || new Date(),
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
                // === HYBRİD ÇÖZÜM AYARLARI ===
                slotEventOverlap: false,
                dayMaxEvents: 5,
                eventMaxStack: 3,
                slotDuration: '00:30:00',
                height: 'auto',
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                scrollTime: '08:00:00',
                nowIndicator: true,
                displayEventEnd: true,
                eventSources: [{
                        url: '<?php echo e(route('web.calendar.events')); ?>',
                        failure: function() {
                            alert('Veritabanı olayları yüklenirken bir hata oluştu!');
                        }
                    },
                    // 2. Kaynak: Türkiye Resmi Tatilleri (API Anahtarınızla)
                    {
                        googleCalendarId: 'tr.turkish#holiday@group.v.calendar.google.com',
                        color: '#dc3545',
                        textColor: 'white',
                        className: 'fc-event-holiday',
                        googleCalendarApiKey: 'AIzaSyAQmEWGR-krGzcCk1r8R69ER-NyZM2BeWM'
                    }
                ],
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
                    if (info.event.extendedProps && info.event.extendedProps.eventType) {
                        openUniversalModal(info.event.extendedProps);
                    }
                },
                eventDidMount: function(info) {
                    if (info.event.extendedProps.is_important) {
                        info.el.classList.add('event-important-pulse');
                    }
                },
                eventsSet: function(info) {
                    // DÜZELTME 1: Doğru parametre adlarını al
                    const modalIdToOpen = urlParams.get('open_modal_id');
                    const modalTypeToOpen = urlParams.get('open_modal_type');

                    // İki parametre de doluysa devam et
                    if (modalIdToOpen && modalTypeToOpen) {
                        const allEvents = calendar.getEvents();
                        const modalIdNum = parseInt(modalIdToOpen, 10);

                        // DÜZELTME 2: Sadece ID'yi değil, HEM ID'yi HEM de TİP'i kontrol et
                        const eventToOpen = allEvents.find(event =>
                            event.extendedProps.id === modalIdNum &&
                            event.extendedProps.model_type === modalTypeToOpen
                        );

                        if (eventToOpen) {
                            console.log('URL\'den modal tetikleniyor:', eventToOpen.extendedProps);
                            openUniversalModal(eventToOpen.extendedProps);
                        } else {
                            console.warn('Modal açılmak istendi ancak ' + modalTypeToOpen + ' (ID:' +
                                modalIdNum + ') takvimde bulunamadı.');
                        }
                        // URL'yi temizle (sayfa yenilenirse tekrar açılmasın)
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                }
            });
            calendar.render();

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
            if (modalImportantCheckbox) {
                modalImportantCheckbox.addEventListener('change', function() {
                    const modelId = this.dataset.modelId;
                    const modelType = this.dataset.modelType;
                    const isChecked = this.checked;

                    // Spinner veya 'kaydediliyor' durumu eklenebilir
                    this.disabled = true;

                    fetch('<?php echo e(route('calendar.toggleImportant')); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(), // CSRF token'ı
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                model_id: modelId,
                                model_type: modelType,
                                is_important: isChecked
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Yetkilendirme hatası veya sunucu cevap vermiyor.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Güncelleme başarılı:', data.message);

                            // BU ÇOK ÖNEMLİ: Takvimi yenile ki pulse animasyonu gelsin/gitsin
                            calendar.refetchEvents();
                        })
                        .catch(error => {
                            console.error('Hata:', error);
                            alert('Bir hata oluştu, değişiklik geri alınıyor.');
                            // Hata olursa checkbox'ı eski haline getir
                            this.checked = !isChecked;
                        })
                        .finally(() => {
                            // İşlem bitince checkbox'ı tekrar aktif et
                            this.disabled = false;
                        });
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/general-calendar.blade.php ENDPATH**/ ?>