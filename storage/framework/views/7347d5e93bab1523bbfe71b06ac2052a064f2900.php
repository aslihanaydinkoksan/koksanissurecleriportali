

<?php $__env->startSection('title', $pageTitle); ?> 

<style>
    /* ... (Mevcut CSS stilleriniz aynı kalır) ... */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
    }

    @keyframes gradientWave {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .create-shipment-card {
        border-radius: 1rem;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        background-color: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .create-shipment-card .card-header,
    .create-shipment-card .form-label,
    .create-shipment-card .form-check-label,
    .create-shipment-card .card-body {
        color: #000;
        font-weight: 500;
    }

    .create-shipment-card .card-header {
        font-weight: bold;
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .create-shipment-card .form-control,
    .create-shipment-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 1);
        border: 1px solid #ced4da;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">

        
        <div id="stats-data-container" style="display: none;" data-chart-data='<?php echo json_encode($chartData ?? [], 15, 512) ?>'
            data-department-slug="<?php echo e($departmentSlug ?? ''); ?>" 
            <?php if($departmentSlug === 'lojistik'): ?> data-shipments='<?php echo json_encode($shipmentsForFiltering ?? [], 15, 512) ?>'
             
             <?php elseif($departmentSlug === 'uretim'): ?>
                data-production-plans='<?php echo json_encode($productionPlansForFiltering ?? [], 15, 512) ?>'
             
             <?php elseif($departmentSlug === 'hizmet'): ?>
                data-events='<?php echo json_encode($eventsForFiltering ?? [], 15, 512) ?>'
                data-assignments='<?php echo json_encode($assignmentsForFiltering ?? [], 15, 512) ?>'
                data-vehicles='<?php echo json_encode($vehiclesForFiltering ?? [], 15, 512) ?>'
                data-monthly-labels='<?php echo json_encode($monthlyLabels ?? [], 15, 512) ?>'  <?php endif; ?>>
        </div>

        
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0" style="color: #1e3a5f; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                    <?php echo e($pageTitle); ?>

                </h3>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="<?php echo e(route('home')); ?>" class="btn btn-link w-40"
                    style="border-color: #1a2332; font-weight: bold; color:#1e3a5f">&larr; Takvime Geri Dön</a>
            </div>
        </div>

        
        <div class="card create-shipment-card mb-4">
            <div class="card-header">📊 Grafik Filtreleri</div>
            <div class="card-body">

                
                <form method="GET" action="<?php echo e(route('statistics.index')); ?>">
                    <h6 class="mb-3">Ana Tarih Aralığı </h6>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="date_from" class="form-label">Başlangıç Tarihi:</label>
                            <input type="date" id="date_from" name="date_from" class="form-control"
                                value="<?php echo e($filters['date_from'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="date_to" class="form-label">Bitiş Tarihi:</label>
                            <input type="date" id="date_to" name="date_to" class="form-control"
                                value="<?php echo e($filters['date_to'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4 d-flex">
                            <button type="submit" class="btn btn-primary w-50 me-2"
                                style="background-color: #667EEA; border: none;">
                                <i class="fa-solid fa-filter me-1"></i> Filtrele
                            </button>
                            <a href="<?php echo e(route('statistics.index')); ?>" class="btn btn-outline-secondary w-50">
                                Temizle
                            </a>
                        </div>
                    </div>
                </form>

                
                <?php if($departmentSlug === 'lojistik'): ?>
                    <hr class="my-4">
                    <h6 class="mb-3">Lojistik Hızlı Filtreleri </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="shipmentTypeFilter" class="form-label">Sevkiyat Türü:</label>
                            <select id="shipmentTypeFilter" class="form-select">
                                <option value="all">Tümü (İthalat/İhracat)</option>
                                
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="vehicleTypeFilter" class="form-label">Araç Tipi:</label>
                            <select id="vehicleTypeFilter" class="form-select">
                                <option value="all">Tüm Araç Tipleri</option>
                                
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cargoContentFilter" class="form-label">Kargo İçeriği:</label>
                            <select id="cargoContentFilter" class="form-select">
                                <option value="all">Tüm Kargolar</option>
                                
                            </select>
                        </div>
                    </div>
                <?php elseif($departmentSlug === 'uretim'): ?>
                    <hr class="my-4">
                    <h6 class="mb-3">Üretim Hızlı Filtreleri (Anlık Günceller)</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="machineFilter" class="form-label">Makine:</label>
                            <select id="machineFilter" class="form-select">
                                <option value="all">Tüm Makineler</option>
                                
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="productFilter" class="form-label">Ürün:</label>
                            <select id="productFilter" class="form-select">
                                <option value="all">Tüm Ürünler</option>
                                
                            </select>
                        </div>
                    </div>
                <?php elseif($departmentSlug === 'hizmet'): ?>
                    <hr class="my-4">
                    <h6 class="mb-3">İdari İşler Hızlı Filtreleri (Anlık Günceller)</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="eventTypeFilter" class="form-label">Etkinlik Tipi:</label>
                            <select id="eventTypeFilter" class="form-select">
                                <option value="all">Tüm Etkinlik Tipleri</option>
                                
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="vehicleFilter" class="form-label">Araç:</label>
                            <select id="vehicleFilter" class="form-select">
                                <option value="all">Tüm Araçlar</option>
                                
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        


        

        
        <?php if($departmentSlug === 'lojistik'): ?>
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="vehicle-chart-title">Araç Tipi Kullanımı (Hızlı Filtre)</div>
                        <div class="card-body">
                            <div id="vehicle-type-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="cargo-chart-title">Kargo İçeriği Dağılımı (Hızlı Filtre)</div>
                        <div class="card-body">
                            <div id="cargo-content-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.5);">
            <h4 class="mb-3" style="color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                Genel İstatistikler (Tarih Aralığı: <?php echo e($filters['date_from']); ?> - <?php echo e($filters['date_to']); ?>)
            </h4>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="monthly-chart-lojistik" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="pie-chart-lojistik" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="hourly-chart-lojistik" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="daily-chart-lojistik" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="yearly-chart-lojistik" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            
        <?php elseif($departmentSlug === 'uretim'): ?>
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="machine-chart-title">Makine Kullanım Sayısı (Hızlı Filtre)</div>
                        <div class="card-body">
                            <div id="machine-chart-uretim" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="product-chart-title">Ürün Miktar Dağılımı (Hızlı Filtre)</div>
                        <div class="card-body">
                            <div id="product-chart-uretim" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.5);">
            <h4 class="mb-3" style="color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                Genel İstatistikler (Tarih Aralığı: <?php echo e($filters['date_from']); ?> - <?php echo e($filters['date_to']); ?>)
            </h4>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="weekly-prod-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="monthly-prod-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            
        <?php elseif($departmentSlug === 'hizmet'): ?>
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="event-pie-chart-title">Etkinlik Tipi Dağılımı (Hızlı Filtre)</div>
                        <div class="card-body">
                            <div id="event-type-pie-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="assignment-chart-title">Aylık Araç Atama Sayısı (Hızlı Filtre)</div>
                        <div class="card-body">
                            <div id="monthly-assign-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            

            
        <?php else: ?>
            <div class="alert alert-info create-shipment-card">Bu departman için özel istatistikler henüz mevcut değil.
            </div>
        <?php endif; ?>
        

    </div> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. GENEL AYARLAR VE VERİ OKUMA ---
            const colorPalette = ['#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9', '#667EEA', '#764BA2'];
            const statsContainer = document.getElementById('stats-data-container');

            if (!statsContainer) {
                console.error('stats-data-container bulunamadı!');
                return;
            }

            let chartData = {};
            let departmentSlug = '';

            try {
                chartData = JSON.parse(statsContainer.dataset.chartData || '{}');
                departmentSlug = statsContainer.dataset.departmentSlug || '';
            } catch (error) {
                console.error('Chart data parse hatası:', error);
                return;
            }

            // --- 2. GENEL GRAFİK AYARLARI ---
            const commonChartOptions = {
                chart: {
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                colors: colorPalette,
                legend: {
                    show: false
                },
                title: {
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: '#2d3748'
                    }
                },
                noData: {
                    text: 'Gösterilecek veri bulunamadı.'
                },
                dataLabels: {
                    enabled: false
                }
            };
            const commonBarOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'bar'
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 8
                    }
                }
            };
            const commonAreaOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'area'
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.1
                    }
                }
            };
            const commonPieOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'donut'
                },
                legend: {
                    position: 'bottom',
                    show: true
                }
            };


            // --- 3. DEPARTMANA ÖZEL GRAFİK MANTIĞI ---

            // --- LOJİSTİK BÖLÜMÜ ---
            if (departmentSlug === 'lojistik') {
                console.log('Lojistik grafikleri çiziliyor...');

                // --- BÖLÜM 1: HIZLI FİLTRE GRAFİKLERİ (JS) ---
                const allShipmentsData = JSON.parse(statsContainer.dataset.shipments || '[]');

                const shipmentTypeDropdown = document.getElementById('shipmentTypeFilter');
                const vehicleTypeDropdown = document.getElementById('vehicleTypeFilter');
                const cargoContentDropdown = document.getElementById('cargoContentFilter');

                const vehicleChartTitle = document.getElementById('vehicle-chart-title');
                const cargoChartTitle = document.getElementById('cargo-chart-title');

                // Grafikleri Başlat
                let vehicleChart = new ApexCharts(document.querySelector("#vehicle-type-chart"), {
                    ...commonBarOptions,
                    series: [{
                        name: 'Kullanım Sayısı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Araç Tipi Kullanımı (Hızlı Filtre)'
                    }
                });
                vehicleChart.render();

                let cargoChart = new ApexCharts(document.querySelector("#cargo-content-chart"), {
                    ...commonBarOptions,
                    series: [{
                        name: 'Kargo Sayısı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Kargo İçeriği Dağılımı (Hızlı Filtre)'
                    }
                });
                cargoChart.render();

                // Dropdown'ları Doldurma
                function populateLojistikFilters() {
                    if (!allShipmentsData) return;
                    const types = new Set(allShipmentsData.map(s => s.shipment_type));
                    const vehicles = new Set(allShipmentsData.map(s => s.vehicle));
                    const cargos = new Set(allShipmentsData.map(s => s.cargo));

                    types.forEach(type => {
                        if (type) shipmentTypeDropdown.innerHTML +=
                            `<option value="${type}">${type === 'import' ? 'İthalat' : 'İhracat'}</option>`;
                    });
                    vehicles.forEach(vehicle => {
                        if (vehicle) vehicleTypeDropdown.innerHTML +=
                            `<option value="${vehicle}">${vehicle}</option>`;
                    });
                    cargos.forEach(cargo => {
                        if (cargo) cargoContentDropdown.innerHTML +=
                            `<option value="${cargo}">${cargo}</option>`;
                    });
                }

                // Grafikleri Güncelleme
                function updateLojistikCharts() {
                    const selectedType = shipmentTypeDropdown.value;
                    const selectedVehicle = vehicleTypeDropdown.value;
                    const selectedCargo = cargoContentDropdown.value;

                    let filteredData = allShipmentsData;

                    if (selectedType !== 'all') {
                        filteredData = filteredData.filter(s => s.shipment_type === selectedType);
                    }
                    if (selectedVehicle !== 'all') {
                        filteredData = filteredData.filter(s => s.vehicle === selectedVehicle);
                    }
                    if (selectedCargo !== 'all') {
                        filteredData = filteredData.filter(s => s.cargo === selectedCargo);
                    }

                    const vehicleCounts = {},
                        cargoCounts = {};
                    filteredData.forEach(shipment => {
                        if (shipment && shipment.vehicle) vehicleCounts[shipment.vehicle] = (vehicleCounts[
                            shipment.vehicle] || 0) + 1;
                        if (shipment && shipment.cargo) cargoCounts[shipment.cargo] = (cargoCounts[shipment
                            .cargo] || 0) + 1;
                    });

                    const sortedVehicles = Object.entries(vehicleCounts).sort((a, b) => b[1] - a[1]);
                    const sortedCargo = Object.entries(cargoCounts).sort((a, b) => b[1] - a[1]);

                    vehicleChart.updateOptions({
                        xaxis: {
                            categories: sortedVehicles.map(([name]) => name)
                        }
                    }, false, false);
                    vehicleChart.updateSeries([{
                        data: sortedVehicles.map(([, count]) => count)
                    }], true);

                    cargoChart.updateOptions({
                        xaxis: {
                            categories: sortedCargo.map(([name]) => name)
                        }
                    }, false, false);
                    cargoChart.updateSeries([{
                        data: sortedCargo.map(([, count]) => count)
                    }], true);
                }

                shipmentTypeDropdown.addEventListener('change', updateLojistikCharts);
                vehicleTypeDropdown.addEventListener('change', updateLojistikCharts);
                cargoContentDropdown.addEventListener('change', updateLojistikCharts);

                populateLojistikFilters();
                updateLojistikCharts();

                // --- BÖLÜM 2: GENEL (SUNUCU TARAFLI FİLTRELENMİŞ) GRAFİKLER ---
                if (chartData.monthly) new ApexCharts(document.querySelector("#monthly-chart-lojistik"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.monthly.data || []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: chartData.monthly.title
                    },
                    xaxis: {
                        categories: chartData.monthly.labels || []
                    }
                }).render();
                if (chartData.pie) new ApexCharts(document.querySelector("#pie-chart-lojistik"), {
                    ...commonPieOptions,
                    series: chartData.pie.data || [],
                    labels: chartData.pie.labels || [],
                    title: {
                        ...commonPieOptions.title,
                        text: chartData.pie.title
                    }
                }).render();
                if (chartData.hourly) new ApexCharts(document.querySelector("#hourly-chart-lojistik"), {
                    ...commonBarOptions,
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
                    },
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.hourly.data || []
                    }],
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.hourly.title
                    },
                    xaxis: {
                        categories: chartData.hourly.labels || [],
                        tickAmount: 12
                    }
                }).render();
                if (chartData.daily) new ApexCharts(document.querySelector("#daily-chart-lojistik"), {
                    ...commonBarOptions,
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
                    },
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.daily.data || []
                    }],
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.daily.title
                    },
                    xaxis: {
                        categories: chartData.daily.labels || []
                    }
                }).render();
                if (chartData.yearly) new ApexCharts(document.querySelector("#yearly-chart-lojistik"), {
                    ...commonBarOptions,
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
                    },
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.yearly.data || []
                    }],
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.yearly.title
                    },
                    xaxis: {
                        categories: chartData.yearly.labels || []
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true,
                            borderRadius: 8
                        }
                    }
                }).render();
                console.log('Lojistik genel grafikleri tamamlandı');
            }

            // --- ÜRETİM BÖLÜMÜ ---
            else if (departmentSlug === 'uretim') {
                console.log('Üretim grafikleri çiziliyor...');

                // --- BÖLÜM 1: HIZLI FİLTRE GRAFİKLERİ (JS) ---
                const allPlansData = JSON.parse(statsContainer.dataset.productionPlans || '[]');

                const machineDropdown = document.getElementById('machineFilter');
                const productDropdown = document.getElementById('productFilter');

                let machineChart = new ApexCharts(document.querySelector("#machine-chart-uretim"), {
                    ...commonBarOptions,
                    series: [{
                        name: 'Kullanım Sayısı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Makine Kullanım Sayısı (Hızlı Filtre)'
                    }
                });
                machineChart.render();

                let productChart = new ApexCharts(document.querySelector("#product-chart-uretim"), {
                    ...commonBarOptions,
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true,
                            borderRadius: 8
                        }
                    }, // Yatay bar
                    series: [{
                        name: 'Üretim Miktarı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Ürün Miktar Dağılımı (Hızlı Filtre)'
                    }
                });
                productChart.render();

                function populateProductionFilters() {
                    if (!allPlansData) return;
                    const machines = new Set(allPlansData.map(p => p.machine));
                    const products = new Set(allPlansData.map(p => p.product));

                    machines.forEach(machine => {
                        if (machine && machine !== 'Bilinmiyor') machineDropdown.innerHTML +=
                            `<option value="${machine}">${machine}</option>`;
                    });
                    products.forEach(product => {
                        if (product && product !== 'Bilinmiyor') productDropdown.innerHTML +=
                            `<option value="${product}">${product}</option>`;
                    });
                }

                function updateProductionCharts() {
                    const selectedMachine = machineDropdown.value;
                    const selectedProduct = productDropdown.value;

                    let filteredData = allPlansData;

                    if (selectedMachine !== 'all') {
                        filteredData = filteredData.filter(p => p.machine === selectedMachine);
                    }
                    if (selectedProduct !== 'all') {
                        filteredData = filteredData.filter(p => p.product === selectedProduct);
                    }

                    const machineCounts = {},
                        productQuantities = {};

                    filteredData.forEach(plan => {
                        if (plan.machine !== 'Bilinmiyor') machineCounts[plan.machine] = (machineCounts[plan
                            .machine] || 0) + 1;
                        if (plan.product !== 'Bilinmiyor') productQuantities[plan.product] = (
                            productQuantities[plan.product] || 0) + plan.quantity;
                    });

                    const sortedMachines = Object.entries(machineCounts).sort((a, b) => b[1] - a[1]);
                    const sortedProducts = Object.entries(productQuantities).sort((a, b) => b[1] - a[1]).slice(0,
                        15); // Çok fazla ürün varsa ilk 15'i al

                    machineChart.updateOptions({
                        xaxis: {
                            categories: sortedMachines.map(([name]) => name)
                        }
                    }, false, false);
                    machineChart.updateSeries([{
                        data: sortedMachines.map(([, count]) => count)
                    }], true);

                    productChart.updateOptions({
                        xaxis: {
                            categories: sortedProducts.map(([name]) => name)
                        }
                    }, false, false);
                    productChart.updateSeries([{
                        data: sortedProducts.map(([, count]) => count)
                    }], true);
                }

                machineDropdown.addEventListener('change', updateProductionCharts);
                productDropdown.addEventListener('change', updateProductionCharts);

                populateProductionFilters();
                updateProductionCharts();

                // --- BÖLÜM 2: GENEL (SUNUCU TARAFLI FİLTRELENMİŞ) GRAFİKLER ---
                if (chartData.weekly_prod) new ApexCharts(document.querySelector("#weekly-prod-chart"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan Sayısı',
                        data: chartData.weekly_prod.data || []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: chartData.weekly_prod.title
                    },
                    xaxis: {
                        categories: chartData.weekly_prod.labels || [],
                        tickAmount: 10,
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    }
                }).render();
                if (chartData.monthly_prod) new ApexCharts(document.querySelector("#monthly-prod-chart"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan Sayısı',
                        data: chartData.monthly_prod.data || []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: chartData.monthly_prod.title
                    },
                    xaxis: {
                        categories: chartData.monthly_prod.labels || [],
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    }
                }).render();

                console.log('Üretim grafikleri tamamlandı');
            }

            // --- HİZMET BÖLÜMÜ ---
            else if (departmentSlug === 'hizmet') {
                console.log('İdari İşler grafikleri çiziliyor...');

                // --- BÖLÜM 1: HIZLI FİLTRE GRAFİKLERİ (JS) ---
                const allEventsData = JSON.parse(statsContainer.dataset.events || '[]');
                const allAssignmentsData = JSON.parse(statsContainer.dataset.assignments || '[]');
                const allVehicles = JSON.parse(statsContainer.dataset.vehicles || '[]');
                const monthlyLabels = JSON.parse(statsContainer.dataset.monthlyLabels || '[]');

                // 2. DOM Elemanlarını Seç
                const eventTypeDropdown = document.getElementById('eventTypeFilter');
                const vehicleDropdown = document.getElementById('vehicleFilter');

                // 3. Grafikleri Başlat
                let eventPieChart = new ApexCharts(document.querySelector("#event-type-pie-chart"), {
                    ...commonPieOptions,
                    series: [],
                    labels: [],
                    title: {
                        ...commonPieOptions.title,
                        text: 'Etkinlik Tipi Dağılımı (Hızlı Filtre)'
                    }
                });
                eventPieChart.render();

                let assignmentChart = new ApexCharts(document.querySelector("#monthly-assign-chart"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Atama Sayısı',
                        data: []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: 'Aylık Araç Atama Sayısı (Hızlı Filtre)'
                    },
                    xaxis: {
                        categories: monthlyLabels
                    } // Etiketleri PHP'den al
                });
                assignmentChart.render();

                // 4. Filtreleri Doldur
                function populateServiceFilters() {
                    const eventTypes = new Map();
                    // Gelen tüm veriyi (Event + Travel) işle
                    allEventsData.forEach(e => {
                        if (e.type_name) eventTypes.set(e.type_slug, e.type_name);
                    });

                    // Dropdown'ı doldur
                    eventTypes.forEach((name, slug) => {
                        eventTypeDropdown.innerHTML += `<option value="${slug}">${name}</option>`;
                    });

                    allVehicles.forEach(vehicle => {
                        vehicleDropdown.innerHTML +=
                            `<option value="${vehicle.id}">${vehicle.plate_number}</option>`;
                    });
                }

                // 5. Grafikleri Güncelle
                function updateServiceCharts() {
                    const selectedEventType = eventTypeDropdown.value;
                    const selectedVehicleId = vehicleDropdown.value;

                    // 1. Etkinlik Pastasını Güncelle
                    let filteredEvents = allEventsData;
                    if (selectedEventType !== 'all') {
                        // 'type_slug'a göre filtrele ('event' veya 'travel' olabilir)
                        filteredEvents = filteredEvents.filter(e => e.type_slug === selectedEventType);
                    }

                    const eventCounts = {};
                    // 'type_name'e göre sayım yap
                    filteredEvents.forEach(event => {
                        eventCounts[event.type_name] = (eventCounts[event.type_name] || 0) + 1;
                    });

                    const sortedEventTypes = Object.entries(eventCounts).sort((a, b) => b[1] - a[1]);

                    eventPieChart.updateOptions({
                        labels: sortedEventTypes.map(([name]) => name),
                        series: sortedEventTypes.map(([, count]) => count)
                    });

                    // 2. Araç Atama Grafiğini Güncelle
                    let filteredAssignments = allAssignmentsData;
                    if (selectedVehicleId !== 'all') {
                        filteredAssignments = filteredAssignments.filter(a => a.vehicle_id == selectedVehicleId);
                    }

                    const monthlyCounts = {};
                    monthlyLabels.forEach(label => monthlyCounts[label] = 0); // ['Oca 2025': 0, ...]

                    filteredAssignments.forEach(assignment => {
                        const monthLabel = assignment.start_month_label;
                        if (monthLabel in monthlyCounts) {
                            monthlyCounts[monthLabel]++;
                        }
                    });

                    assignmentChart.updateSeries([{
                        data: Object.values(monthlyCounts)
                    }]);
                }

                // 6. Dinleyicileri Ekle
                eventTypeDropdown.addEventListener('change', updateServiceCharts);
                vehicleDropdown.addEventListener('change', updateServiceCharts);

                // 7. İlk Yükleme
                populateServiceFilters();
                updateServiceCharts();

                console.log('İdari İşler grafikleri tamamlandı');
            }
        }); // DOMContentLoaded sonu
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/statistics/index.blade.php ENDPATH**/ ?>