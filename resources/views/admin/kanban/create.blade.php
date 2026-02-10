@extends('layouts.app')

@section('title', 'Yeni Kanban Panosu Oluştur')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                @if (auth()->user()->isAdmin())
                    <i class="fa fa-shield-alt text-primary mr-2"></i> Yönetici Pano Oluşturma
                @else
                    Kişisel Kanban Panosu Oluştur
                @endif
            </h1>
            <a href="{{ route('kanban-boards.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fa fa-arrow-left"></i> Geri Dön
            </a>
        </div>

        <form action="{{ route('kanban-boards.store') }}" method="POST">
            @csrf

            {{-- 1. GENEL AYARLAR --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-cog mr-2"></i> Pano Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Pano Sahibi Seçimi (Admin için Otomatik Tetikleyicili) --}}
                        @if (auth()->user()->isAdmin())
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-primary">Pano Sahibi (Kullanıcı)</label>
                                <select name="user_id" id="admin_user_selector"
                                    class="form-control form-select shadow-sm border-primary">
                                    <option value="{{ auth()->id() }}"
                                        data-unit-id="{{ auth()->user()->businessUnits->first()?->id }}">Kendim (Admin)
                                    </option>
                                    @foreach ($allUsers as $u)
                                        @if ($u->id !== auth()->id())
                                            {{-- data-unit-id ile kullanıcının birimini saklıyoruz --}}
                                            <option value="{{ $u->id }}"
                                                data-unit-id="{{ $u->businessUnits->first()?->id }}">
                                                {{ $u->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <small class="text-primary font-weight-bold">Kullanıcı seçildiğinde fabrika otomatik
                                    güncellenir.</small>
                            </div>
                        @endif

                        {{-- Pano Adı --}}
                        <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }} mb-3">
                            <label class="form-label font-weight-bold">Pano Adı</label>
                            <input type="text" name="name" id="board_name" class="form-control shadow-sm"
                                placeholder="Örn: Sevkiyat Takibi" value="{{ old('name') }}" required>
                        </div>

                        {{-- Fabrika / Birim --}}
                        <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }} mb-3">
                            <label class="form-label font-weight-bold text-dark">Fabrika / Birim</label>
                            @if (auth()->user()->isAdmin())
                                <select name="business_unit_id" id="admin_unit_selector"
                                    class="form-control form-select shadow-sm">
                                    @foreach (\App\Models\BusinessUnit::all() as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $userUnit->id == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="form-control bg-light text-muted border-dashed" style="border-style: dashed;">
                                    <i class="fa fa-lock mr-2 text-warning"></i> {{ $userUnit->name }}
                                </div>
                                <input type="hidden" name="business_unit_id" value="{{ $userUnit->id }}">
                            @endif
                        </div>

                        {{-- Modül / Scope --}}
                        <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }} mb-3">
                            <label class="form-label font-weight-bold text-dark">İş Akışı (Kapsam)</label>
                            @if (auth()->user()->isAdmin())
                                <select name="module_scope" class="form-control form-select shadow-sm">
                                    <option value="">-- Kapsam Seçiniz --</option>
                                    @foreach ($modules as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ (string) $scope == (string) $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="form-control bg-light text-muted border-dashed" style="border-style: dashed;">
                                    {{ $modules[(string) $scope] ?? 'Genel' }}
                                </div>
                                <input type="hidden" name="module_scope" value="{{ $scope }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. PANO SÜTUNLARI --}}
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fa fa-columns mr-2"></i> Pano Sütunları</h6>
                    <div class="btn-group">
                        <button type="button" onclick="applyTemplate('standard')"
                            class="btn btn-sm btn-outline-primary shadow-sm">Standart Şablon</button>
                        <button type="button" onclick="applyTemplate('logistic')"
                            class="btn btn-sm btn-outline-info shadow-sm mx-1">Lojistik Şablonu</button>
                        <button type="button" onclick="addColumn()" class="btn btn-sm btn-success shadow-sm"><i
                                class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="columns-container" class="bg-light p-3 rounded" style="border: 1px solid #e3e6f0;"></div>
                </div>
                <div class="card-footer bg-white text-right">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fa fa-save mr-2"></i> Kaydet
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // OTOMASYON: Kullanıcı seçilince fabrika değişsin
        document.addEventListener('DOMContentLoaded', function() {
            const userSelector = document.getElementById('admin_user_selector');
            const unitSelector = document.getElementById('admin_unit_selector');

            if (userSelector && unitSelector) {
                userSelector.addEventListener('change', function() {
                    // Seçilen option'daki data-unit-id bilgisini al
                    const selectedOption = this.options[this.selectedIndex];
                    const targetUnitId = selectedOption.getAttribute('data-unit-id');

                    if (targetUnitId) {
                        unitSelector.value = targetUnitId;
                        // Görsel efekt: Seçimin değiştiğini belli etmek için kısa bir yanıp sönme
                        unitSelector.classList.add('is-valid');
                        setTimeout(() => unitSelector.classList.remove('is-valid'), 1000);
                    }
                });
            }
        });

        // KANBAN SÜTUN MANTIĞI
        let columnIndex = 0;
        const templates = {
            standard: [{
                    title: 'Bekleyenler',
                    color: 'bg-light',
                    is_default: true
                },
                {
                    title: 'İşlemde',
                    color: 'bg-primary text-white',
                    is_default: false
                },
                {
                    title: 'Tamamlandı',
                    color: 'bg-success text-white',
                    is_default: false
                }
            ],
            logistic: [{
                    title: 'Sipariş Alındı',
                    color: 'bg-light text-dark',
                    is_default: true
                },
                {
                    title: 'Yolda',
                    color: 'bg-info text-white',
                    is_default: false
                },
                {
                    title: 'Teslim Edildi',
                    color: 'bg-success text-white',
                    is_default: false
                }
            ]
        };

        function applyTemplate(type, skipConfirm = false) {
            const container = document.getElementById('columns-container');
            if (!skipConfirm && container.children.length > 0) {
                if (!confirm('Mevcut sütunlar silinecek. Emin misiniz?')) return;
            }
            container.innerHTML = '';
            columnIndex = 0;
            templates[type].forEach(col => addColumn(col.title, col.color, col.is_default));
        }

        function addColumn(title = '', color = 'bg-light', isDefault = false) {
            const container = document.getElementById('columns-container');
            const html = `
                <div class="row mb-3 align-items-center p-3 bg-white rounded shadow-sm border mx-0" id="row-${columnIndex}">
                    <div class="col-md-5">
                        <label class="small font-weight-bold">Sütun Başlığı</label>
                        <input type="text" name="columns[${columnIndex}][title]" value="${title}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold">Renk</label>
                        <select name="columns[${columnIndex}][color_class]" class="form-control form-select">
                            <option value="bg-light" ${color === 'bg-light' ? 'selected' : ''}>Gri</option>
                            <option value="bg-primary text-white" ${color.includes('bg-primary') ? 'selected' : ''}>Mavi</option>
                            <option value="bg-success text-white" ${color.includes('bg-success') ? 'selected' : ''}>Yeşil</option>
                            <option value="bg-danger text-white" ${color.includes('bg-danger') ? 'selected' : ''}>Kırmızı</option>
                        </select>
                    </div>
                    <div class="col-md-2 mt-4">
                        <input type="radio" name="default_selection" value="${columnIndex}" ${isDefault ? 'checked' : ''} onclick="updateDefaultValue(${columnIndex})">
                        <input type="hidden" name="columns[${columnIndex}][is_default]" id="hiddenDefault${columnIndex}" value="${isDefault ? '1' : '0'}">
                        <label class="small">Varsayılan</label>
                    </div>
                    <div class="col-md-2 mt-4 text-right">
                        <button type="button" onclick="this.closest('.row').remove()" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            columnIndex++;
        }

        function updateDefaultValue(index) {
            document.querySelectorAll('input[id^="hiddenDefault"]').forEach(input => input.value = '0');
            document.getElementById('hiddenDefault' + index).value = '1';
        }

        window.onload = () => applyTemplate('standard', true);
    </script>
@endsection
