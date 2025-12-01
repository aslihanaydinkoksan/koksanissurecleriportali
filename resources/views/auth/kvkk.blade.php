@extends('layouts.app')

@section('title', 'KVKK Aydınlatma Metni')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h2 class="fw-bold text-primary">Kişisel Verilerin Korunması Aydınlatma Metni</h2>
                    </div>
                    <div class="card-body p-4" style="line-height: 1.8; color: #4a5568;">

                        {{-- BURAYA AVUKATLARIN METNİ GELECEK --}}
                        <p>
                            Sayın Kullanıcımız,<br><br>
                            KÖKSAN A.Ş. olarak kişisel verilerinizin güvenliği hususuna azami hassasiyet göstermekteyiz.
                            Avukatlarımız tarafından hazırlanan detaylı metin buraya eklenecektir.
                        </p>

                        <hr class="my-4">

                        <div class="text-center">
                            <a href="#" onclick="window.close();" class="btn btn-secondary">Pencereyi Kapat</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
