@extends('layouts.app')

@section('title', 'Kanban Panosunu Düzenle')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fa fa-edit text-warning mr-2"></i> Panoyu Düzenle: {{ $kanbanBoard->name }}
            </h1>

            <div class="d-flex border-left pl-3">
                {{-- SİLME FORMU --}}
                <form action="{{ route('kanban-boards.destroy', $kanbanBoard->id) }}" method="POST"
                    onsubmit="return confirm('DİKKAT! Bu panoyu silerseniz içindeki tüm kartlar ve sütun yapısı tamamen silinecektir. Bu işlem geri alınamaz! Emin misiniz?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm mr-2">
                        <i class="fa fa-trash mr-1"></i> Panoyu Tamamen Sil
                    </button>
                </form>

                <a href="{{ route('kanban-boards.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Geri Dön
                </a>
            </div>
        </div>

        {{-- Hata Mesajları --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-left-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kanban-boards.update', $kanbanBoard->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 1. PANO GENEL AYARLARI --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-cog mr-2"></i> Temel Bilgiler</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Sahibi Değiştir (Sadece Admin) --}}
                        @if (auth()->user()->isAdmin())
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-primary">Pano Sahibi</label>
                                <select name="user_id" class="form-control form-select border-primary shadow-sm">
                                    @foreach (\App\Models\User::orderBy('name')->get() as $u)
                                        <option value="{{ $u->id }}"
                                            {{ $kanbanBoard->user_id == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-primary font-weight-bold">Yönetici: Panoyu başka birine
                                    devredebilirsiniz.</small>
                            </div>
                        @endif

                        {{-- Pano Adı --}}
                        <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }} mb-3">
                            <label class="form-label font-weight-bold">Pano Adı</label>
                            <input type="text" name="name" value="{{ old('name', $kanbanBoard->name) }}"
                                class="form-control shadow-sm" required>
                            <small class="text-muted">Görünen pano başlığı.</small>
                        </div>

                        {{-- Fabrika / Birim --}}
                        <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }} mb-3">
                            <label class="form-label font-weight-bold text-dark">Fabrika / Birim</label>
                            @if (auth()->user()->isAdmin())
                                <select name="business_unit_id" class="form-control form-select shadow-sm">
                                    @foreach (\App\Models\BusinessUnit::all() as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $kanbanBoard->business_unit_id == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="form-control bg-light border-dashed" style="border-style: dashed;">
                                    {{ $kanbanBoard->businessUnit->name ?? 'Bilinmiyor' }}
                                </div>
                                <input type="hidden" name="business_unit_id" value="{{ $kanbanBoard->business_unit_id }}">
                            @endif
                        </div>

                        {{-- Modül (Kilitli - Değiştirilemez) --}}
                        <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }} mb-3">
                            <label class="form-label font-weight-bold text-dark">İş Akışı (Kapsam)</label>
                            <div class="form-control bg-light border-dashed text-muted" style="border-style: dashed;">
                                <i class="fa fa-lock mr-2 text-warning"></i> {{ strtoupper($kanbanBoard->module_scope) }}
                            </div>
                            <small class="text-danger">Modül tipi oluşturulduktan sonra değiştirilemez.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. SÜTUN YÖNETİMİ --}}
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fa fa-columns mr-2"></i> Pano Sütunları</h6>
                    <button type="button" onclick="addColumn()" class="btn btn-sm btn-success shadow-sm">
                        <i class="fa fa-plus"></i> Yeni Sütun Ekle
                    </button>
                </div>
                <div class="card-body">
                    <div id="columns-container" class="bg-light p-3 rounded" style="border: 1px solid #e3e6f0;">
                        @foreach ($kanbanBoard->columns->sortBy('order_index') as $index => $column)
                            <div class="row mb-3 align-items-center p-3 bg-white rounded shadow-sm border mx-0 column-row"
                                id="row-{{ $index }}">
                                {{-- Mevcut ID (Güncelleme için şart) --}}
                                <input type="hidden" name="columns[{{ $index }}][id]" value="{{ $column->id }}">

                                <div class="col-md-5">
                                    <label class="small font-weight-bold text-gray-700">Sütun Başlığı</label>
                                    <input type="text" name="columns[{{ $index }}][title]"
                                        value="{{ $column->title }}" class="form-control shadow-sm" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="small font-weight-bold text-gray-700">Renk Teması</label>
                                    <select name="columns[{{ $index }}][color_class]"
                                        class="form-control form-select">
                                        <option value="bg-light"
                                            {{ $column->color_class == 'bg-light' ? 'selected' : '' }}>Gri (Standart)
                                        </option>
                                        <option value="bg-primary text-white"
                                            {{ str_contains($column->color_class, 'bg-primary') ? 'selected' : '' }}>Mavi
                                        </option>
                                        <option value="bg-warning text-dark"
                                            {{ str_contains($column->color_class, 'bg-warning') ? 'selected' : '' }}>Sarı
                                        </option>
                                        <option value="bg-success text-white"
                                            {{ str_contains($column->color_class, 'bg-success') ? 'selected' : '' }}>Yeşil
                                        </option>
                                        <option value="bg-danger text-white"
                                            {{ str_contains($column->color_class, 'bg-danger') ? 'selected' : '' }}>Kırmızı
                                        </option>
                                        <option value="bg-info text-white"
                                            {{ str_contains($column->color_class, 'bg-info') ? 'selected' : '' }}>Turkuaz
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4 text-center">
                                    <div class="form-check form-switch shadow-sm border p-2 rounded"
                                        style="min-height: 38px;">
                                        <input type="radio" name="default_selection" value="{{ $index }}"
                                            {{ $column->is_default ? 'checked' : '' }} class="form-check-input"
                                            id="defaultCheck{{ $index }}"
                                            onclick="updateDefaultValue({{ $index }})"
                                            style="float: none; margin-left: 0;">
                                        <input type="hidden" name="columns[{{ $index }}][is_default]"
                                            id="hiddenDefault{{ $index }}"
                                            value="{{ $column->is_default ? '1' : '0' }}">
                                        <label class="form-check-label small d-block"
                                            for="defaultCheck{{ $index }}">Varsayılan mı?</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mt-4 text-right">
                                    <button type="button" onclick="removeColumn('row-{{ $index }}')"
                                        class="btn btn-outline-danger btn-sm px-3">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fa fa-save mr-2"></i> Değişiklikleri Kaydet
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Mevcut sütun sayısından başlatıyoruz
        let columnIndex = {{ $kanbanBoard->columns->count() }};

        function addColumn() {
            const container = document.getElementById('columns-container');
            const html = `
                <div class="row mb-3 align-items-center p-3 bg-white rounded shadow-sm border mx-0 column-row" id="row-${columnIndex}">
                    <div class="col-md-5">
                        <label class="small font-weight-bold text-gray-700">Sütun Başlığı</label>
                        <input type="text" name="columns[${columnIndex}][title]" class="form-control shadow-sm" placeholder="Yeni Sütun Adı" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-gray-700">Renk Teması</label>
                        <select name="columns[${columnIndex}][color_class]" class="form-control form-select">
                            <option value="bg-light">Gri (Standart)</option>
                            <option value="bg-primary text-white">Mavi</option>
                            <option value="bg-warning text-dark">Sarı</option>
                            <option value="bg-success text-white">Yeşil</option>
                            <option value="bg-danger text-white">Kırmızı</option>
                            <option value="bg-info text-white">Turkuaz</option>
                        </select>
                    </div>
                    <div class="col-md-2 mt-4 text-center">
                        <div class="form-check form-switch shadow-sm border p-2 rounded" style="min-height: 38px;">
                            <input type="radio" name="default_selection" value="${columnIndex}" class="form-check-input" id="defaultCheck${columnIndex}" onclick="updateDefaultValue(${columnIndex})" style="float: none; margin-left: 0;">
                            <input type="hidden" name="columns[${columnIndex}][is_default]" id="hiddenDefault${columnIndex}" value="0">
                            <label class="form-check-label small d-block" for="defaultCheck${columnIndex}">Varsayılan mı?</label>
                        </div>
                    </div>
                    <div class="col-md-2 mt-4 text-right">
                        <button type="button" onclick="removeColumn('row-${columnIndex}')" class="btn btn-outline-danger btn-sm px-3">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            columnIndex++;
        }

        function removeColumn(id) {
            const el = document.getElementById(id);
            if (el) {
                // Eğer içinde ID olan bir sütunsa, kullanıcıyı uyarabiliriz.
                const hasId = el.querySelector('input[name*="[id]"]');
                if (hasId) {
                    if (!confirm(
                            'Bu mevcut bir sütun. Sildiğinizde bu sütundaki kartlar "Varsayılan" sütuna taşınacaktır. Emin misiniz?'
                        )) {
                        return;
                    }
                }
                el.remove();
            }
        }

        function updateDefaultValue(index) {
            // Tüm gizli inputları sıfırla
            document.querySelectorAll('input[id^="hiddenDefault"]').forEach(input => input.value = '0');
            // Seçilenin gizli inputunu 1 yap
            const target = document.getElementById('hiddenDefault' + index);
            if (target) target.value = '1';
        }
    </script>
@endsection
