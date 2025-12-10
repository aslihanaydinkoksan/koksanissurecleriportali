@extends('layouts.master')

@section('title', 'Çıkış Yap (Check-out)')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-top border-4 border-danger">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-danger">
                            <i class="fa fa-sign-out-alt me-2"></i> Çıkış İşlemi (Check-out)
                        </h5>
                        <small class="text-muted">
                            Mekan: <strong>{{ $stay->location->name }}</strong> |
                            Personel: <strong>{{ $stay->resident->first_name }} {{ $stay->resident->last_name }}</strong>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="fa fa-info-circle fa-2x me-3"></i>
                            <div>
                                Bu personel <strong>{{ $stay->check_in_date->format('d.m.Y') }}</strong> tarihinden beri bu
                                odada kalmaktadır.
                            </div>
                        </div>

                        <form action="{{ route('stays.processCheckout', $stay->id) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold">Çıkış Tarihi</label>
                                <input type="datetime-local" name="check_out_date" class="form-control"
                                    value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>

                            <div class="mb-4 bg-light p-3 rounded border">
                                <label class="form-label fw-bold text-primary"><i class="fa fa-tasks me-1"></i> İade ve
                                    Kontroller</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[keys_returned]"
                                                value="1" id="retKeys" checked>
                                            <label class="form-check-label" for="retKeys">Anahtar Geri Alındı</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[room_clear]"
                                                value="1" id="retClear" checked>
                                            <label class="form-check-label" for="retClear">Kişisel Eşya Kalmadı</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[damage_check]"
                                                value="1" id="retDamage">
                                            <label class="form-check-label" for="retDamage">Hasar Kontrolü Yapıldı</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[card_returned]"
                                                value="1" id="retCard">
                                            <label class="form-check-label" for="retCard">Giriş Kartı İade Alındı</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Çıkış Notları (Varsa hasar veya eksik)</label>
                                <textarea name="notes" class="form-control" rows="2"
                                    placeholder="Örn: Klima kumandası eksik, depozito iade edildi..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('locations.show', $stay->location_id) }}"
                                    class="btn btn-secondary">İptal</a>
                                <button type="submit" class="btn btn-danger px-5">
                                    <i class="fa fa-sign-out-alt me-1"></i> Çıkışı Onayla & Odayı Boşalt
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
