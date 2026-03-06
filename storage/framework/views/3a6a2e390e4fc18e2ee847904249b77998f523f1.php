<div class="modal fade" id="kvkkModal" tabindex="-1" aria-labelledby="kvkkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem; overflow: hidden;">

            
            <div class="modal-header border-0 position-relative"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem 2rem;">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-1" id="kvkkModalLabel" style="font-size: 1.35rem;">
                        <i class="fa-solid fa-shield-halved me-2"></i> KVKK Aydınlatma Metni
                    </h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.875rem;">Kişisel Verilerin Korunması Kanunu
                        uyarınca aydınlatma metnimizdir.</p>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal"
                    aria-label="Kapat" style="filter: brightness(0) invert(1);"></button>
            </div>

            
            <div class="modal-body p-0" style="background-color: #f8f9fa;">
                <iframe src="<?php echo e(asset('docs/kvkk-web-sitesi-aydinlatma-metni-.pdf')); ?>#toolbar=0&navpanes=0&scrollbar=0" width="100%"
                    style="height: 55vh; min-height: 400px; border: none; display: block;"
                    title="KVKK Aydınlatma Metni PDF">
                    <p class="text-center p-4">
                        Tarayıcınız PDF görüntülemeyi desteklemiyor.<br>
                        <a href="<?php echo e(asset('docs/kvkk-web-sitesi-aydinlatma-metni-.pdf')); ?>" target="_blank" class="text-primary">
                            Metni indirmek veya okumak için tıklayınız.
                        </a>
                    </p>
                </iframe>
            </div>

            
            <div class="modal-footer border-0 p-4"
                style="background-color: #f8f9fa; display: flex; justify-content: space-between; align-items: center;">

                
                <a href="<?php echo e(asset('docs/kvkk-web-sitesi-aydinlatma-metni-.pdf')); ?>" target="_blank" class="text-decoration-none"
                    style="color: #667eea; font-size: 0.9rem; font-weight: 500;">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Yeni Sekmede Aç
                </a>

                
                <button type="button" id="btn-kvkk-accept"
                    class="btn btn-lg text-white fw-semibold rounded-pill px-5 shadow-sm m-0" data-bs-dismiss="modal"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';">
                    <i class="fa-solid fa-check me-2"></i>Okudum, Kabul Ediyorum
                </button>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/auth/kvkk.blade.php ENDPATH**/ ?>