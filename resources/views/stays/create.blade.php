@extends('layouts.master')

@section('title', 'Giriş Yap (Check-in)')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-top border-4 border-success">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-success">
                            <i class="fa fa-sign-in-alt me-2"></i> Giriş İşlemi (Check-in)
                        </h5>
                        <small class="text-muted">
                            Yerleştirilecek Mekan: <strong>{{ $location->name }}</strong>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stays.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="location_id" value="{{ $location->id }}">

                            <div class="mb-4">
                                <label class="form-label fw-bold">Personel / Misafir Seç</label>
                                <div class="input-group">
                                    <select name="resident_id" id="residentSelect" class="form-select form-select-lg"
                                        required>
                                        <option value="">-- Listeden Seçiniz --</option>
                                        @foreach ($residents as $resident)
                                            <option value="{{ $resident->id }}">
                                                {{ $resident->first_name }} {{ $resident->last_name }}
                                                ({{ $resident->department ?? 'Bölüm Yok' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#newResidentModal">
                                        <i class="fa fa-plus"></i> Yeni Ekle
                                    </button>
                                </div>
                                <div class="form-text">Aradığınız kişi listede yoksa yandaki butona basarak hemen
                                    ekleyebilirsiniz.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Giriş Tarihi</label>
                                <input type="datetime-local" name="check_in_date" class="form-control"
                                    value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>

                            <div class="mb-4 bg-light p-3 rounded border">
                                <label class="form-label fw-bold text-primary"><i class="fa fa-tasks me-1"></i> Teslim
                                    Edilenler & Kontroller</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[keys]"
                                                value="1" id="checkKeys">
                                            <label class="form-check-label" for="checkKeys">Anahtar Teslim Edildi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[wifi]"
                                                value="1" id="checkWifi">
                                            <label class="form-check-label" for="checkWifi">Wi-Fi Şifresi Verildi</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[clean]"
                                                value="1" id="checkClean">
                                            <label class="form-check-label" for="checkClean">Oda Temizliği Kontrol
                                                Edildi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[ac]" value="1"
                                                id="checkAC">
                                            <label class="form-check-label" for="checkAC">Klima/Kombi Çalışıyor</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notlar</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Örn: Yedek anahtar da verildi..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('locations.show', $location->id) }}" class="btn btn-secondary">İptal</a>
                                <button type="submit" class="btn btn-success px-5">
                                    <i class="fa fa-check-circle me-1"></i> Girişi Onayla
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newResidentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa fa-user-plus me-2"></i>Hızlı Personel Ekle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <div id="modalAlert" class="alert alert-danger d-none"></div>
                    <form id="quickAddForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Adı *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Soyadı *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Departman</label>
                            <input type="text" name="department" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TC Kimlik No (Opsiyonel)</label>
                            <input type="text" name="tc_no" class="form-control" maxlength="11">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-success" onclick="saveNewResident()">Kaydet ve Seç</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveNewResident() {
            const form = document.getElementById('quickAddForm');
            const formData = new FormData(form);
            const alertBox = document.getElementById('modalAlert');

            // Butonu pasif yap (Çift tıklamayı önle)
            const saveBtn = document.querySelector('#newResidentModal .btn-success');
            const originalText = saveBtn.innerText;
            saveBtn.disabled = true;
            saveBtn.innerText = 'Kaydediliyor...';

            fetch("{{ route('residents.storeAjax') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 1. Yeni kişiyi Select listesine ekle
                        const select = document.getElementById('residentSelect');
                        const option = new Option(
                            data.resident.first_name + ' ' + data.resident.last_name + ' (' + (data.resident
                                .department || '-') + ')',
                            data.resident.id
                        );
                        select.add(option, undefined);

                        // 2. Yeni kişiyi otomatik seç
                        select.value = data.resident.id;

                        // 3. Modalı kapat ve formu temizle
                        var myModal = bootstrap.Modal.getInstance(document.getElementById('newResidentModal'));
                        myModal.hide();
                        form.reset();
                        alertBox.classList.add('d-none');
                    } else {
                        // Hata mesajı göster
                        alertBox.innerText = data.message;
                        alertBox.classList.remove('d-none');
                    }
                })
                .catch(error => {
                    alertBox.innerText = "Bir hata oluştu. Lütfen tekrar deneyin.";
                    alertBox.classList.remove('d-none');
                })
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.innerText = originalText;
                });
        }
    </script>
@endsection
