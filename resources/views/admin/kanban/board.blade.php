@extends('layouts.app')

@section('title', 'Kanban Panosu: ' . $board->name)

@section('content')

    {{-- STİLLERİ BURAYA ALDIK Kİ KESİN ÇALIŞSIN --}}
    <style>
        /* --- 1. ANA PANO DÜZENİ (YATAY KAYDIRMA) --- */
        .kanban-board-area {
            background-color: #0d1e37 !important;
            /* Koyu Lacivert Masa Rengi */
            background-image: radial-gradient(#1a3c61 1px, transparent 1px);
            background-size: 20px 20px;
            height: calc(100vh - 120px);
            overflow-x: auto !important;
            overflow-y: hidden;
            padding: 20px;
            white-space: nowrap;
            /* Alt satıra inmeyi engeller */
            display: flex !important;
            flex-direction: row !important;
            align-items: flex-start;
            gap: 20px;
        }

        /* --- 2. SÜTUN TASARIMI --- */
        .kanban-column {
            display: inline-flex;
            /* Yan yana dizilim */
            flex-direction: column;
            width: 340px !important;
            min-width: 340px !important;
            /* Küçülmeyi engeller */
            max-width: 340px !important;
            max-height: 95%;
            background: #ebecf0;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            vertical-align: top;
            margin-right: 0;
            /* Gap kullanıyoruz */
        }

        .kanban-column-header {
            padding: 15px;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background: #fff;
            border-bottom: 2px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kanban-column-body {
            padding: 10px;
            overflow-y: auto;
            flex: 1;
            /* Scrollbar ayarları */
            scrollbar-width: thin;
            scrollbar-color: #c1c7d0 #f4f5f7;
        }

        /* --- 3. KART TASARIMI (GERÇEKÇİ FİZİK) --- */
        .kanban-card {
            background: #ffffff;
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            cursor: grab;
            position: relative;
            border-left: 4px solid transparent;
            transition: transform 0.1s, box-shadow 0.1s;
            white-space: normal;
            /* Kart içi metin alt satıra geçsin */

            /* İMLEÇ SORUNU ÇÖZÜMÜ: Metin seçimini engelle */
            user-select: none;
            -webkit-user-select: none;
        }

        .kanban-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .kanban-card:active {
            cursor: grabbing;
        }

        /* Kart İçeriği */
        .card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #888;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #172b4d;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .card-details {
            font-size: 13px;
            color: #5e6c84;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* --- 4. SÜRÜKLEME ANİMASYONLARI --- */

        /* Kart Sürüklenirken (Havada) */
        .sortable-drag {
            background: #fff !important;
            opacity: 1 !important;
            transform: rotate(4deg) scale(1.05) !important;
            /* Kartı eğ ve büyüt */
            box-shadow: 10px 20px 30px rgba(0, 0, 0, 0.3) !important;
            cursor: grabbing !important;
            z-index: 10000;
        }

        /* Kartın Gideceği Yer (Hayalet) */
        .sortable-ghost {
            background: rgba(9, 30, 66, 0.04) !important;
            border: 2px dashed rgba(9, 30, 66, 0.15);
            box-shadow: none !important;
            opacity: 0.5;
        }

        /* Renk Sınıfları */
        .border-left-primary {
            border-left-color: #0052cc !important;
        }

        .border-left-success {
            border-left-color: #00875a !important;
        }

        .border-left-danger {
            border-left-color: #de350b !important;
        }

        .border-left-warning {
            border-left-color: #ff991f !important;
        }

        .border-left-secondary {
            border-left-color: #505f79 !important;
        }

        /* Header Renkleri */
        .text-primary-custom {
            color: #0052cc;
        }

        .text-success-custom {
            color: #00875a;
        }

        .text-danger-custom {
            color: #de350b;
        }

        .text-warning-custom {
            color: #ff991f;
        }
    </style>

    {{-- Geri Dönüş Linki --}}
    @php
        $backRoute = match ($scope) {
            'maintenance' => route('maintenance.index'),
            'logistics' => route('shipments.index'),
            'production' => route('production.plans.index'),
            'admin' => route('service.events.index'),
            default => route('welcome'),
        };
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 px-3">
        <div>
            <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
                {{ $board->name }}
            </h1>
        </div>
        <div>
            <a href="{{ $backRoute }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fa fa-arrow-left"></i> Listeye Dön
            </a>
        </div>
    </div>

    <div class="kanban-board-area">
        @foreach ($board->columns as $column)
            @php
                // Renk ve Border Ayarları
                $colorClass = match ($column->slug) {
                    'teslim-edildi', 'done', 'completed', 'bitti' => 'border-left-success text-success-custom',
                    'yolda', 'processing', 'islemde' => 'border-left-primary text-primary-custom',
                    'siparis-iptal-edildi', 'cancelled', 'iptal' => 'border-left-danger text-danger-custom',
                    'waiting', 'pending', 'siparis-alindi' => 'border-left-secondary',
                    default => 'border-left-warning text-warning-custom',
                };

                // Border sınıfını ayıralım (sadece border-left-...)
                $borderOnly = explode(' ', $colorClass)[0];
            @endphp

            <div class="kanban-column">
                {{-- Sütun Başlığı --}}
                <div class="kanban-column-header">
                    <span class="{{ str_replace('border-left-', 'text-', $borderOnly) }}-custom">
                        {{ $column->title }}
                    </span>
                    <span class="badge bg-secondary text-white rounded-pill">{{ $column->cards->count() }}</span>
                </div>

                {{-- Kartların Olduğu Alan --}}
                <div class="kanban-column-body" id="column-{{ $column->id }}" data-column-id="{{ $column->id }}">
                    @foreach ($column->cards as $card)
                        @if ($card->model)
                            <div class="kanban-card {{ $borderOnly }}" data-card-id="{{ $card->id }}">

                                {{-- Kart Üst Bilgi (ID ve Tarih) --}}
                                <div class="card-meta">
                                    <span>#{{ $card->model->id }}</span>
                                    <span>{{ $card->created_at->format('d.m') }}</span>
                                </div>

                                {{-- Kart Başlığı --}}
                                <div class="card-title">
                                    {{ $card->model->title ?? ($card->model->plaka ?? 'Başlıksız İş') }}
                                </div>

                                {{-- Detay (Şoför, Açıklama vb.) --}}
                                <div class="card-details">
                                    @if (isset($card->model->sofor_adi))
                                        <i class="fas fa-user-circle"></i> {{ $card->model->sofor_adi }}
                                    @elseif(isset($card->model->description))
                                        <i class="fas fa-align-left"></i> {{ Str::limit($card->model->description, 30) }}
                                    @endif
                                </div>

                                {{-- Öncelik Badge (Varsa) --}}
                                @if (isset($card->model->priority) && $card->model->priority == 'high')
                                    <div class="mt-2 text-end">
                                        <span class="badge bg-danger text-white" style="font-size: 10px;">ACİL</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="kanbanDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="kanbanDetailContent">
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const columns = document.querySelectorAll('.kanban-column-body');

            columns.forEach((column) => {
                new Sortable(column, {
                    group: 'kanban-board',
                    animation: 250, // Geçiş süresi
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    delay: 0,
                    forceFallback: true, // Tarayıcı API'sini ezerek kendi kopyamızı kullanır (Fizik efekti için şart)
                    fallbackClass: 'sortable-drag', // Sürüklenen kopya sınıfı
                    fallbackOnBody: true, // Kopyayı body'ye ekle ki overflow hidden'dan etkilenmesin

                    onEnd: function(evt) {
                        const itemEl = evt.item;
                        const newColumnEl = evt.to;
                        const cardId = itemEl.getAttribute('data-card-id');
                        const targetColumnId = newColumnEl.getAttribute('data-column-id');
                        const newIndex = evt.newIndex;

                        updateCardPosition(cardId, targetColumnId, newIndex);
                    }
                });
            });
        });

        // Backend Update
        function updateCardPosition(cardId, targetColumnId, newIndex) {
            axios.post('{{ route('kanban.move') }}', {
                    card_id: cardId,
                    target_column_id: targetColumnId,
                    new_index: newIndex,
                    _token: '{{ csrf_token() }}'
                })
                .then(res => console.log('Pozisyon kaydedildi'))
                .catch(err => {
                    console.error('Hata:', err);
                    alert('Sıralama hatası!');
                });
        }

        // Modal Açma
        document.addEventListener('click', function(e) {
            const cardEl = e.target.closest('.kanban-card');
            if (cardEl) {
                const cardId = cardEl.getAttribute('data-card-id');
                openCardModal(cardId);
            }
        });

        function openCardModal(cardId) {
            const modalEl = document.getElementById('kanbanDetailModal');
            const modalContent = document.getElementById('kanbanDetailContent');
            const modal = new bootstrap.Modal(modalEl);

            modalContent.innerHTML =
                `<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div></div>`;
            modal.show();

            axios.get(`{{ url('/kanban/card') }}/${cardId}`)
                .then(res => {
                    modalContent.innerHTML = res.data;
                })
                .catch(err => {
                    modalContent.innerHTML = '<div class="p-3 text-danger">Hata oluştu</div>';
                });
        }
    </script>
@endsection
