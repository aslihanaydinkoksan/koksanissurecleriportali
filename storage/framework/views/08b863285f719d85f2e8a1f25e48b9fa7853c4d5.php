

<?php $__env->startSection('title', 'Benim Takvimim'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
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

        .create-shipment-card {
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
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
            color: #2d3748;
        }

        .create-shipment-card .form-label {
            color: #2d3748;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .create-shipment-card .form-control,
        .create-shipment-card .form-select {
            border-radius: 0.75rem;
            background-color: rgba(255, 255, 255, 0.95);
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .create-shipment-card .form-control:focus,
        .create-shipment-card .form-select:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }


        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 700;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 1rem;
            border: none;
            padding: 1rem 1.25rem;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.4s ease;
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


        #calendar {
            background: transparent;
            border-radius: 0;
            padding: 0;
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
        }

        .fc .fc-button-primary:hover {
            background: linear-gradient(135deg, #764BA2, #667EEA);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: linear-gradient(135deg, #764BA2, #667EEA);
        }

        .fc-event {
            border-radius: 0.5rem;
            border: none;
            padding: 2px 6px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            user-select: none;
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
            font-weight: 700;
            color: #2d3748;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background: rgba(102, 126, 234, 0.1) !important;
        }


        .table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table thead th {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            color: #2d3748;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .table tbody tr {
            background: rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr td:first-child {
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .table tbody tr td:last-child {
            border-radius: 0 0.5rem 0.5rem 0;
        }


        .badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }

        .bg-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2) !important;
        }

        .bg-info {
            background: linear-gradient(135deg, #4FD1C5, #38B2AC) !important;
        }

        .bg-secondary {
            background: linear-gradient(135deg, #718096, #4a5568) !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #48BB78, #38A169) !important;
        }

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

        .btn-info {
            background: linear-gradient(135deg, #4FD1C5, #38B2AC);
            color: white !important;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #F6AD55, #ED8936);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #48BB78, #38A169);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #FC8181, #F56565);
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid #667EEA;
            color: #667EEA;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border-color: #667EEA;
            color: white;
        }


        .modal-content {
            border-radius: 1.25rem;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .modal-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 1.25rem 1.25rem 0 0;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 700;
            color: #2d3748;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
            color: #2d3748;
        }

        .modal-body p {
            margin-bottom: 0.75rem;
            line-height: 1.8;
        }

        .modal-body strong {
            color: #667EEA;
            font-weight: 700;
            display: inline-block;
            min-width: 180px;
        }

        .modal-body hr {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
            margin: 1.5rem 0;
        }

        .modal-footer {
            border-top: 2px solid rgba(102, 126, 234, 0.1);
            padding: 1.5rem;
            background: rgba(249, 250, 251, 0.5);
            border-radius: 0 0 1.25rem 1.25rem;
        }


        #stats-card-body {
            padding: 1.5rem;
        }

        #stats-card-body a {
            color: #667EEA;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        #stats-card-body a:hover {
            color: #764BA2;
            transform: translateX(5px);
        }

        #stats-card-body a::after {
            content: '→';
            font-size: 1.2rem;
            transition: transform 0.2s ease;
        }

        #stats-card-body a:hover::after {
            transform: translateX(3px);
        }


        @media (max-width: 768px) {
            .create-shipment-card .card-header {
                font-size: 1rem;
                padding: 1rem;
            }

            .modal-body strong {
                min-width: 120px;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }


        html {
            scroll-behavior: smooth;
        }


        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner-border {
            animation: spin 0.75s linear infinite;
        }

        .event-important-pulse {
            border: 2px solid #ff4136 !important;
            box-shadow: 0 0 0 rgba(255, 65, 54, 0.4);
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

        @media (max-width: 768px) {
            .fc-event-holiday {
                font-size: 9px;
                padding: 2px 4px;
            }
        }

        .wide-container {
            max-width: 1600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Event'leri dengeli göster */
        .fc-timegrid-event {
            font-size: 0.85em !important;
            padding: 5px 8px !important;
            line-height: 1.3 !important;
            border-radius: 5px !important;
            font-weight: 600 !important;
            min-height: 30px !important;
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
            -webkit-box-orient: vertical !important;
            line-height: 1.3 !important;
        }

        .fc-event-time {
            font-size: 0.85em !important;
            font-weight: 700 !important;
            opacity: 0.95 !important;
            display: block !important;
            margin-bottom: 2px !important;
        }

        .fc-timegrid-event-harness {
            margin-bottom: 2px !important;
        }

        .fc-timegrid-event-harness-inset {
            max-height: 65px !important;
            overflow: hidden !important;
        }

        .fc-timegrid-slot {
            height: 2em !important;
        }

        .fc-timegrid-event:hover {
            max-height: none !important;
            z-index: 1000 !important;
            transform: scale(1.05) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25) !important;
            -webkit-line-clamp: unset !important;
            cursor: pointer !important;
        }

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

        #detailModal .modal-body hr {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
            margin: 2rem 0;
        }

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

        #detailModal .modal-footer {
            background: white;
            border: none;
            padding: 1.5rem 2.5rem;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

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

        #modalEditButton {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 167, 38, 0.4);
        }

        #modalEditButton:hover {
            box-shadow: 0 8px 25px rgba(255, 167, 38, 0.5);
        }

        #modalExportButton {
            background: linear-gradient(135deg, #4fd1c5, #38b2ac);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 209, 197, 0.4);
        }

        #modalExportButton:hover {
            box-shadow: 0 8px 25px rgba(79, 209, 197, 0.5);
        }

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

        #modalDeleteForm .btn-danger {
            background: linear-gradient(135deg, #fc8181, #f56565);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
        }

        #modalDeleteForm .btn-danger:hover {
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.5);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
            box-shadow: 0 4px 15px rgba(113, 128, 150, 0.4);
        }

        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(113, 128, 150, 0.5);
        }

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
    <div class="container-fluid wide-container">

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✓</strong> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>✗</strong> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-9">
                <div class="card create-shipment-card">
                    <div class="card-header">
                        📅 <?php echo e($departmentName); ?> Takvimi
                    </div>
                    <div class="card-body">
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
                                <input class="form-check-input" type="checkbox" value="uretim" id="filterUretim" checked>
                                <label class="form-check-label" for="filterUretim" style="color: #4FD1C5;">Üretim</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="hizmet" id="filterHizmet" checked>
                                <label class="form-check-label" for="filterHizmet" style="color: #F093FB;">İdari
                                    İşler</label>
                            </div>

                            <span class="mx-2">|</span>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="important" id="filterImportant">
                                <label class="form-check-label" for="filterImportant" style="color: #dc3545;"><strong>Sadece
                                        Önemliler</strong></label>
                            </div>
                        </div>

                        <div id="calendar" data-events='<?php echo json_encode($events, 15, 512) ?>'
                            data-is-authorized="<?php echo e(in_array(Auth::user()->role, ['admin', 'yönetici']) ? 'true' : 'false'); ?>"
                            data-current-user-id="<?php echo e(Auth::id()); ?>">
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('is-global-manager')): ?>
                    <div class="card create-shipment-card mb-3">
                        <div class="card-header">⚡ <?php echo e(__('Hızlı Eylemler')); ?></div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-animated-gradient"
                                    style="text-transform: none; color:#fff">👤 Yeni Kullanıcı Ekle</a>
                                <button type="button" class="btn btn-info text-white" id="toggleUsersButton">👥 Mevcut
                                    Kullanıcıları Görüntüle</button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(!empty($chartData)): ?>
                    <div class="card create-shipment-card">
                        <div class="card-header">
                            📊 <?php echo e($statsTitle); ?>

                        </div>
                        <div class="card-body" id="stats-card-body">
                            <?php if($departmentSlug === 'lojistik'): ?>
                                <div id="hourly-chart-lojistik"></div>
                                <hr>
                                <div id="daily-chart-lojistik"></div>
                            <?php elseif($departmentSlug === 'uretim'): ?>
                                <div id="weekly-plans-chart"></div>
                                <hr>
                                <p class="text-muted text-center small mt-3">Yakında daha fazla üretim istatistiği
                                    eklenecektir.</p>
                            <?php elseif($departmentSlug === 'hizmet'): ?>
                                <div id="daily-events-chart"></div>
                                <hr>
                                <div id="daily-assignments-chart"></div>
                            <?php else: ?>
                                <p class="text-center">Bu departman için özel istatistikler henüz tanımlanmamıştır.</p>
                            <?php endif; ?>

                            <?php if(Route::has('statistics.index')): ?>
                                <hr>
                                <div class="text-center">
                                    <a href="<?php echo e(route('statistics.index')); ?>">Daha Fazla İstatistik Görüntüle</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <?php if(in_array(Auth::user()->role, ['admin', 'yönetici'])): ?>
            <div class="row mt-4" id="userListContainer" style="display: none;">
                <div class="col-md-9">
                    <div class="card create-shipment-card">
                        <div class="card-header">
                            👥 <?php echo e(__('Sistemdeki Mevcut Kullanıcılar')); ?>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ad Soyad</th>
                                            <th scope="col">E-posta</th>
                                            <th scope="col">Rol</th>
                                            <th scope="col">Birim</th>
                                            <th scope="col">Kayıt Tarihi</th>
                                            <th scope="col">Eylemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <th scope="row"><?php echo e($user->id); ?></th>
                                                <td><?php echo e($user->name); ?></td>
                                                <td><?php echo e($user->email); ?></td>
                                                <?php
                                                    $roleClass = 'bg-secondary';
                                                    if ($user->role === 'admin') {
                                                        $roleClass = 'bg-primary';
                                                    }
                                                    if ($user->role === 'yönetici') {
                                                        $roleClass = 'bg-info';
                                                    }
                                                ?>
                                                <td><span
                                                        class="badge <?php echo e($roleClass); ?>"><?php echo e(ucfirst($user->role)); ?></span>
                                                </td>
                                                <td>
                                                    
                                                    <?php echo e($user->department?->name ?? '-'); ?>

                                                </td>
                                                <td><?php echo e($user->created_at->format('d/m/Y H:i')); ?></td>
                                                <td>
                                                    <div class="d-flex flex-column gap-1">
                                                        <?php if(Auth::user()->role === 'admin' || $user->role !== 'admin'): ?>
                                                            <a href="<?php echo e(route('users.edit', $user->id)); ?>"
                                                                class="btn btn-sm btn-secondary">✏️ Düzenle</a>
                                                        <?php endif; ?>
                                                        <?php if(Auth::user()->role === 'admin' && Auth::user()->id !== $user->id && $user->role !== 'admin'): ?>
                                                            <form action="<?php echo e(route('users.destroy', $user->id)); ?>"
                                                                method="POST"
                                                                onsubmit="return confirm('<?php echo e($user->name); ?> adlı kullanıcıyı silmek istediğinizden emin misiniz?');">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="btn btn-sm btn-danger">🗑️
                                                                    Sil</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Sistemde gösterilecek kullanıcı
                                                    bulunamadı.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        <?php endif; ?>
    </div>
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-info-circle"></i>
                        <span>Detaylar</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
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

                    <div id="modalImportantCheckboxContainer" style="display: none;">
                        <input type="checkbox" id="modalImportantCheckbox" class="form-check-input">
                        <label for="modalImportantCheckbox" class="form-check-label">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Bu Etkinliği Önemli Olarak İşaretle
                        </label>
                    </div>

                    <div id="modalDynamicBody">
                        <div class="modal-loading">
                            <div class="modal-spinner"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" id="modalEditButton" class="btn" style="display: none;">
                        <i class="fas fa-edit me-2"></i> Düzenle
                    </a>

                    <a href="#" id="modalExportButton" class="btn" style="display: none;">
                        <i class="fas fa-file-excel me-2"></i> Excel İndir
                    </a>

                    <form method="POST" id="modalOnayForm" style="display: none;" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i> Tesise Ulaştı
                        </button>
                    </form>

                    <form method="POST" id="modalOnayKaldirForm" style="display: none;" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-undo me-2"></i> Onayı Kaldır
                        </button>
                    </form>

                    <form method="POST" id="modalDeleteForm" style="display: none;" class="d-inline"
                        onsubmit="return confirm('Bu kaydı silmek istediğinizden emin misiniz?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i> Sil
                        </button>
                    </form>

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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.13/index.global.min.js'></script>
    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const colorPalette = ['#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'];

            var calendarEl = document.getElementById('calendar');
            const isAuthorized = calendarEl.dataset.isAuthorized === 'true';
            const currentUserId = parseInt(calendarEl.dataset.currentUserId, 10);
            const eventsData = JSON.parse(calendarEl.dataset.events || '[]');
            const appTimezone = calendarEl.dataset.timezone;

            var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
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

            // === YARDIMCI FONKSİYON: Tarih/Saat Ayırıcı ===
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

            // === YENİ: MODAL UI SIFIRLAMA (HARD RESET) ===
            function hardResetModalUI() {
                const idsToHide = [
                    'modalEditButton',
                    'modalExportButton',
                    'modalOnayForm',
                    'modalOnayKaldirForm',
                    'modalDeleteForm',
                    'modalOnayBadge',
                    'modalImportantCheckboxContainer'
                ];

                idsToHide.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.style.display = 'none';
                        el.classList.remove('d-inline', 'd-block');
                    }
                });

                document.getElementById('modalTitle').innerHTML = '';
                document.getElementById('modalDynamicBody').innerHTML =
                    '<div class="modal-loading"><div class="modal-spinner"></div></div>';

                // Export butonu ikonunu varsayılana (Excel) döndür
                const exportBtn = document.getElementById('modalExportButton');
                if (exportBtn) exportBtn.innerHTML = '<i class="fas fa-file-excel me-2"></i> Excel İndir';
            }

            // === MODAL AÇMA FONKSİYONU ===
            function openUniversalModal(props) {
                console.log('--- MODAL AÇILIYOR (HOME) ---', props.eventType);

                // 1. Temizlik
                hardResetModalUI();

                if (!props || !props.eventType) {
                    console.error("Modal için geçersiz veri:", props);
                    return;
                }

                // Elementleri Tazele
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

                // Önemli Checkbox
                if (isAuthorized) {
                    modalImportantContainer.style.display = 'block';
                    modalImportantCheckbox.checked = props.is_important || false;
                    modalImportantCheckbox.dataset.modelType = props.model_type;
                    modalImportantCheckbox.dataset.modelId = props.id;
                }

                // İkon ve Başlık
                const iconMap = {
                    'shipment': 'fa-truck',
                    'production': 'fa-industry',
                    'service_event': 'fa-calendar-star',
                    'vehicle_assignment': 'fa-car',
                    'travel': 'fa-plane-departure'
                };
                const icon = iconMap[props.eventType] || 'fa-info-circle';
                modalTitle.innerHTML = `<i class="fas ${icon}"></i> <span>${props.title || 'Detaylar'}</span>`;

                // Yetki Kontrolü
                let canModify = false;
                if (isAuthorized) {
                    canModify = true;
                } else if (props.user_id && props.user_id === currentUserId) {
                    canModify = true;
                }

                if (canModify && props.editUrl && props.editUrl !== '#') {
                    modalEditButton.href = props.editUrl;
                    modalEditButton.style.display = 'inline-block';
                }
                if (canModify && props.deleteUrl && modalDeleteForm) {
                    modalDeleteForm.action = props.deleteUrl;
                    modalDeleteForm.style.display = 'inline-block';
                }

                let html = '';

                // --- 1. SHIPMENT ---
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
                        modalOnayForm.action = props.onayUrl;
                        modalOnayForm.style.display = 'inline-block';
                    }

                    const isGemi = (props.details['Araç Tipi'] || '').toLowerCase().includes('gemi');

                    html +=
                        `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3"><i class="fas fa-truck me-2"></i>Araç Bilgileri</h6><div class="row">`;
                    html +=
                        `<div class="col-md-6"><p><strong>🚛 Araç Tipi:</strong> ${props.details['Araç Tipi'] || '-'}</p></div>`;
                    if (!isGemi) {
                        html +=
                            `<div class="col-md-6"><p><strong>🔢 Plaka:</strong> ${props.details['Plaka'] || '-'}</p></div>`;
                    } else {
                        html +=
                            `<div class="col-md-6"><p><strong>🚢 Gemi Adı:</strong> ${props.details['Gemi Adı'] || '-'}</p></div>`;
                    }
                    html += `</div></div>`;

                    html +=
                        `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3"><i class="fas fa-route me-2"></i>Rota Bilgileri</h6><div class="row">`;
                    html +=
                        `<div class="col-md-6"><p><strong>📍 Kalkış:</strong> ${props.details['Kalkış Noktası'] || props.details['Kalkış Limanı'] || '-'}</p></div>`;
                    html +=
                        `<div class="col-md-6"><p><strong>📍 Varış:</strong> ${props.details['Varış Noktası'] || props.details['Varış Limanı'] || '-'}</p></div>`;
                    html += `</div></div>`;

                    if (props.details['Dosya Yolu']) {
                        html +=
                            `<div class="text-center mt-3"><a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-paperclip me-2"></i> Dosyayı Görüntüle</a></div>`;
                    }
                }

                // --- 2. TRAVEL ---
                else if (props.eventType === 'travel') {
                    // Güvenlik: Butonları gizle
                    if (modalOnayForm) modalOnayForm.style.display = 'none';

                    html += '<div class="modal-info-card">';
                    html +=
                        '<h6 class="text-primary fw-bold mb-3"><i class="fas fa-plane-departure me-2"></i>Seyahat Bilgileri</h6>';
                    html += `<p><strong>✈️ Plan:</strong> ${props.details['Plan Adı'] || '-'}</p>`;
                    html +=
                        `<p><strong>📅 Tarih:</strong> ${props.details['Başlangıç'] || '-'} - ${props.details['Bitiş'] || '-'}</p>`;
                    html += `<p><strong>Durum:</strong> ${props.details['Durum'] || '-'}</p>`;
                    html += '</div>';

                    if (props.url) {
                        modalExportButton.href = props.url;
                        modalExportButton.target = "_blank";
                        modalExportButton.innerHTML = '<i class="fas fa-plane-departure me-2"></i> Detaya Git';
                        modalExportButton.style.display = 'inline-block';
                    }
                }

                // --- 3. DİĞER TİPLER ---
                else {
                    // Standart Basit Gösterim (Home için yeterli olabilir, isterseniz general-calendar'daki detaylı hali kopyalayabilirsiniz)
                    if (props.eventType === 'service_event') {
                        html +=
                            `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3">Etkinlik</h6><p>${props.title}</p></div>`;
                    } else if (props.eventType === 'production') {
                        html +=
                            `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3">Üretim Planı</h6><p>${props.details['Plan Başlığı'] || '-'}</p></div>`;
                    } else if (props.eventType === 'vehicle_assignment') {
                        html +=
                            `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3">Araç Görevi</h6><p>${props.details['Araç'] || '-'} - ${props.details['Görev'] || '-'}</p></div>`;
                    }
                }

                // Notlar
                const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
                    'Açıklama'];
                if (aciklama) {
                    html +=
                        `<div class="modal-notes-box"><div class="modal-notes-title"><i class="fas fa-sticky-note"></i> Notlar</div><p class="mb-0">${aciklama}</p></div>`;
                }

                modalBody.innerHTML = html;
                detailModal.show();
            }

            // === FULLCALENDAR INIT ===
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'tr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Bugün',
                    dayGridMonth: 'Ay',
                    timeGridWeek: 'Hafta',
                    timeGridDay: 'Gün',
                    listWeek: 'Liste'
                },
                // Orijinal CSS'e uygun ayarlar (text-wrapping için)
                slotEventOverlap: false,
                dayMaxEvents: 4,
                eventMaxStack: 3,
                slotDuration: '00:30:00',
                height: 'auto',
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                scrollTime: '08:00:00',
                nowIndicator: true,

                eventSources: [{
                        id: 'databaseEvents',
                        events: eventsData
                    },
                    {
                        googleCalendarId: 'tr.turkish#holiday@group.v.calendar.google.com',
                        color: '#dc3545',
                        textColor: 'white',
                        className: 'fc-event-holiday',
                        googleCalendarApiKey: 'AIzaSyAQmEWGR-krGzcCk1r8R69ER-NyZM2BeWM'
                    }
                ],
                timeZone: appTimezone,
                dayMaxEvents: false,
                moreLinkText: function(num) {
                    return '+ ' + num + ' tane daha';
                },
                displayEventTime: true,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventDisplay: 'list-item', // Bu önemli: text-wrapping için list-item stili

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
                }
            });
            calendar.render();
            setInterval(function() {
                console.log('Veriler arkaplanda güncelleniyor...');
                calendar.refetchEvents(); // FullCalendar'ın sihirli fonksiyonu
            }, 30000);

            function applyCalendarFilters() {
                const showLojistik = document.getElementById('filterLojistik').checked;
                const showUretim = document.getElementById('filterUretim').checked;
                const showHizmet = document.getElementById('filterHizmet').checked;
                const showImportant = document.getElementById('filterImportant').checked;

                let dbSource = calendar.getEventSourceById('databaseEvents');
                if (dbSource) {
                    dbSource.remove();
                }
                const filteredDbEvents = eventsData.filter(event => {
                    const props = event.extendedProps;
                    if (!props) return true;
                    if (showImportant && !props.is_important) {
                        return false;
                    }
                    const eventType = props.eventType;
                    if (eventType === 'shipment') {
                        return showLojistik;
                    }

                    if (eventType === 'production') {
                        return showUretim;
                    }
                    const isHizmet = (
                        eventType === 'service_event' ||
                        eventType === 'vehicle_assignment' ||
                        eventType === 'travel'
                    );
                    if (isHizmet) {
                        return showHizmet;
                    }
                    return true;
                });

                calendar.addEventSource({
                    id: 'databaseEvents',
                    events: filteredDbEvents
                });
            }

            const filters = document.querySelectorAll('.calendar-filters .form-check-input');
            filters.forEach(filter => filter.addEventListener('change', applyCalendarFilters));

            // === LISTENERLAR ===
            if (modalOnayForm) {
                modalOnayForm.addEventListener('submit', function(e) {
                    if (!confirm('Sevkiyatın tesise ulaştığını onaylıyorsunuz?')) e.preventDefault();
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

            // URL'den Modal Açma
            const urlParams = new URLSearchParams(window.location.search);
            const modalIdToOpen = urlParams.get('open_modal_id');
            const modalTypeToOpen = urlParams.get('open_modal_type');

            if (modalIdToOpen && modalTypeToOpen) {
                const allEvents = calendar.getEvents();
                const modalIdNum = parseInt(modalIdToOpen, 10);

                const eventToOpen = allEvents.find(event =>
                    event.extendedProps.id === modalIdNum &&
                    event.extendedProps.model_type === modalTypeToOpen
                );

                if (eventToOpen) {
                    console.log('URL\'den modal tetikleniyor:', eventToOpen.extendedProps);
                    openUniversalModal(eventToOpen.extendedProps);
                }
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            if (isAuthorized) {
                const toggleBtn = document.getElementById('toggleUsersButton');
                const userList = document.getElementById('userListContainer');
                if (toggleBtn && userList) {
                    toggleBtn.addEventListener('click', function() {
                        if (userList.style.display === 'none') {
                            userList.style.display = 'block';
                            toggleBtn.textContent = '👥 Kullanıcı Listesini Gizle';
                        } else {
                            userList.style.display = 'none';
                            toggleBtn.textContent = '👥 Mevcut Kullanıcıları Görüntüle';
                        }
                    });
                }
            }
            if (modalImportantCheckbox) {
                modalImportantCheckbox.addEventListener('change', function() {
                    const modelId = this.dataset.modelId;
                    const modelType = this.dataset.modelType;
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
                                model_id: modelId,
                                model_type: modelType,
                                is_important: isChecked
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) throw new Error(data.message || 'Güncelleme başarısız.');
                            console.log('Güncelleme başarılı:', data.message);
                            location
                                .reload(); // Home sayfasında veri JS object içinde olduğu için reload gerekli
                        })
                        .catch(error => {
                            console.error('Hata:', error);
                            alert('Bir hata oluştu, değişiklik geri alınıyor.');
                            this.checked = !isChecked;
                            this.disabled = false;
                        });
                });
            }

            const statsCard = document.getElementById('stats-card-body');
            const chartData = <?php echo json_encode($chartData ?? [], 15, 512) ?>;
            const departmentSlug = '<?php echo e($departmentSlug); ?>';

            const commonChartOptions = {
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'],
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 8
                    }
                },
                legend: {
                    show: false
                },
                title: {
                    align: 'left',
                    style: {
                        fontSize: '14px',
                        fontWeight: 700,
                        color: '#2d3748'
                    }
                },
                dataLabels: {
                    enabled: false
                }
            };

            // (Grafik kodları aynen devam ediyor...)
            // Lojistik Grafikleri
            if (departmentSlug === 'lojistik' && chartData.hourly && chartData.daily) {
                if (chartData.hourly.labels.length > 0 && document.querySelector("#hourly-chart-lojistik")) {
                    let hourlyOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Sevkiyat Sayısı',
                            data: chartData.hourly.data
                        }],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.hourly.title || 'Saatlik Yoğunluk'
                        },
                        xaxis: {
                            categories: chartData.hourly.labels,
                            tickAmount: 6
                        }
                    };
                    new ApexCharts(document.querySelector("#hourly-chart-lojistik"), hourlyOptions).render();
                }
                if (chartData.daily.labels.length > 0 && document.querySelector("#daily-chart-lojistik")) {
                    let dailyOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Sevkiyat Sayısı',
                            data: chartData.daily.data
                        }],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily.title || 'Haftalık Yoğunluk'
                        },
                        xaxis: {
                            categories: chartData.daily.labels
                        }
                    };
                    new ApexCharts(document.querySelector("#daily-chart-lojistik"), dailyOptions).render();
                }
            }
            // Üretim
            else if (departmentSlug === 'uretim' && chartData.weekly_plans) {
                if (chartData.weekly_plans.labels.length > 0 && document.querySelector("#weekly-plans-chart")) {
                    let weeklyOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Plan Sayısı',
                            data: chartData.weekly_plans.data
                        }],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.weekly_plans.title || 'Haftalık Plan Sayısı'
                        },
                        xaxis: {
                            categories: chartData.weekly_plans.labels
                        }
                    };
                    new ApexCharts(document.querySelector("#weekly-plans-chart"), weeklyOptions).render();
                }
            }
            // Hizmet
            else if (departmentSlug === 'hizmet' && chartData.daily_events && chartData.daily_assignments) {
                if (chartData.daily_events.labels.length > 0 && document.querySelector("#daily-events-chart")) {
                    let eventOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Etkinlik Sayısı',
                            data: chartData.daily_events.data
                        }],
                        chart: {
                            type: 'area',
                            height: 250,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            }
                        },
                        colors: [commonChartOptions.colors[1]],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily_events.title || 'Günlük Etkinlik Sayısı'
                        },
                        xaxis: {
                            categories: chartData.daily_events.labels,
                            tickAmount: 6,
                            labels: {
                                rotate: -45,
                                rotateAlways: true,
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0);
                                }
                            },
                            min: 0
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                opacityFrom: 0.6,
                                opacityTo: 0.1
                            }
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM'
                            },
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(0) + " etkinlik"
                                }
                            }
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3', 'transparent'],
                                opacity: 0.5
                            }
                        },
                    };
                    new ApexCharts(document.querySelector("#daily-events-chart"), eventOptions).render();
                }

                if (chartData.daily_assignments.labels.length > 0 && document.querySelector(
                        "#daily-assignments-chart")) {
                    let assignmentOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Atama Sayısı',
                            data: chartData.daily_assignments.data
                        }],
                        chart: {
                            type: 'area',
                            height: 250,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            }
                        },
                        colors: [commonChartOptions.colors[3]],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily_assignments.title || 'Günlük Araç Atama Sayısı'
                        },
                        xaxis: {
                            categories: chartData.daily_assignments.labels,
                            tickAmount: 6,
                            labels: {
                                rotate: -45,
                                rotateAlways: true,
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0);
                                }
                            },
                            min: 0
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                opacityFrom: 0.6,
                                opacityTo: 0.1
                            }
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM'
                            },
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(0) + " atama"
                                }
                            }
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3', 'transparent'],
                                opacity: 0.5
                            }
                        },
                    };
                    new ApexCharts(document.querySelector("#daily-assignments-chart"), assignmentOptions).render();
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/home.blade.php ENDPATH**/ ?>