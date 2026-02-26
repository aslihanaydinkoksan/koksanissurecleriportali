<?php $__env->startSection('title', 'Finansal Analiz '); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">💰 Finansal Analiz Dashboard</h2>

        
        <div class="row g-3 mb-4">
            <?php $__currentLoopData = $data['totals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <small class="text-muted fw-bold d-block mb-1">TOPLAM HARCAMA (<?php echo e($total->currency); ?>)</small>
                        <h3 class="fw-bold text-primary mb-0"><?php echo e(number_format($total->total, 2)); ?> <?php echo e($total->currency); ?>

                        </h3>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-pie me-2 text-warning"></i>Kategori Dağılımı</h5>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-bar me-2 text-success"></i>Modül Karşılaştırması
                    </h5>
                    <canvas id="moduleChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Kategori Grafiği (Filtrelenmiş Veri - Şimdilik TRY bazlı varsayalım veya döviz seçtirilebilir)
        const ctxCat = document.getElementById('categoryChart');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($data['byCategory']->pluck('category')); ?>,
                datasets: [{
                    data: <?php echo json_encode($data['byCategory']->pluck('total')); ?>,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                }]
            }
        });

        // Modül Grafiği (Travel vs Event)
        const ctxMod = document.getElementById('moduleChart');
        new Chart(ctxMod, {
            type: 'bar',
            data: {
                labels: ['Seyahatler', 'Etkinlikler/Fuarlar'],
                datasets: [{
                    label: 'Harcama Tutarı',
                    data: [
                        <?php echo e($data['byModule']->where('expensable_type', 'App\Models\Travel')->sum('total')); ?>,
                        <?php echo e($data['byModule']->where('expensable_type', 'App\Models\Event')->sum('total')); ?>

                    ],
                    backgroundColor: '#667EEA'
                }]
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/statistics/finance.blade.php ENDPATH**/ ?>