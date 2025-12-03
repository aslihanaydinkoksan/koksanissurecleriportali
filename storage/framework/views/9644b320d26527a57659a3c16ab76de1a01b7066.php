

<?php $__env->startSection('title', 'Seyahat Detayı'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .travel-hero {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .travel-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .travel-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .travel-hero-content {
            position: relative;
            z-index: 1;
        }

        .quick-add-card {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .booking-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.15);
            border-color: #667EEA;
        }

        .booking-type {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .booking-type.flight {
            background: linear-gradient(135deg, #667EEA20, #667EEA10);
            color: #667EEA;
        }

        .booking-type.bus {
            background: linear-gradient(135deg, #FF6B6B20, #FF6B6B10);
            /* Kırmızımsı/Turuncu ton */
            color: #FF6B6B;
        }

        .booking-type.hotel {
            background: linear-gradient(135deg, #4FD1C520, #4FD1C510);
            color: #2c9e91;
        }

        .booking-type.car {
            background: linear-gradient(135deg, #F093FB20, #F093FB10);
            color: #c965d6;
        }

        .booking-type.train {
            background: linear-gradient(135deg, #FBD38D20, #FBD38D10);
            color: #d4a043;
        }

        .file-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin: 0.25rem;
            font-size: 0.875rem;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .file-badge:hover {
            background: #667EEA;
            color: white;
            border-color: #667EEA;
            transform: translateX(2px);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-edit {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        .stats-row {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .stat-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-box h4 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-box p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }

        .visit-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667EEA;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .visit-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 1rem;
            border: 2px dashed #dee2e6;
        }

        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm-modern {
            padding: 0.4rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-modern {
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .btn-outline-modern:hover {
            background: #667EEA;
            border-color: #667EEA;
            color: white;
            transform: translateY(-1px);
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                
                <div class="travel-hero">
                    <div class="travel-hero-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="mb-2">✈️ <?php echo e($travel->name); ?></h2>
                                <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    <?php echo e(\Carbon\Carbon::parse($travel->start_date)->format('d/m/Y')); ?> -
                                    <?php echo e(\Carbon\Carbon::parse($travel->end_date)->format('d/m/Y')); ?>

                                </p>
                            </div>
                            <?php if(Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')): ?>
                                <a href="<?php echo e(route('travels.edit', $travel)); ?>" class="btn-edit">
                                    <i class="fa-solid fa-pen me-1"></i> Düzenle
                                </a>
                            <?php endif; ?>
                        </div>

                        
                        <div class="stats-row">
                            <div class="stat-box">
                                <h4><?php echo e($travel->bookings->count()); ?></h4>
                                <p>Rezervasyon</p>
                            </div>
                            <div class="stat-box">
                                <h4><?php echo e($travel->customerVisits->count()); ?></h4>
                                <p>Ziyaret</p>
                            </div>
                            <div class="stat-box">
                                <h4><?php echo e(\Carbon\Carbon::parse($travel->start_date)->diffInDays(\Carbon\Carbon::parse($travel->end_date))); ?>

                                </h4>
                                <p>Gün</p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong><i class="fa-solid fa-exclamation-triangle me-2"></i>Kayıt eklenirken bir hata
                            oluştu:</strong>
                        <ul class="mb-0 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                
                <div class="quick-add-card">
                    <div class="section-title">
                        <i class="fa-solid fa-circle-plus"></i>
                        <h5 class="mb-0">Yeni Rezervasyon Ekle</h5>
                    </div>
                    <form action="<?php echo e(route('travels.bookings.store', $travel)); ?>" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('bookings._form', ['booking' => null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <button type="submit" class="btn-gradient">
                            <i class="fa-solid fa-plus me-2"></i>Rezervasyonu Ekle
                        </button>
                    </form>
                </div>

                
                <div class="section-title">
                    <i class="fa-solid fa-ticket"></i>
                    <h5 class="mb-0">Kayıtlı Rezervasyonlar <span
                            class="badge bg-primary rounded-pill"><?php echo e($travel->bookings->count()); ?></span></h5>
                </div>

                <?php if($travel->bookings->isEmpty()): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <h5 class="text-muted">Henüz Rezervasyon Yok</h5>
                        <p class="text-muted mb-0">Bu seyahate ait rezervasyon bulunmuyor.</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $travel->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="booking-card">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <span class="booking-type <?php echo e($booking->type); ?>">
                                        <?php if($booking->type == 'flight'): ?>
                                            ✈️ Uçuş
                                        <?php elseif($booking->type == 'bus'): ?>
                                            🚌 Otobüs
                                        <?php elseif($booking->type == 'hotel'): ?>
                                            🏨 Otel
                                        <?php elseif($booking->type == 'car_rental'): ?>
                                            🚗 Araç
                                        <?php elseif($booking->type == 'train'): ?>
                                            🚆 Tren
                                        <?php else: ?>
                                            📋 Diğer
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <strong style="font-size: 1.1rem;"><?php echo e($booking->provider_name); ?></strong>
                                    <div class="text-muted small">Kod: <?php echo e($booking->confirmation_code); ?></div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-muted small">Tarih</div>
                                    <strong><?php echo e(\Carbon\Carbon::parse($booking->start_datetime)->format('d/m/Y H:i')); ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <?php if($booking->getMedia('attachments')->count() > 0): ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php $__currentLoopData = $booking->getMedia('attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e($media->getUrl()); ?>" target="_blank" class="file-badge">
                                                    <i class="fa-solid fa-paperclip"></i>
                                                    <?php echo e(Str::limit($media->file_name, 15)); ?>

                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">Dosya yok</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2 text-end">
                                    <?php if(Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager')): ?>
                                        <div class="action-buttons">
                                            <a href="<?php echo e(route('bookings.edit', $booking)); ?>"
                                                class="btn btn-sm-modern btn-outline-modern" title="Düzenle">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="<?php echo e(route('bookings.destroy', $booking)); ?>" method="POST"
                                                onsubmit="return confirm('Bu rezervasyonu silmek istediğinizden emin misiniz?');"
                                                style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="btn btn-sm-modern btn-outline-modern text-danger" title="Sil">
                                                    <i class="fa-solid fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                
                <div class="section-title mt-5">
                    <i class="fa-solid fa-handshake"></i>
                    <h5 class="mb-0">Bağlı Ziyaretler <span
                            class="badge bg-primary rounded-pill"><?php echo e($travel->customerVisits->count()); ?></span></h5>
                </div>

                <?php if($travel->customerVisits->isEmpty()): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-calendar-xmark"></i>
                        <h5 class="text-muted">Henüz Ziyaret Yok</h5>
                        <p class="text-muted mb-0">Bu seyahate bağlı ziyaret bulunmuyor.</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $travel->customerVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="visit-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="text-muted small">Müşteri</div>
                                    <a href="<?php echo e(route('customers.show', $visit->customer)); ?>"
                                        style="font-weight: 600; font-size: 1.05rem; color: #667EEA;">
                                        <?php echo e($visit->customer->name ?? 'Bilinmiyor'); ?>

                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Etkinlik</div>
                                    <strong><?php echo e($visit->event->title ?? 'N/A'); ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Tarih</div>
                                    <strong><?php echo e($visit->event ? \Carbon\Carbon::parse($visit->event->start_datetime)->format('d/m/Y H:i') : '-'); ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Amaç</div>
                                    <strong><?php echo e($visit->visit_purpose); ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/travels/show.blade.php ENDPATH**/ ?>