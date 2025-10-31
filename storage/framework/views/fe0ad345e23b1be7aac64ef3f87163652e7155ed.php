

<?php $__env->startSection('title', $pageTitle); ?> 

<style>
    /* ... (Mevcut CSS stilleriniz aynı kalır) ... */
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg,
                #dbe4ff,
                #fde2ff,
                #d9fcf7,
                #fff0d9);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
    }

    /* Arka plan dalgalanma animasyonu */
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

    /* === YARI ŞEFFAF "FROSTED GLASS" KART STİLİ === */
    .create-shipment-card {
        border-radius: 1rem;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        background-color: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    /* Okunabilirlik için metin stilleri (form-check-label eklendi) */
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

    /* === BU SAYFA İÇİN GEREKLİ FORM STİLLERİ === */
    .create-shipment-card .form-control,
    .create-shipment-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 1);
        border: 1px solid #ced4da;
    }

    .create-shipment-card .form-check-input:checked {
        background-color: #667EEA;
        border-color: #667EEA;
    }

    /* (home.blade.php'den gelen buton stili) */
    .btn-animated-gradient {
        background: linear-gradient(-45deg,
                #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    }

    .btn-animated-gradient:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-custom {
        color: #9DECF9;
        border-color: #9DECF9;
    }

    .btn-outline-custom:hover {
        background-color: #9DECF9;
        color: #000;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div id="stats-data-container" style="display: none;" data-chart-data='<?php echo json_encode($chartData ?? [], 15, 512) ?>'
            data-department-slug="<?php echo e($departmentSlug ?? ''); ?>">
        </div>

        
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0" style="color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                    <?php echo e($pageTitle); ?>

                </h3>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="<?php echo e(route('home')); ?>" class="btn btn-link w-40"
                    style="border-color: #9DECF9; font-weight: bold; color:#fff">&larr; Ana Sayfaya Geri Dön</a>
            </div>
        </div>

        
        <?php if($departmentSlug === 'lojistik'): ?>
            <div class="card create-shipment-card mb-4">
                <div class="card-header">Grafik Filtreleri</div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="dateFilter" class="form-label">Tarih Seçin:</label>
                            <input type="date" id="dateFilter" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Sevkiyat Türü:</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="shipmentTypeFilter" id="type_all"
                                        value="all" checked>
                                    _ <label class="form-check-label" for="type_all">Tümü</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="shipmentTypeFilter"
                                        id="type_import" value="import">
                                    <label class="form-check-label" for="type_import">İthalat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="shipmentTypeFilter"
                                        id="type_export" value="export">
                                    <label class="form-check-label" for="type_export">İhracat</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button id="clearFilterBtn" class="btn btn-outline-secondary w-100"
                                style="border-color: #9DECF9;">Filtreyi Temizle</button>
                        </div>
                    </div>
                </div>
                <div id="filter-data-container" style="display: none;" data-shipments='<?php echo json_encode($shipmentsForFiltering ?? [], 15, 512) ?>'></div>
            </div>
        <?php endif; ?>
        


        

        
        <?php if($departmentSlug === 'lojistik'): ?>
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="vehicle-chart-title">Araç Tipi Kullanımı (Tümü)
                        </div>
                        <div class="card-body">
                            <div id="vehicle-type-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-header" id="cargo-chart-title">Kargo İçeriği Dağılımı
                            (Tümü)</div>
                        <div class="card-body">
                            <div id="cargo-content-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>


            <hr class="my-4" style="border-color: rgba(255,255,255,0.5);">

            <h4 class="mb-3" style="color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">Genel Sevkiyat
                İstatistikleri
                (Tüm Zamanlar)</h4>

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
                        _   <div class="card-body">
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
                            _           <div id="monthly-prod-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center mt-4" style="color:#fff;">Daha fazla üretim istatistiği eklenecektir.</p>

            
        <?php elseif($departmentSlug === 'hizmet'): ?>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="event-type-pie-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card create-shipment-card">
                        <div class="card-body">
                            <div id="monthly-assign-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center mt-4" style="color:#fff;">Daha fazla hizmet istatistiği eklenecektir.</p>

            
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
            const colorPalette = ['#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9', '#667EEA', '#764BA2'];
            const statsContainer = document.getElementById('stats-data-container');
            const filterDataContainer = document.getElementById('filter-data-container');

            if (!statsContainer) {
                console.error('stats-data-container bulunamadı!');
                return;
            }

            let chartData = {};
            let departmentSlug = '';

            try {
                chartData = JSON.parse(statsContainer.dataset.chartData || '{}');
                departmentSlug = statsContainer.dataset.departmentSlug || '';
                console.log('Statistics Data:', {
                    department: departmentSlug,
                    chartKeys: Object.keys(chartData),
                    chartData: chartData
                });
            } catch (error) {
                console.error('Chart data parse hatası:', error);
                return;
            }

            // --- Genel ApexCharts Ayarları ---
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

            // --- Lojistik Grafikleri ---
            if (departmentSlug === 'lojistik') {
                console.log('Lojistik grafikleri çiziliyor...');

                // Filtrelenebilir Grafikler
                if (filterDataContainer) {
                    const allShipmentsData = JSON.parse(filterDataContainer.dataset.shipments || '[]');
                    const dateInput = document.getElementById('dateFilter');
                    const clearButton = document.getElementById('clearFilterBtn');
                    const typeFilterRadios = document.querySelectorAll('input[name="shipmentTypeFilter"]');
                    const vehicleChartTitle = document.getElementById('vehicle-chart-title');
                    const cargoChartTitle = document.getElementById('cargo-chart-title');

                    let vehicleChart = null;
                    let cargoChart = null;

                    // Grafikleri başlat
                    if (document.querySelector("#vehicle-type-chart")) {
                        vehicleChart = new ApexCharts(document.querySelector("#vehicle-type-chart"), {
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
                                text: 'Araç Tipi Kullanımı'
                            }
                        });
                        vehicleChart.render();
                    }

                    if (document.querySelector("#cargo-content-chart")) {
                        cargoChart = new ApexCharts(document.querySelector("#cargo-content-chart"), {
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
                                text: 'Kargo İçeriği Dağılımı'
                            }
                        });
                        cargoChart.render();
                    }

                    function updateFilteredCharts() {
                        const selectedDate = dateInput.value;
                        const selectedType = document.querySelector('input[name="shipmentTypeFilter"]:checked')
                            .value;
                        let filteredData = allShipmentsData;
                        let dateText = "Tüm Zamanlar";
                        let typeText = "Tümü";

                        if (selectedDate) {
                            const [year, month, day] = selectedDate.split('-').map(Number);
                            filteredData = filteredData.filter(shipment => {
                                return shipment &&
                                    shipment.year === year &&
                                    shipment.month === month &&
                                    shipment.day === day;
                            });
                            dateText = `${String(day).padStart(2, '0')}.${String(month).padStart(2, '0')}.${year}`;
                        }

                        if (selectedType !== 'all') {
                            filteredData = filteredData.filter(shipment => {
                                return shipment && shipment.shipment_type === selectedType;
                            });
                            typeText = selectedType === 'import' ? 'İthalat' : 'İhracat';
                        }

                        // Araç ve Kargo sayılarını hesapla
                        const vehicleCounts = {};
                        const cargoCounts = {};

                        filteredData.forEach(shipment => {
                            if (shipment && shipment.vehicle) {
                                vehicleCounts[shipment.vehicle] = (vehicleCounts[shipment.vehicle] || 0) +
                                    1;
                            }
                            if (shipment && shipment.cargo) {
                                cargoCounts[shipment.cargo] = (cargoCounts[shipment.cargo] || 0) + 1;
                            }
                        });

                        // Sırala
                        const sortedVehicles = Object.entries(vehicleCounts).sort((a, b) => b[1] - a[1]);
                        const sortedCargo = Object.entries(cargoCounts).sort((a, b) => b[1] - a[1]);

                        // Başlıkları güncelle
                        if (vehicleChartTitle) {
                            vehicleChartTitle.textContent = `Araç Tipi Kullanımı (${typeText} - ${dateText})`;
                        }
                        if (cargoChartTitle) {
                            cargoChartTitle.textContent = `Kargo İçeriği Dağılımı (${typeText} - ${dateText})`;
                        }

                        // Grafikleri güncelle
                        if (vehicleChart) {
                            vehicleChart.updateOptions({
                                xaxis: {
                                    categories: sortedVehicles.map(([name]) => name)
                                }
                            }, false, false);
                            vehicleChart.updateSeries([{
                                data: sortedVehicles.map(([, count]) => count)
                            }], true);
                        }

                        if (cargoChart) {
                            cargoChart.updateOptions({
                                xaxis: {
                                    categories: sortedCargo.map(([name]) => name)
                                }
                            }, false, false);
                            cargoChart.updateSeries([{
                                data: sortedCargo.map(([, count]) => count)
                            }], true);
                        }
                    }

                    // Event listeners
                    if (dateInput) dateInput.addEventListener('change', updateFilteredCharts);
                    typeFilterRadios.forEach(radio => radio.addEventListener('change', updateFilteredCharts));
                    if (clearButton) {
                        clearButton.addEventListener('click', () => {
                            dateInput.value = '';
                            document.getElementById('type_all').checked = true;
                            updateFilteredCharts();
                        });
                    }

                    updateFilteredCharts();
                }

                // Genel Grafikler
                if (chartData.monthly && document.querySelector("#monthly-chart-lojistik")) {
                    new ApexCharts(document.querySelector("#monthly-chart-lojistik"), {
                        ...commonAreaOptions,
                        series: [{
                            name: 'Sevkiyat Sayısı',
                            data: chartData.monthly.data || []
                        }],
                        title: {
                            ...commonAreaOptions.title,
                            text: chartData.monthly.title || 'Aylık Sevkiyat'
                        },
                        xaxis: {
                            categories: chartData.monthly.labels || []
                        }
                    }).render();
                }

                if (chartData.pie && document.querySelector("#pie-chart-lojistik")) {
                    new ApexCharts(document.querySelector("#pie-chart-lojistik"), {
                        ...commonPieOptions,
                        series: chartData.pie.data || [],
                        labels: chartData.pie.labels || [],
                        title: {
                            ...commonPieOptions.title,
                            text: chartData.pie.title || 'Araç Tipi Dağılımı'
                        }
                    }).render();
                }

                if (chartData.hourly && document.querySelector("#hourly-chart-lojistik")) {
                    new ApexCharts(document.querySelector("#hourly-chart-lojistik"), {
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
                            text: chartData.hourly.title || 'Saatlik Yoğunluk'
                        },
                        xaxis: {
                            categories: chartData.hourly.labels || [],
                            tickAmount: 12
                        }
                    }).render();
                }

                if (chartData.daily && document.querySelector("#daily-chart-lojistik")) {
                    new ApexCharts(document.querySelector("#daily-chart-lojistik"), {
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
                            text: chartData.daily.title || 'Haftalık Yoğunluk'
                        },
                        xaxis: {
                            categories: chartData.daily.labels || []
                        }
                    }).render();
                }

                if (chartData.yearly && document.querySelector("#yearly-chart-lojistik")) {
                    new ApexCharts(document.querySelector("#yearly-chart-lojistik"), {
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
                            text: chartData.yearly.title || 'Yıllık Dağılım'
                        },
                        xaxis: {
                            categories: chartData.yearly.labels || []
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                distributed: true
                            }
                        }
                    }).render();
                }

                console.log('Lojistik grafikleri tamamlandı');
            }
            // --- Üretim Grafikleri ---
            else if (departmentSlug === 'uretim') {
                console.log('Üretim grafikleri çiziliyor...');

                if (chartData.weekly_prod && document.querySelector("#weekly-prod-chart")) {
                    new ApexCharts(document.querySelector("#weekly-prod-chart"), {
                        ...commonAreaOptions,
                        series: [{
                            name: 'Plan Sayısı',
                            data: chartData.weekly_prod.data || []
                        }],
                        title: {
                            ...commonAreaOptions.title,
                            text: chartData.weekly_prod.title || 'Haftalık Planlar'
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
                }

                if (chartData.monthly_prod && document.querySelector("#monthly-prod-chart")) {
                    new ApexCharts(document.querySelector("#monthly-prod-chart"), {
                        ...commonAreaOptions,
                        series: [{
                            name: 'Plan Sayısı',
                            data: chartData.monthly_prod.data || []
                        }],
                        title: {
                            ...commonAreaOptions.title,
                            text: chartData.monthly_prod.title || 'Aylık Planlar'
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
                }

                console.log('Üretim grafikleri tamamlandı');
            }
            // --- Hizmet Grafikleri ---
            else if (departmentSlug === 'hizmet') {
                console.log('Hizmet grafikleri çiziliyor...');

                if (chartData.event_type_pie && document.querySelector("#event-type-pie-chart")) {
                    new ApexCharts(document.querySelector("#event-type-pie-chart"), {
                        ...commonPieOptions,
                        series: chartData.event_type_pie.data || [],
                        labels: chartData.event_type_pie.labels || [],
                        title: {
                            ...commonPieOptions.title,
                            text: chartData.event_type_pie.title || 'Etkinlik Tipleri'
                        }
                    }).render();
                }

                if (chartData.monthly_assign && document.querySelector("#monthly-assign-chart")) {
                    new ApexCharts(document.querySelector("#monthly-assign-chart"), {
                        ...commonAreaOptions,
                        series: [{
                            name: 'Atama Sayısı',
                            data: chartData.monthly_assign.data || []
                        }],
                        title: {
                            ...commonAreaOptions.title,
                            text: chartData.monthly_assign.title || 'Aylık Atamalar'
                        },
                        xaxis: {
                            categories: chartData.monthly_assign.labels || [],
                            labels: {
                                rotate: -45,
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        }
                    }).render();
                }

                console.log('Hizmet grafikleri tamamlandı');
            }

        }); // DOMContentLoaded sonu
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\aslihan.aydin\Desktop\tedarik-yonetimi\tedarik-yonetimi\resources\views/statistics/index.blade.php ENDPATH**/ ?>