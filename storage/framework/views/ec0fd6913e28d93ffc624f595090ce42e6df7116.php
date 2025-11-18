

<?php $__env->startSection('title', 'Atanmış Görevlerim'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Atanmış Görevlerim</h1>

        <?php if($assignments->isEmpty()): ?>
            <div class="alert alert-info">Size atanmış aktif görev bulunmamaktadır.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Görev Başlığı</th>
                        <th>Araç</th>
                        <th>Başlangıç Zamanı</th>
                        <th>Durum</th>
                        <th>Aksiyon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($assignment->title); ?></td>
                            <td><?php echo e($assignment->vehicle->name ?? 'Yok'); ?></td>
                            <td><?php echo e($assignment->start_time->format('d.m.Y H:i')); ?></td>
                            <td><?php echo e(ucfirst($assignment->status)); ?></td>
                            <td>
                                <a href="<?php echo e(route('service.assignments.show', $assignment)); ?>"
                                    class="btn btn-sm btn-info">Detay</a>
                                
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <?php echo e($assignments->links()); ?>

        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/assignments/my_assignments.blade.php ENDPATH**/ ?>