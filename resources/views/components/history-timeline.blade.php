@props(['activities', 'emptyMessage' => 'Kayıtlı geçmiş işlem bulunamadı.'])

<div class="timeline mt-4 pt-3 border-top timeline-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-secondary mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Son Hareketler</h6>
        @if(count($activities) > 5)
            <span class="badge bg-light text-muted border total-count-badge">{{ count($activities) }} Kayıt</span>
        @endif
    </div>
    
    <div class="timeline-items-wrapper">
        @forelse($activities as $index => $activity)
            <div class="card mb-2 border-0 shadow-sm timeline-item" 
                 style="background: rgba(248, 250, 252, 0.5); {{ $index >= 5 ? 'display: none;' : '' }}">
                <div class="card-body py-2 px-3 position-relative">
                    {{-- Sol Çizgi (Log Rengi) --}}
                    <div class="position-absolute top-0 start-0 bottom-0 rounded-start"
                         style="width: 4px; background: {{ $activity->description == 'created' ? '#10b981' : ($activity->description == 'deleted' ? '#ef4444' : '#3b82f6') }};">
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-white text-dark border me-2">
                                {{ class_basename($activity->subject_type) }}
                            </span>
                            <span class="text-dark small">
                                {{-- change logu varsa detay ver, yoksa description bas --}}
                                @if($activity->event == 'updated')
                                    Güncelleme
                                @elseif($activity->event == 'created')
                                    Yeni Kayıt
                                @elseif($activity->event == 'deleted')
                                    Silme
                                @else
                                    {{ $activity->description }}
                                @endif
                            </span>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ $activity->created_at->diffForHumans() }}
                        </small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted fst-italic">
                            <i class="fa-solid fa-user-circle me-1"></i>
                            {{ $activity->causer->name ?? 'Sistem' }}
                        </small>
                        
                        {{-- Eğer güncelleme ise nelerin değiştiğini göster (Tooltip ile) --}}
                        @if($activity->event == 'updated' && $activity->properties->has('attributes'))
                            <span class="text-primary small" style="cursor: help;" 
                                  title="Değişenler: {{ implode(', ', array_keys($activity->properties['attributes'])) }}">
                                <i class="fa-solid fa-info-circle"></i> Detay
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted small py-2">
                <i class="fa-solid fa-history opacity-50 mb-1"></i><br>
                {{ $emptyMessage }}
            </div>
        @endforelse
    </div>

    @if(count($activities) > 5)
        <div class="text-center mt-3">
            <button type="button" class="btn btn-sm btn-outline-primary btn-load-more px-3" data-step="5">
                <i class="fa-solid fa-chevron-down me-1"></i> Devamını Görüntüle
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary btn-collapse px-3" style="display: none;">
                <i class="fa-solid fa-chevron-up me-1"></i> Gizle
            </button>
        </div>
    @endif
</div>

<script>
document.addEventListener('click', function(e) {
    // Daha Fazla Yükle Butonu
    if (e.target.closest('.btn-load-more')) {
        const btn = e.target.closest('.btn-load-more');
        const wrapper = btn.closest('.timeline-wrapper');
        const items = wrapper.querySelectorAll('.timeline-item');
        const step = parseInt(btn.getAttribute('data-step') || 5);
        
        let shownCount = 0;
        let visibleItems = 0;
        
        items.forEach(item => {
            if (item.style.display !== 'none') {
                visibleItems++;
            }
        });

        items.forEach((item, index) => {
            if (item.style.display === 'none' && shownCount < step) {
                item.style.display = 'block';
                item.classList.add('animate__animated', 'animate__fadeIn'); // Şık görünüm için
                shownCount++;
            }
        });

        // Hepsini gösterdik mi?
        const totalHidden = wrapper.querySelectorAll('.timeline-item[style*="display: none"]').length;
        if (totalHidden === 0) {
            btn.style.display = 'none';
            wrapper.querySelector('.btn-collapse').style.display = 'inline-block';
        }
    }

    // Gizle Butonu
    if (e.target.closest('.btn-collapse')) {
        const btn = e.target.closest('.btn-collapse');
        const wrapper = btn.closest('.timeline-wrapper');
        const items = wrapper.querySelectorAll('.timeline-item');

        items.forEach((item, index) => {
            if (index >= 5) {
                item.style.display = 'none';
                item.classList.remove('animate__fadeIn');
            }
        });

        btn.style.display = 'none';
        wrapper.querySelector('.btn-load-more').style.display = 'inline-block';
        
        // Kaybolmasın diye en başa odakla (Opsiyonel)
        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>