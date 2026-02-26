<div class="col-lg-2 col-md-3">
    <div class="sidebar-wrapper">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

            {{-- Kategori: Genel --}}
            <div class="sidebar-category-title">Genel Bakış</div>
            <button class="nav-link active" id="details-tab" data-bs-toggle="pill" data-bs-target="#details" type="button"
                role="tab">
                <span><i class="fa-solid fa-user"></i> Detaylar</span>
            </button>
            <button class="nav-link" id="activities-tab" data-bs-toggle="pill" data-bs-target="#activities"
                type="button" role="tab">
                <span><i class="fas fa-history"></i> İletişim</span>
                <span class="badge bg-white text-dark border shadow-sm">{{ $customer->activities->count() }}</span>
            </button>
            <button class="nav-link" id="reports-tab" data-bs-toggle="pill" data-bs-target="#reports" type="button"
                role="tab">
                <span><i class="fa-solid fa-chart-pie text-warning"></i> Analiz & Raporlar</span>
            </button>

            {{-- Kategori: Ticari --}}
            <div class="sidebar-category-title">Ticari</div>
            <button class="nav-link" id="products-tab" data-bs-toggle="pill" data-bs-target="#products" type="button"
                role="tab">
                <span><i class="fa-solid fa-box-open"></i> Ürünler</span>
                @if ($customer->products->count() > 0)
                    <span
                        class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill">{{ $customer->products->count() }}</span>
                @endif
            </button>
            <button class="nav-link" id="opportunities-tab" data-bs-toggle="pill" data-bs-target="#opportunities"
                type="button" role="tab">
                <span><i class="fa-solid fa-bullseye"></i> Fırsatlar</span>
                @if ($customer->opportunities->count() > 0)
                    <span
                        class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill">{{ $customer->opportunities->count() }}</span>
                @endif
            </button>
            <button class="nav-link" id="samples-tab" data-bs-toggle="pill" data-bs-target="#samples" type="button"
                role="tab">
                <span><i class="fa-solid fa-flask"></i> Numuneler</span>
                @if ($customer->samples->count() > 0)
                    <span
                        class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill">{{ $customer->samples->count() }}</span>
                @endif
            </button>

            {{-- Kategori: Teknik --}}
            <div class="sidebar-category-title">Teknik</div>
            <button class="nav-link" id="visits-tab" data-bs-toggle="pill" data-bs-target="#visits" type="button"
                role="tab">
                <span><i class="fa-solid fa-calendar-days"></i> Ziyaretler</span>
                @if ($customer->visits->count() > 0)
                    <span
                        class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">{{ $customer->visits->count() }}</span>
                @endif
            </button>
            <button class="nav-link" id="machines-tab" data-bs-toggle="pill" data-bs-target="#machines" type="button"
                role="tab">
                <span><i class="fa-solid fa-industry"></i> Makineler</span>
                @if ($customer->machines->count() > 0)
                    <span
                        class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill">{{ $customer->machines->count() }}</span>
                @endif
            </button>
            <button class="nav-link" id="tests-tab" data-bs-toggle="pill" data-bs-target="#tests" type="button"
                role="tab">
                <span><i class="fa-solid fa-vial"></i> Testler</span>
                @if ($customer->testResults->count() > 0)
                    <span
                        class="badge bg-light text-dark border rounded-pill">{{ $customer->testResults->count() }}</span>
                @endif
            </button>

            {{-- Kategori: Destek --}}
            <div class="sidebar-category-title">Destek</div>
            <button class="nav-link" id="logistics-tab" data-bs-toggle="pill" data-bs-target="#logistics" type="button"
                role="tab">
                <span><i class="fas fa-truck"></i> Lojistik</span>
                @if ($customer->vehicleAssignments->count() > 0)
                    <span
                        class="badge bg-dark bg-opacity-10 text-dark border border-dark rounded-pill">{{ $customer->vehicleAssignments->count() }}</span>
                @endif
            </button>
            <button class="nav-link" id="complaints-tab" data-bs-toggle="pill" data-bs-target="#complaints"
                type="button" role="tab">
                <span><i class="fa-solid fa-exclamation-triangle"></i> Şikayetler</span>
                @if ($customer->complaints->count() > 0)
                    <span
                        class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill">{{ $customer->complaints->count() }}</span>
                @endif
            </button>
            <button class="nav-link" id="returns-tab" data-bs-toggle="pill" data-bs-target="#returns" type="button"
                role="tab">
                <span><i class="fa-solid fa-rotate-left"></i> İadeler</span>
                @if ($customer->returns->count() > 0)
                    <span
                        class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill">{{ $customer->returns->count() }}</span>
                @endif
            </button>
        </div>
    </div>
</div>
