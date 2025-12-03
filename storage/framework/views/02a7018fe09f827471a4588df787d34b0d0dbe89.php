

<?php $__env->startSection('title', 'Tüm Rezervasyonlar'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .page-hero {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .booking-table-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            color: #495057;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .type-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 1rem;
        }

        .type-badge.flight {
            background: #E3F2FD;
            color: #1976D2;
        }

        .type-badge.hotel {
            background: #E0F2F1;
            color: #00897B;
        }

        .type-badge.car_rental {
            background: #F3E5F5;
            color: #8E24AA;
        }

        .type-badge.train {
            background: #FFF3E0;
            color: #F57C00;
        }

        .type-badge.bus {
            background: #FFEBEE;
            color: #D32F2F;
        }

        /* Yeni Otobüs Rengi */
        .type-badge.other {
            background: #ECEFF1;
            color: #455A64;
        }

        .btn-sm-modern {
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .btn-sm-modern:hover {
            background-color: #e9ecef;
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                
                <div class="page-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                        <div>
                            <h2 class="mb-1">🎫 Tüm Rezervasyonlar</h2>
                            <p class="mb-0 opacity-75">Sistemdeki kayıtlı tüm seyahat rezervasyonları</p>
                        </div>
                        <div class="text-end">
                            <h1 class="mb-0 display-6 fw-bold"><?php echo e($bookings->total()); ?></h1>
                            <span class="small opacity-75">Toplam Kayıt</span>
                        </div>
                    </div>
                </div>

                
                <div class="booking-table-card">
                    <?php if($bookings->isEmpty()): ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-ticket fa-3x mb-3 text-muted opacity-50"></i>
                            <h4>Henüz hiç rezervasyon yok</h4>
                            <p>Seyahat planlarına giderek yeni rezervasyonlar ekleyebilirsiniz.</p>
                            <a href="<?php echo e(route('travels.index')); ?>" class="btn btn-primary mt-2">Seyahat Planlarına Git</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">Tip</th>
                                        <th>Sağlayıcı / Kod</th>
                                        <th>Bağlı Seyahat & Kişi</th>
                                        <th>Tarih</th>
                                        <th>Tutar</th>
                                        <th class="text-end">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            
                                            <td class="text-center">
                                                <?php
                                                    $icon = match ($booking->type) {
                                                        'flight' => 'fa-plane',
                                                        'hotel' => 'fa-hotel',
                                                        'car_rental' => 'fa-car',
                                                        'train' => 'fa-train',
                                                        'bus' => 'fa-bus',
                                                        default => 'fa-clipboard-list',
                                                    };
                                                ?>
                                                <div class="type-badge <?php echo e($booking->type); ?>"
                                                    title="<?php echo e(ucfirst($booking->type)); ?>">
                                                    <i class="fa-solid <?php echo e($icon); ?>"></i>
                                                </div>
                                            </td>

                                            
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo e($booking->provider_name); ?></div>
                                                <?php if($booking->confirmation_code): ?>
                                                    <div class="small text-muted">
                                                        <i
                                                            class="fa-solid fa-barcode me-1"></i><?php echo e($booking->confirmation_code); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            
                                            <td>
                                                <?php if($booking->travel): ?>
                                                    <a href="<?php echo e(route('travels.show', $booking->travel)); ?>"
                                                        class="text-decoration-none fw-semibold" style="color: #667EEA;">
                                                        <?php echo e($booking->travel->name); ?>

                                                    </a>
                                                    <div class="small text-muted">
                                                        <i
                                                            class="fa-regular fa-user me-1"></i><?php echo e($booking->user->name ?? 'Bilinmiyor'); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">Silinmiş Seyahat</span>
                                                <?php endif; ?>
                                            </td>

                                            
                                            <td>
                                                <div class="fw-bold text-dark">
                                                    <?php echo e(\Carbon\Carbon::parse($booking->start_datetime)->format('d.m.Y')); ?>

                                                </div>
                                                <div class="small text-muted">
                                                    <?php echo e(\Carbon\Carbon::parse($booking->start_datetime)->format('H:i')); ?>

                                                    <?php if($booking->end_datetime): ?>
                                                        -
                                                        <?php echo e(\Carbon\Carbon::parse($booking->end_datetime)->format('H:i')); ?>

                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            
                                            <td>
                                                <?php if($booking->cost): ?>
                                                    <span class="badge bg-light text-dark border">
                                                        <?php echo e(number_format($booking->cost, 2)); ?> ₺
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted small">-</span>
                                                <?php endif; ?>
                                            </td>

                                            
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <?php if(Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager')): ?>
                                                        <a href="<?php echo e(route('bookings.edit', $booking)); ?>"
                                                            class="btn btn-sm-modern text-primary" title="Düzenle">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>

                                                        <form action="<?php echo e(route('bookings.destroy', $booking)); ?>"
                                                            method="POST"
                                                            onsubmit="return confirm('Silmek istediğine emin misin?')"
                                                            style="display:inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm-modern text-danger"
                                                                title="Sil">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                    
                                                    <?php if($booking->getMedia('attachments')->count() > 0): ?>
                                                        <div class="vr mx-1"></div>
                                                        <a href="<?php echo e($booking->getMedia('attachments')->first()->getUrl()); ?>"
                                                            target="_blank" class="btn btn-sm-modern text-secondary"
                                                            title="Dosyayı Görüntüle">
                                                            <i class="fa-solid fa-paperclip"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($bookings->links()); ?>

                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/bookings/index.blade.php ENDPATH**/ ?>