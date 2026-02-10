<div class="modal fade" id="kvkkModal" tabindex="-1" aria-labelledby="kvkkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem; overflow: hidden;">

            {{-- Modal Başlık --}}
            <div class="modal-header border-0 position-relative"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem 2rem 1.5rem;">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-1" id="kvkkModalLabel" style="font-size: 1.35rem;">
                        <i class="fa-solid fa-shield-halved me-2"></i> KVKK Aydınlatma Metni
                    </h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.875rem;">Kişisel Verilerin Korunması Kanunu</p>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal"
                    aria-label="Kapat" style="filter: brightness(0) invert(1);"></button>
            </div>

            {{-- Modal İçerik --}}
            <div class="modal-body p-4 px-lg-5 py-lg-4"
                style="font-size: 0.95rem; line-height: 1.8; color: #2d3748; background-color: #f8f9fa;">

                <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                    <p class="mb-3">
                        <strong style="color: #667eea; font-size: 1.05rem;">Sayın Kullanıcımız,</strong><br>
                        <span style="color: #4a5568;">Veri güvenliğiniz ve gizliliğiniz bizim için önceliklidir.
                            Şeffaflık ilkemiz gereği, kişisel verilerinizin işlenmesi süreçleri hakkında sizi
                            bilgilendirmek isteriz.</span>
                    </p>
                </div>

                <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h6 class="text-primary fw-bold mb-3" style="font-size: 1rem;">
                        <i class="fa-solid fa-file-lines me-2"></i>Aydınlatma Metni
                    </h6>
                    <p style="color: #4a5568;">
                        Kişisel verileriniz, 6698 sayılı KVKK’ya uygun olarak yalnızca hizmet sunumu amacıyla KÖKSAN
                        tarafından güvenle saklanmakta ve işlenmektedir. Veri güvenliğiniz önceliğimizdir.
                    </p>
                </div>

                <div class="alert border-0 rounded-3 shadow-sm d-flex align-items-start mt-4" role="alert"
                    style="background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%); border-left: 4px solid #667eea !important;">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-info fa-lg mt-1" style="color: #667eea;"></i>
                    </div>
                    <div class="ms-3" style="color: #4c1d95;">
                        <strong class="d-block mb-1">Önemli Bilgilendirme</strong>
                        Sisteme giriş yaparak, bu şartları kabul etmiş sayılırsınız.
                    </div>
                </div>

            </div>

            {{-- Modal Alt Kısım --}}
            <div class="modal-footer border-0 pt-0 pb-4 px-4 px-lg-5" style="background-color: #f8f9fa;">
                <button type="button" id="btn-kvkk-accept"
                    class="btn btn-lg text-white fw-semibold rounded-pill px-5 shadow-sm" data-bs-dismiss="modal"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';">
                    <i class="fa-solid fa-check me-2"></i>Okudum, Anladım
                </button>
            </div>

        </div>
    </div>
</div>
