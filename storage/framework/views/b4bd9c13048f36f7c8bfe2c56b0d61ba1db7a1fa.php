


<style>
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
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

    /* Cam Kart Stili (Liste için) */
    .list-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    /* Kart Başlığı (Liste için) */
    .list-card .card-header {
        background: rgba(255, 255, 255, 0.5);
        color: #333;
        font-weight: bold;
        font-size: 1.25rem;
        border-bottom: none;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 1rem 1.5rem;
    }

    /* Padding artırıldı */

    /* Tablo Stilleri (Aynı kaldı) */
    .table {
        background-color: transparent;
        margin-bottom: 0;
        /* Kartın alt boşluğu için */
    }

    .table thead th {
        color: #333;
        border-bottom-width: 2px;
        border-color: rgba(0, 0, 0, 0.15);
    }

    /* Renk belirginleştirildi */
    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: rgba(255, 255, 255, 0.4);
        color: #212529;
    }

    .table-striped>tbody>tr:nth-of-type(even)>* {
        --bs-table-accent-bg: transparent;
        color: #212529;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-accent-bg: rgba(255, 255, 255, 0.8);
        color: #000;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    /* Dikey hizalama */

    /* === YENİ FİLTRE STİLLERİ === */
    /* Filtre Aç/Kapa Butonu */
    .btn-filter-toggle {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #333;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .btn-filter-toggle:hover {
        background-color: rgba(255, 255, 255, 0.95);
        border-color: rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-filter-toggle[aria-expanded="true"] {
        background-color: rgba(230, 235, 255, 0.9);
        /* Açıkken hafif mavi */
    }

    /* Filtre Kartı */
    .filter-card {
        background: rgba(255, 255, 255, 0.85);
        /* Biraz daha belirgin */
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        /* İç boşluk artırıldı */
    }

    .filter-card .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.3rem;
        /* Etiket alt boşluğu */
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 0.5rem;
        background-color: #fff;
        /* Tam opak */
    }

    /* Filtre içindeki row'un alt boşluğu */
    .filter-card .row {
        margin-bottom: -1rem;
        /* Bootstrap g-3'ün mb-3'ünü dengelemek için */
    }

    .filter-card .row>div {
        margin-bottom: 1rem;
        /* Sütunlar arasına dikey boşluk */
    }

    /* Filtre Butonları */
    .btn-apply-filter {
        /* Gradient butonu kullan */
        background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        padding: 0.5rem 1.25rem;
        /* Standart buton boyutu */
    }

    .btn-apply-filter:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-clear-filter {
        padding: 0.5rem 1.25rem;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                
                <div class="mb-4">
                    <div class="d-grid">
                        
                        <button class="btn btn-filter-toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-filter me-2"></i> Filtre Seçenekleri
                            
                            <i class="fas fa-chevron-down ms-2 small"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <div class="card filter-card"> 
                            <form method="GET" action="<?php echo e(route('products.list')); ?>">
                                
                                <div class="row">
                                    
                                    <div class="col-md-3">
                                        <label for="shipment_type" class="form-label">Sevkiyat Türü</label>
                                        <select class="form-select form-select-sm" id="shipment_type" name="shipment_type">
                                            
                                            <option value="all"
                                                <?php echo e(($filters['shipment_type'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tümü
                                            </option>
                                            <option value="import"
                                                <?php echo e(($filters['shipment_type'] ?? '') == 'import' ? 'selected' : ''); ?>>
                                                İthalat</option>
                                            <option value="export"
                                                <?php echo e(($filters['shipment_type'] ?? '') == 'export' ? 'selected' : ''); ?>>
                                                İhracat</option>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-3">
                                        <label for="vehicle_type" class="form-label">Araç Tipi</label>
                                        <select class="form-select form-select-sm" id="vehicle_type" name="vehicle_type">
                                            <option value="all"
                                                <?php echo e(($filters['vehicle_type'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tümü
                                            </option>
                                            <?php $__currentLoopData = $vehicleTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($type); ?>"
                                                    <?php echo e(($filters['vehicle_type'] ?? '') == $type ? 'selected' : ''); ?>>
                                                    <?php echo e(ucfirst($type)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <label for="cargo_content" class="form-label">Kargo İçeriği (Ara)</label>
                                        <input type="text" class="form-control form-control-sm" id="cargo_content"
                                            name="cargo_content" value="<?php echo e($filters['cargo_content'] ?? ''); ?>"
                                            placeholder="İçerik adı girin...">
                                    </div>

                                    
                                    <div class="col-md-3">
                                        <label for="date_from" class="form-label">Başlangıç Tarihi</label>
                                        <input type="date" class="form-control form-control-sm" id="date_from"
                                            name="date_from" value="<?php echo e($filters['date_from'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_to" class="form-label">Bitiş Tarihi</label>
                                        <input type="date" class="form-control form-control-sm" id="date_to"
                                            name="date_to" value="<?php echo e($filters['date_to'] ?? ''); ?>">
                                    </div>

                                    
                                    <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                                        <a href="<?php echo e(route('products.list')); ?>"
                                            class="btn btn-secondary btn-clear-filter btn-sm">
                                            <i class="fas fa-times me-1"></i> Temizle
                                        </a>
                                        <button type="submit" class="btn btn-apply-filter btn-sm">
                                            <i class="fas fa-check me-1"></i> Filtrele
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                


                
                <div class="card list-card">
                    <div class="card-header">Sevkiyat Listesi</div>

                    <div class="card-body p-0"> 
                        <?php if($shipments->isEmpty()): ?>
                            <div class="alert alert-warning m-3" role="alert"> 
                                <i class="fas fa-exclamation-triangle me-2"></i> Seçilen filtrelere uygun sevkiyat kaydı
                                bulunamadı.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0"> 
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-3">Sevkiyat Türü</th> 
                                            <th scope="col">Kargo İçeriği</th>
                                            <th scope="col">Araç Tipi</th>
                                            <th scope="col">Kalkış</th>
                                            <th scope="col">Varış</th>
                                            <th scope="col">Çıkış Tarihi</th>
                                            <th scope="col">Tahmini Varış</th>
                                            <th scope="col" class="text-end pe-3">İşlemler</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="ps-3">
                                                    <?php if($shipment->shipment_type == 'import'): ?>
                                                        <span class="badge bg-primary">İthalat</span>
                                                    <?php elseif($shipment->shipment_type == 'export'): ?>
                                                        <span class="badge bg-success">İhracat</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($shipment->kargo_icerigi); ?></td>
                                                <td><?php echo e(ucfirst($shipment->arac_tipi)); ?></td>
                                                <td><?php echo e($shipment->arac_tipi == 'gemi' ? $shipment->kalkis_limani : $shipment->kalkis_noktasi); ?>

                                                </td>
                                                <td><?php echo e($shipment->arac_tipi == 'gemi' ? $shipment->varis_limani : $shipment->varis_noktasi); ?>

                                                </td>
                                                <td><?php echo e($shipment->cikis_tarihi ? \Carbon\Carbon::parse($shipment->cikis_tarihi)->format('d.m.Y H:i') : '-'); ?>

                                                </td>
                                                <td><?php echo e($shipment->tahmini_varis_tarihi ? \Carbon\Carbon::parse($shipment->tahmini_varis_tarihi)->format('d.m.Y H:i') : '-'); ?>

                                                </td>
                                                <td class="text-end pe-3"> 
                                                    <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                                        <a href="<?php echo e(route('shipments.edit', $shipment)); ?>"
                                                            class="btn btn-sm btn-light" title="Düzenle"><i
                                                                class="fas fa-edit"></i></a>
                                                        
                                                        <form action="<?php echo e(route('shipments.destroy', $shipment)); ?>"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Bu sevkiyatı silmek istediğinizden emin misiniz?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-light"
                                                                title="Sil">
                                                                <i class="fas fa-trash"
                                                                    style="color: rgb(235, 76, 108)"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                                        
                                                        
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var filterCollapse = document.getElementById('filterCollapse');
            var filterButtonIcon = document.querySelector('.btn-filter-toggle .fa-chevron-down');

            if (filterCollapse && filterButtonIcon) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-down');
                    filterButtonIcon.classList.add('fa-chevron-up');
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-up');
                    filterButtonIcon.classList.add('fa-chevron-down');
                });

                // Sayfa yüklendiğinde filtreler zaten açıksa ikonu düzelt (örn. form hatası sonrası)
                if (filterCollapse.classList.contains('show')) {
                    filterButtonIcon.classList.remove('fa-chevron-down');
                    filterButtonIcon.classList.add('fa-chevron-up');
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/shipments/list.blade.php ENDPATH**/ ?>