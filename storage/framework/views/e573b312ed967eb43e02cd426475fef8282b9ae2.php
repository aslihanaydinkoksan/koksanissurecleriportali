<div class="tab-pane fade" id="reports" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h4 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-chart-line me-2 text-primary"></i> Müşteri Performans
            Analizi</h4>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100"
                style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); border-radius: 1rem;">
                <div class="card-body text-center">
                    <h6 class="text-muted fw-bold mb-3">Fırsat Kazanma Oranı</h6>
                    <div id="oppConversionChart" style="min-height: 200px;"></div>
                    <div class="mt-3 small">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success me-1">Kazanıldı:
                            <?php echo e($chartData['opportunity_conversion']['details']['won']); ?></span>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger me-1">Kaybedildi:
                            <?php echo e($chartData['opportunity_conversion']['details']['lost']); ?></span>
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Bekleyen:
                            <?php echo e($chartData['opportunity_conversion']['details']['pending']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100"
                style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); border-radius: 1rem;">
                <div class="card-body text-center">
                    <h6 class="text-muted fw-bold mb-3">Müşterideki Pazar Payımız</h6>
                    <div id="productDistributionChart" class="d-flex justify-content-center" style="min-height: 220px;">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100"
                style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); border-radius: 1rem;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-3">6 Aylık Etkileşim Trendi</h6>
                    <div id="activityDensityChart" style="min-height: 220px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card border-0 shadow-sm h-100"
                style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); border-radius: 1rem;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-3">Kalite İndeksi (Gönderilen vs İade Edilen - Top 5 Ürün)</h6>
                    <div id="returnRadarChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartData = <?php echo json_encode($chartData, 15, 512) ?>;

            // 1. Fırsat Dönüşüm Oranı (Radial Bar)
            new ApexCharts(document.querySelector("#oppConversionChart"), {
                series: chartData.opportunity_conversion.series,
                chart: {
                    type: 'radialBar',
                    height: 250,
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5,
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                color: '#999',
                                opacity: 1,
                                blur: 2
                            }
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: -2,
                                fontSize: '22px',
                                fontWeight: 'bold',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91]
                    }
                },
                colors: ['#10b981'] // Kazanma yeşili
            }).render();

            // 2. Ürün / Rakip Dağılımı (Doughnut)
            new ApexCharts(document.querySelector("#productDistributionChart"), {
                series: chartData.product_distribution.series,
                labels: chartData.product_distribution.labels,
                chart: {
                    type: 'donut',
                    height: 260
                },
                colors: ['#667EEA', '#ef4444'], // KÖKSAN Mavisi, Rakip Kırmızısı
                plotOptions: {
                    donut: {
                        size: '65%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    markers: {
                        radius: 12
                    }
                },
                stroke: {
                    width: 0
                }
            }).render();

            // 3. Aktivite Yoğunluğu (Stacked Bar Chart)
            new ApexCharts(document.querySelector("#activityDensityChart"), {
                series: chartData.activity_density.series,
                chart: {
                    type: 'bar',
                    height: 250,
                    stacked: true,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#3b82f6', '#f59e0b'], // Mavi ve Turuncu
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 4,
                        columnWidth: '40%'
                    }
                },
                xaxis: {
                    categories: chartData.activity_density.categories,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    show: false
                }, // Şık durması için Y eksenini kapattık
                grid: {
                    show: false
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '10px'
                    }
                }
            }).render();

            // 4. İade vs Sevkiyat (Radar / Spider Chart)
            new ApexCharts(document.querySelector("#returnRadarChart"), {
                series: chartData.return_ratio.series,
                chart: {
                    type: 'radar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                labels: chartData.return_ratio.categories,
                colors: ['#10b981', '#dc2626'], // Gönderilen: Yeşil, İade: Kırmızı
                stroke: {
                    width: 2
                },
                fill: {
                    opacity: 0.2
                },
                markers: {
                    size: 4,
                    hover: {
                        size: 6
                    }
                },
                yaxis: {
                    show: false
                },
                legend: {
                    position: 'bottom'
                }
            }).render();
        });
    </script>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/reports.blade.php ENDPATH**/ ?>