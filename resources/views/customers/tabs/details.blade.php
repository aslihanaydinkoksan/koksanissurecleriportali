<div class="tab-pane fade show active" id="details" role="tabpanel">
    <div class="row">
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="mb-0 border-0"><i class="fa-solid fa-building me-2"></i>Firma Bilgileri</h5>
            </div>
            <dl class="row detail-list mt-3">
                <dt class="col-sm-4 text-primary">Çalışma Başlangıcı</dt>
                <dd class="col-sm-8 fw-bold text-primary">
                    {{ $customer->start_date ? \Carbon\Carbon::parse($customer->start_date)->format('d.m.Y') : 'Tarih Belirtilmedi' }}
                </dd>
                @if (!($customer->is_active ?? true) && $customer->end_date)
                    <dt class="col-sm-4 text-danger">Çalışma Bitişi</dt>
                    <dd class="col-sm-8 fw-bold text-danger">
                        {{ \Carbon\Carbon::parse($customer->end_date)->format('d.m.Y') }}
                    </dd>
                @endif
                <dt class="col-sm-4 mt-2">Adres</dt>
                <dd class="col-sm-8 mt-2">{{ $customer->address ?: '-' }}</dd>
                <dt class="col-sm-4">Genel Tel</dt>
                <dd class="col-sm-8">{{ $customer->phone ?: '-' }}</dd>
                <dt class="col-sm-4">Genel Email</dt>
                <dd class="col-sm-8">{{ $customer->email ?: '-' }}</dd>
            </dl>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="mb-0 border-0"><i class="fa-solid fa-users me-2"></i>İletişim Kişileri</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>Ad Soyad</th>
                            <th>Ünvan</th>
                            <th>İletişim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->contacts as $contact)
                            <tr>
                                <td class="align-middle"><span class="fw-bold text-dark">{{ $contact->name }}</span>
                                    @if ($contact->is_primary)
                                        <i class="fa-solid fa-star text-warning small ms-1" title="Ana İletişim"></i>
                                    @endif
                                </td>
                                <td class="align-middle text-muted small">{{ $contact->title ?? '-' }}</td>
                                <td class="small">
                                    @if ($contact->email)
                                        <div class="mb-1"><i class="fa-solid fa-envelope text-primary me-1"></i>{{ $contact->email }}</div>
                                    @endif
                                    @if ($contact->phone)
                                        <div><i class="fa-solid fa-phone text-success me-1"></i>{{ $contact->phone }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty 
                            <tr>
                                <td colspan="3" class="text-center text-muted">Kayıtlı kişi yok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>