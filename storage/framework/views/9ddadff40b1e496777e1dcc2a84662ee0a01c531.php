

<?php $__env->startSection('title', 'Kanban Panosu Yönetimi'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">İş Süreçleri Panoları (Kanban)</h1>
            <a href="<?php echo e(route('kanban-boards.create')); ?>" class="btn btn-primary">
                <i class="fa fa-plus"></i> Yeni Pano Oluştur
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Mevcut Panolar</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Pano Adı</th>
                                <th>Fabrika / Birim</th>
                                <th>Modül</th>
                                <th>Sütun Sayısı</th>
                                <th class="text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $boards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $board): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="font-weight-bold"><?php echo e($board->name); ?></td>
                                    <td>
                                        <span class="badge bg-info text-white">
                                            <?php echo e($board->businessUnit->name ?? '-'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e(strtoupper($board->module_scope)); ?></td>
                                    <td><?php echo e($board->columns->count()); ?></td>
                                    <td class="text-right">
                                        
                                        <a href="<?php echo e(route('kanban.board', ['scope' => $board->module_scope])); ?>"
                                            class="btn btn-sm btn-info text-white me-1" target="_blank">
                                            <i class="fa fa-columns"></i> Panoya Git
                                        </a>

                                        <a href="<?php echo e(route('kanban-boards.edit', $board->id)); ?>"
                                            class="btn btn-sm btn-warning text-white">
                                            Düzenle
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/admin/kanban/index.blade.php ENDPATH**/ ?>