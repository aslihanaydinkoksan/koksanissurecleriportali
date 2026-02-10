{{-- BU, YENİ EVRENSEL MODALIMIZDIR --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Başlık JavaScript ile dinamik olarak dolacak --}}
                <h5 class="modal-title" id="modalTitle">Detaylar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- İçerik JavaScript ile dinamik olarak dolacak --}}
            <div class="modal-body" id="modalDynamicBody" style="min-height: 200px;">
                {{-- Burası JS tarafından doldurulacak --}}
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                {{-- DİNAMİK BUTONLAR (JS ile gösterilip/gizlenecek) --}}

                {{-- Düzenle Butonu (Tümü için) --}}
                <a href="#" id="modalEditButton" class="btn btn-warning">✏️ Düzenle</a>

                <form method="POST" action="" id="modalDeleteForm" style="display: none; margin: 0 0 0 5px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Bu kaydı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')">
                        <i class="fas "></i> 🗑️ Sil
                    </button>
                </form>

                {{-- Excel'e Kaydet (Sadece Sevkiyat için) --}}
                <a href="#" id="modalExportButton" class="btn btn-success me-auto" style="display: none;">📊
                    Excel'e Kaydet</a>

                {{-- Onay Formları (Sadece Sevkiyat için) --}}
                <form id="modalOnayForm" method="POST" action="" class="p-0 m-0" style="display: none;"
                    onsubmit="return confirm('Sevkiyatın tesise ulaştığını onaylıyor musunuz?');">
                    @csrf
                    <button type="submit" class="btn btn-info text-white">✓ Sevkiyatı Onayla</button>
                </form>

                @if (in_array(Auth::user()->role, ['admin', 'yönetici', 'kullanıcı']))
                    <form id="modalOnayKaldirForm" method="POST" action="" class="p-0 m-0" style="display: none;"
                        onsubmit="return confirm('Bu sevkiyatın onayını geri almak istediğinizden emin misiniz?');">
                        @csrf
                        <button type="submit" class="btn btn-warning">↩️ Onayı Geri Al</button>
                    </form>
                @endif

                {{-- Onay Rozeti (Sadece Sevkiyat için) --}}
                <span id="modalOnayBadge" class="badge bg-success p-2" style="display:none; font-size: 0.9rem;">
                    ✓ Onaylandı (<span id="modalOnayBadgeTarih"></span> - <span id="modalOnayBadgeKullanici"></span>)
                </span>

                <div id="modalImportantCheckboxContainer" class="form-check form-switch me-auto" style="display: none;">
                    <input class="form-check-input" type="checkbox" role="switch" id="modalImportantCheckbox"
                        data-model-id="" data-model-type="">
                    <label class="form-check-label" for="modalImportantCheckbox">
                        <strong>👈 Önemli Olarak İşaretle</strong>
                    </label>
                </div>

                {{-- Kapat Butonu --}}
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
