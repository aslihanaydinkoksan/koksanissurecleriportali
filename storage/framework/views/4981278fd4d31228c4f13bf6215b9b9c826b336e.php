

<?php $__env->startPush('styles'); ?>
    <style>
        /* Hero Alanı (Travel Detay Sayfasıyla Uyumlu) */
        .event-hero {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.25);
        }

        .event-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .event-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .event-hero-content {
            position: relative;
            z-index: 1;
        }

        /* Bilgi Kartları */
        .info-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: transform 0.2s;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }

        .info-card .card-header {
            background: rgba(248, 249, 250, 0.6);
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #4a5568;
        }

        .info-label {
            font-size: 0.85rem;
            color: #718096;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .info-value {
            font-size: 1rem;
            color: #2d3748;
            font-weight: 600;
        }

        /* Badge Stilleri (Index ile uyumlu) */
        .badge-type {
            background: linear-gradient(135deg, #E8D5F2, #E3F2FD);
            color: #6B4C8A;
            border: 1px solid rgba(232, 213, 242, 0.5);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
        }

        /* Rezervasyon Bölümü */
        .booking-section {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #e9ecef;
            margin-top: 2rem;
        }

        .btn-edit {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: white;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                
                <div class="event-hero">
                    <div class="event-hero-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <h2 class="mb-0 fw-bold"><?php echo e($event->title); ?></h2>
                                    <span class="badge badge-type">
                                        <?php echo e($eventTypes[$event->event_type] ?? ucfirst($event->event_type)); ?>

                                    </span>
                                </div>
                                <p class="mb-0 opacity-75">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    <?php echo e(\Carbon\Carbon::parse($event->start_datetime)->format('d.m.Y H:i')); ?> -
                                    <?php echo e(\Carbon\Carbon::parse($event->end_datetime)->format('d.m.Y H:i')); ?>

                                </p>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('service.events.index')); ?>" class="btn btn-edit text-white">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Listeye Dön
                                </a>
                                <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                    <a href="<?php echo e(route('service.events.edit', $event)); ?>" class="btn btn-edit text-white">
                                        <i class="fa-solid fa-pen me-1"></i> Düzenle
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row g-4 mb-4">
                    
                    <div class="col-md-8">
                        <div class="info-card h-100">
                            <div class="card-header">
                                <i class="fa-solid fa-circle-info me-2 text-primary"></i> Etkinlik Detayları
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="info-label">Konum</div>
                                        <div class="info-value"><?php echo e($event->location ?? '-'); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label">Oluşturan</div>
                                        <div class="info-value"><?php echo e($event->user->name ?? 'Bilinmiyor'); ?></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="info-label">Açıklama</div>
                                        <div class="info-value text-muted">
                                            <?php echo e($event->description ?? 'Açıklama girilmemiş.'); ?>

                                        </div>
                                    </div>

                                    
                                    <?php if($event->customerVisit): ?>
                                        <div class="col-12">
                                            <hr class="my-2">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Müşteri</div>
                                            <div class="info-value">
                                                <a href="<?php echo e(route('customers.show', $event->customerVisit->customer_id)); ?>"
                                                    class="text-decoration-none">
                                                    <?php echo e($event->customerVisit->customer->name ?? '-'); ?>

                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Ziyaret Amacı</div>
                                            <div class="info-value">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $event->customerVisit->visit_purpose))); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-4">
                        <div class="info-card h-100">
                            <div class="card-header">
                                <i class="fa-solid fa-chart-pie me-2 text-primary"></i> Durum Bilgisi
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="mb-3">
                                    <?php switch($event->visit_status):
                                        case ('planlandi'): ?>
                                            <i class="fa-solid fa-clock fa-3x text-info mb-2"></i>
                                            <h5 class="text-info">Planlandı</h5>
                                        <?php break; ?>

                                        <?php case ('gerceklesti'): ?>
                                            <i class="fa-solid fa-circle-check fa-3x text-success mb-2"></i>
                                            <h5 class="text-success">Gerçekleşti</h5>
                                        <?php break; ?>

                                        <?php case ('iptal'): ?>
                                            <i class="fa-solid fa-circle-xmark fa-3x text-danger mb-2"></i>
                                            <h5 class="text-danger">İptal Edildi</h5>
                                        <?php break; ?>

                                        <?php default: ?>
                                            <i class="fa-solid fa-question-circle fa-3x text-muted mb-2"></i>
                                            <h5 class="text-muted"><?php echo e(ucfirst($event->visit_status)); ?></h5>
                                    <?php endswitch; ?>
                                </div>
                                <?php if($event->cancellation_reason): ?>
                                    <div class="alert alert-light border w-100 text-start mt-2">
                                        <small class="fw-bold text-danger">İptal/Erteleme Nedeni:</small><br>
                                        <small><?php echo e($event->cancellation_reason); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                
                <?php if($event->event_type === 'fuar'): ?> 

                    <div class="booking-section">
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                            <h4 class="mb-0 text-dark">
                                <i class="fa-solid fa-ticket me-2 text-primary"></i> Seyahat & Konaklama Planlaması
                            </h4>
                        </div>

                        
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-plus-circle me-1"></i> Yeni
                                    Rezervasyon Ekle</h6>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo e(route('service.events.bookings.store', $event->id)); ?>" method="POST"
                                    enctype="multipart/form-data" autocomplete="off">
                                    <?php echo csrf_field(); ?>
                                    
                                    <?php echo $__env->make('bookings._form', ['booking' => null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-gradient px-4">
                                            <i class="fa-solid fa-save me-1"></i> Rezervasyonu Kaydet
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                        <h5 class="mb-3 text-muted fw-bold ps-1">Kayıtlı Rezervasyonlar (<?php echo e($event->bookings->count()); ?>)
                        </h5>

                        <?php if($event->bookings->isEmpty()): ?>
                            <div class="text-center py-5 bg-white rounded border border-dashed">
                                <i class="fa-solid fa-plane-slash fa-3x text-muted mb-3 opacity-50"></i>
                                <p class="text-muted mb-0">Henüz bu etkinlik için bir seyahat/konaklama planı oluşturulmadı.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php $__currentLoopData = $event->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12 mb-3">
                                        
                                        
                                        

                                        <div class="card h-100 border shadow-sm">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    
                                                    <div class="col-md-1 text-center">
                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                                            <?php if($booking->type == 'flight'): ?>
                                                                <i class="fa-solid fa-plane text-primary fs-5"></i>
                                                            <?php elseif($booking->type == 'bus'): ?>
                                                                <i class="fa-solid fa-bus text-danger fs-5"></i>
                                                            <?php elseif($booking->type == 'hotel'): ?>
                                                                <i class="fa-solid fa-hotel text-success fs-5"></i>
                                                            <?php else: ?>
                                                                <i class="fa-solid fa-ticket text-secondary fs-5"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-3">
                                                        <small class="text-muted d-block">Sağlayıcı / Kod</small>
                                                        <span class="fw-bold"><?php echo e($booking->provider_name); ?></span>
                                                        <span
                                                            class="badge bg-light text-dark border ms-1"><?php echo e($booking->confirmation_code); ?></span>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <small class="text-muted d-block">Tarih</small>
                                                        <span class="fw-bold text-dark">
                                                            <?php echo e(\Carbon\Carbon::parse($booking->start_datetime)->format('d.m.Y H:i')); ?>

                                                        </span>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <small class="text-muted d-block">Tutar</small>
                                                        <span
                                                            class="text-success fw-bold"><?php echo e(number_format($booking->cost, 2)); ?>

                                                            ₺</span>
                                                    </div>

                                                    
                                                    <div class="col-md-3 text-end">
                                                        <?php if(Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager')): ?>
                                                            <a href="<?php echo e(route('bookings.edit', $booking)); ?>"
                                                                class="btn btn-sm btn-outline-primary me-1">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </a>
                                                            <form action="<?php echo e(route('bookings.destroy', $booking)); ?>"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Silmek istediğine emin misin?')">
                                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/events/show.blade.php ENDPATH**/ ?>