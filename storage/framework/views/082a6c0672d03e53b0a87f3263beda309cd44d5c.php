
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title" id="modalTitle">Detaylar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            
            <div class="modal-body" id="modalDynamicBody" style="min-height: 200px;">
                
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                

                
                <a href="#" id="modalEditButton" class="btn btn-warning">✏️ Düzenle</a>

                <form method="POST" action="" id="modalDeleteForm" style="display: none; margin: 0 0 0 5px;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Bu kaydı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')">
                        <i class="fas "></i> 🗑️ Sil
                    </button>
                </form>

                
                <a href="#" id="modalExportButton" class="btn btn-success me-auto" style="display: none;">📊
                    Excel'e Kaydet</a>

                
                <form id="modalOnayForm" method="POST" action="" class="p-0 m-0" style="display: none;"
                    onsubmit="return confirm('Sevkiyatın tesise ulaştığını onaylıyor musunuz?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-info text-white">✓ Sevkiyatı Onayla</button>
                </form>

                <?php if(in_array(Auth::user()->role, ['admin', 'yönetici', 'kullanıcı'])): ?>
                    <form id="modalOnayKaldirForm" method="POST" action="" class="p-0 m-0" style="display: none;"
                        onsubmit="return confirm('Bu sevkiyatın onayını geri almak istediğinizden emin misiniz?');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-warning">↩️ Onayı Geri Al</button>
                    </form>
                <?php endif; ?>

                
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

                
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/koksan-takvim/resources/views/partials/calendar-modal.blade.php ENDPATH**/ ?>