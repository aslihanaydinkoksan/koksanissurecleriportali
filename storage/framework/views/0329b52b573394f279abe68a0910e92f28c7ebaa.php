

<?php $__env->startSection('title', 'Sistem Aktivite Logları'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card shadow-sm">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">Sistem Aktivite Logları</h4>
                        <small class="text-muted">Projedeki aktiviteleri görüntüleyin.</small>
                    </div>
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">Kim (Causer)</th>
                                        <th style="width: 35%;">Ne Yaptı (Description)</th>
                                        <th style="width: 25%;">Neyi Etkiledi (Subject)</th>
                                        <th style="width: 20%;">Zaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo e($activity->causer->name ?? 'Sistem/Silinmiş Kullanıcı'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php echo e($activity->description); ?>

                                            </td>
                                            <td>
                                                <?php if($activity->subject): ?>
                                                    <code class="text-dark">
                                                        <?php echo e(class_basename($activity->subject_type)); ?> (ID:
                                                        <?php echo e($activity->subject_id); ?>)
                                                    </code>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo e($activity->created_at->tz('Europe/Istanbul')->format('d/m/Y H:i:s')); ?>

                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mb-0 mt-2">Görüntülenecek hiç log bulunamadı.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <?php if($activities->hasPages()): ?>
                            <div class="mt-4">
                                <nav aria-label="Sayfa navigasyonu">
                                    <ul class="pagination pagination-rounded justify-content-center mb-0">
                                        
                                        <?php if($activities->onFirstPage()): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="bi bi-chevron-left"></i> Önceki
                                                </span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e($activities->previousPageUrl()); ?>">
                                                    <i class="bi bi-chevron-left"></i> Önceki
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $activities->getUrlRange(1, $activities->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($page == $activities->currentPage()): ?>
                                                <li class="page-item active">
                                                    <span class="page-link"><?php echo e($page); ?></span>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($activities->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e($activities->nextPageUrl()); ?>">
                                                    Sonraki <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    Sonraki <i class="bi bi-chevron-right"></i>
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>

                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        Toplam <?php echo e($activities->total()); ?> kayıttan
                                        <?php echo e($activities->firstItem()); ?>-<?php echo e($activities->lastItem()); ?> arası gösteriliyor
                                    </small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .pagination-rounded .page-link {
            border-radius: 0.375rem;
            margin: 0 3px;
            border: 1px solid #dee2e6;
            color: #4a5568;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease;
        }

        .pagination-rounded .page-link:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
            transform: translateY(-1px);
        }

        .pagination-rounded .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
            font-weight: 600;
        }

        .pagination-rounded .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: white;
            cursor: not-allowed;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\wwwroot\koksan_is_surecleri\resources\views/logs/index.blade.php ENDPATH**/ ?>