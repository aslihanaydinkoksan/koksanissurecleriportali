@extends('layouts.app')

{{-- Service Injection: Geçmiş verilerini çekmek için --}}
@inject('historyService', 'App\Services\CustomerHistoryService')

@section('title', $customer->name)

@push('styles')
    @include('customers.partials.styles')
@endpush

@section('content')
    <div class="container-fluid px-lg-5 py-4">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="customer-card">

                    {{-- Header Kısmı --}}
                    @include('customers.partials.header')

                    {{-- Alert Alanı --}}
                    @include('customers.partials.alerts')

                    <div class="card-body p-0">
                        <div class="row g-0">

                            {{-- 1. SOL SIDEBAR (DİKEY MENÜ) --}}
                            @include('customers.partials.sidebar')

                            {{-- 2. SAĞ İÇERİK ALANI --}}
                            <div class="col-lg-10 col-md-9">
                                <div class="tab-content tab-content-area" id="v-pills-tabContent">
                                    @include('customers.tabs.details')
                                    @include('customers.tabs.products')
                                    @include('customers.tabs.opportunities')
                                    @include('customers.tabs.activities')
                                    @include('customers.tabs.reports')
                                    @include('customers.tabs.visits')
                                    @include('customers.tabs.samples')
                                    @include('customers.tabs.returns')
                                    @include('customers.tabs.complaints')
                                    @include('customers.tabs.machines')
                                    @include('customers.tabs.tests')
                                    @include('customers.tabs.logistics')
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODALLAR --}}
        @include('customers.partials.modals')

        {{-- AKILLI ÜRÜN ÖNERİ LİSTESİ --}}
        <datalist id="productList">
            @foreach ($customer->products as $prod)
                <option value="{{ $prod->name }}"></option>
            @endforeach
        </datalist>

    </div>
@endsection

@section('page_scripts')
    @include('customers.partials.scripts')
@endsection
