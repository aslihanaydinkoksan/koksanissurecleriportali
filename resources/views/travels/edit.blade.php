@extends('layouts.app')

@section('title', 'Seyahat Planını Düzenle')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">"{{ $travel->name }}" Planını Düzenle</h4>
                    </div>
                    <div class="card-body px-4">
                        {{-- GÜNCELLEME FORMU --}}
                        <form action="{{ route('travels.update', $travel) }}" method="POST" id="update-form"
                            autocomplete="off">
                            @csrf
                            @method('PUT')
                            @include('travels._form', ['travel' => $travel])
                        </form>

                        <div class="d-flex justify-content-between mt-3">
                            {{-- SİLME BUTONU --}}
                            {{-- type="button" olması kritik, yoksa formu submit etmeye çalışır --}}
                            <button type="button" class="btn btn-outline-danger rounded-pill px-4"
                                onclick="confirmDeleteTravel({{ $travel->id }})">
                                <i class="fa-solid fa-trash-alt me-1"></i> Sil
                            </button>

                            <div>
                                <a href="{{ route('travels.index') }}"
                                    class="btn btn-outline-secondary rounded-pill px-4 me-2">
                                    İptal
                                </a>

                                <button type="submit" class="btn btn-primary-gradient rounded-pill px-4"
                                    style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;"
                                    form="update-form">
                                    <i class="fa-solid fa-save me-1"></i>
                                    Değişiklikleri Kaydet
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        window.confirmDeleteTravel = function(id) {
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu seyahat planı silinecek! Bağlı ziyaretler silinmez, bağımsız hale gelir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Axios isteği
                    axios.delete(`/travels/${id}`)
                        .then(response => {
                            // Başarılı olursa
                            if (response.data.status === 'success') {
                                Swal.fire({
                                    title: 'Silindi!',
                                    text: response.data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Listeye yönlendir
                                    window.location.href = response.data.redirect_url;
                                });
                            }
                        })
                        .catch(error => {
                            // Hata olursa
                            console.error('Silme hatası:', error);
                            Swal.fire(
                                'Hata!',
                                error.response?.data?.message || 'Bir sorun oluştu, silinemedi.',
                                'error'
                            );
                        });
                }
            });
        }
    </script>
@endsection
