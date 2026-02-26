@props(['activities', 'emptyMessage' => 'Kayıtlı geçmiş işlem bulunamadı.'])

<div class="timeline mt-4 pt-3 border-top">
    <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-clock-rotate-left me-2"></i>Son Hareketler</h6>
    
    @forelse($activities as $activity)
        <div class="card mb-2 border-0 shadow-sm" style="background: rgba(248, 250, 252, 0.5);">
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