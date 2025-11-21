@extends('layouts.app')
@section('title', 'Genel KÖKSAN Takvimi')

@push('styles')
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
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card create-shipment-card">
                    <div class="card-header">📅 Genel KÖKSAN Takvimi</div>
                    <div class="card-body">
                        <div id='calendar' data-current-user-id="{{ Auth::id() }}"
                            data-is-authorized="{{ in_array(Auth::user()->role, ['admin', 'yönetici']) ? 'true' : 'false' }}">
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                    <div id="modalImportantCheckboxContainer" style="display: none;">
                        <input type="checkbox" id="modalImportantCheckbox" class="form-check-input">
                        <label for="modalImportantCheckbox" class="form-check-label"><i
                                class="fas fa-exclamation-circle me-1"></i> Bu Etkinliği Önemli Olarak İşaretle</label>
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

                    <form method="POST" id="modalOnayForm" style="display: none;" class="d-inline">@csrf<button
                            type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i> Tesise Ulaştı</button>
                    </form>
                    <form method="POST" id="modalOnayKaldirForm" style="display: none;" class="d-inline">@csrf
                        @method('DELETE')<button type="submit" class="btn btn-warning"><i class="fas fa-undo me-2"></i>
                            Onayı Kaldır</button></form>
                    <form method="POST" id="modalDeleteForm" style="display: none;" class="d-inline"
                        onsubmit="return confirm('Silmek istediğinize emin misiniz?');">@csrf @method('DELETE')<button
                            type="submit" class="btn btn-danger"><i class="fas fa-trash me-2"></i> Sil</button></form>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fas fa-times me-2"></i> Kapat</button>
                </div>
            </div>
        </div>
    </div>
    @include('partials.calendar-modal')
@endsection

@section('page_scripts')
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
            const currentUserDepartment = "{{ Auth::user()->department?->slug }}";
            const currentUserRole = "{{ Auth::user()->role }}";
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

                // Başlık & İkon
                const iconMap = {
                    'shipment': 'fa-truck',
                    'production': 'fa-industry',
                    'service_event': 'fa-calendar-star',
                    'vehicle_assignment': 'fa-car',
                    'travel': 'fa-plane-departure'
                };
                const icon = iconMap[props.eventType] || 'fa-info-circle';
                modalTitle.innerHTML = `<i class="fas ${icon}"></i> <span>${props.title || 'Detaylar'}</span>`;

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
                dayMaxEvents: 4, // UI Düzeltmesi için
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
                        url: '{{ route('web.calendar.events') }}',
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
                    if (info.event.extendedProps.is_important) info.el.classList.add(
                        'event-important-pulse');
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
                    fetch('{{ route('calendar.toggleImportant') }}', {
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
@endsection
