<div class="tab-pane fade" id="activities" role="tabpanel">
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background: #f8f9fa;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-plus-circle me-1"></i> Yeni İşlem Gir</h6>
                    <form action="{{ route('customers.activities.store', $customer->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">İşlem Tipi</label>
                            <select name="type" class="form-select">
                                <option value="phone">📞 Telefon Görüşmesi</option>
                                <option value="meeting">🤝 Yüz Yüze Toplantı</option>
                                <option value="email">✉️ E-Posta</option>
                                <option value="visit">🏢 Müşteri Ziyareti</option>
                                <option value="note">📝 Genel Not</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Görüşülen Kişiler</label>
                            <div class="dropdown mb-2">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start bg-white" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">Müşteri yetkililerini seçin...</button>
                                <ul class="dropdown-menu w-100 p-2 shadow-sm" style="max-height: 200px; overflow-y: auto;">
                                    @forelse($customer->contacts as $contact)
                                        <li>
                                            <div class="form-check m-1">
                                                <input class="form-check-input" type="checkbox" name="contact_persons[]" value="{{ $contact->name }}" id="new_contact_{{ $contact->id }}" style="cursor: pointer;">
                                                <label class="form-check-label" for="new_contact_{{ $contact->id }}" style="cursor: pointer;">{{ $contact->name }} <small class="text-muted">({{ $contact->title ?? 'Ünvan Yok' }})</small></label>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="text-muted small text-center p-2">Kayıtlı kişi bulunamadı.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <input type="text" name="other_contact_persons" class="form-control form-control-sm" placeholder="Farklı biri varsa yazın (Virgülle ayırın)...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Tarih & Saat</label>
                            <input type="datetime-local" name="activity_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Detaylar</label>
                            <div class="input-group">
                                <textarea name="description" id="new_activity_desc" class="form-control" rows="4" placeholder="Neler konuşuldu? Sonuç ne?" required></textarea>
                                <button class="btn btn-outline-secondary" type="button" id="btn_new_act_desc" onclick="toggleVoiceInput('new_activity_desc', 'btn_new_act_desc')"><i class="fa-solid fa-microphone"></i></button>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary text-white" style="background: linear-gradient(135deg, #667EEA, #764BA2); border:none;">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Geçmiş Hareketler</h6>
                <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
                    <i class="fa-solid fa-filter text-muted mx-1"></i>
                    <input type="date" id="filterActDate" class="filter-input bg-white">
                    <input type="text" id="filterActSearch" class="filter-input bg-white" placeholder="İçerik ara...">
                    <select id="filterActStatus" class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                        <option value="">Tüm Tipler</option>
                        <option value="phone">Telefon</option>
                        <option value="meeting">Toplantı</option>
                        <option value="email">E-Posta</option>
                        <option value="visit">Ziyaret</option>
                        <option value="note">Not</option>
                    </select>
                </div>
            </div>
            <div class="timeline" id="activitiesList">
                @forelse($customer->activities as $activity)
                    <div class="card mb-3 border-0 shadow-sm activity-item" data-date="{{ $activity->activity_date->format('Y-m-d') }}" data-search="{{ mb_strtolower($activity->description . ' ' . ($activity->user->name ?? '') . ' ' . implode(' ', $activity->contact_persons ?? [])) }}" data-status="{{ $activity->type }}">
                        <div class="card-body position-relative">
                            <div class="position-absolute top-0 start-0 bottom-0 rounded-start" style="width: 5px; background: {{ $activity->type == 'phone' ? '#3b82f6' : ($activity->type == 'meeting' ? '#10b981' : ($activity->type == 'email' ? '#f59e0b' : '#6b7280')) }};"></div>
                            <div class="position-absolute top-0 end-0 mt-3 me-3 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-link text-primary p-0" data-bs-toggle="modal" data-bs-target="#editActivityModal{{ $activity->id }}" title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('customer-activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Bu kaydı silmek istediğinize emin misiniz?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash-alt"></i></button>
                                </form>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 ps-2 pe-5">
                                <div>
                                    <span class="badge bg-light text-dark border me-2">
                                        @if ($activity->type == 'phone') <i class="fas fa-phone text-primary"></i> Telefon
                                        @elseif($activity->type == 'meeting') <i class="fas fa-handshake text-success"></i> Toplantı
                                        @elseif($activity->type == 'email') <i class="fas fa-envelope text-warning"></i> E-Posta
                                        @elseif($activity->type == 'visit') <i class="fas fa-building text-info"></i> Ziyaret
                                        @else <i class="fas fa-sticky-note text-secondary"></i> Not
                                        @endif
                                    </span>
                                    <span class="text-muted small">{{ $activity->activity_date->format('d.m.Y H:i') }}</span>
                                </div>
                                <small class="text-muted fst-italic"><i class="fas fa-user-circle me-1"></i>{{ $activity->user->name }}</small>
                            </div>
                            <div class="ps-2 mt-2">
                                @if (!empty($activity->contact_persons))
                                    <div class="mb-2">
                                        <small class="text-muted fw-bold"><i class="fa-solid fa-users me-1"></i>Görüşülen Kişiler:</small>
                                        <div class="mt-1">
                                            @foreach ($activity->contact_persons as $person)
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-2 py-1 me-1 mb-1">{{ $person }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="text-dark" style="white-space: pre-line;">{{ $activity->description }}</div>
                            </div>
                        </div>
                    </div>
                @empty 
                    <div class="alert alert-light text-center border border-dashed p-4 empty-message-row">
                        <i class="fas fa-history fa-2x text-muted mb-2"></i>
                        <p class="mb-0 text-muted">Henüz bu müşteriyle ilgili kaydedilmiş bir aktivite yok.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>