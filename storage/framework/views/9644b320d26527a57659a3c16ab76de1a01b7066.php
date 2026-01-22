

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

        .booking-type {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .booking-type.flight {
            background: #e0e7ff;
            color: #4338ca;
        }

        .booking-type.bus {
            background: #fef2f2;
            color: #b91c1c;
        }

        .booking-type.hotel {
            background: #f0fdf4;
            color: #15803d;
        }

        .booking-type.car_rental {
            background: #fdf4ff;
            color: #a21caf;
        }

        .booking-type.train {
            background: #fffbeb;
            color: #b45309;
        }

        .booking-type.other {
            background: #f3f4f6;
            color: #374151;
        }

        .stat-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .visit-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667EEA;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: #f8f9fa;
            border-radius: 1rem;
            border: 2px dashed #dee2e6;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            backdrop-filter: blur(10px);
            transition: 0.3s;
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                
                <div class="travel-hero shadow-lg">
                    <div class="travel-hero-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="mb-2 fw-bold">✈️ <?php echo e($travel->name); ?></h2>
                                <p class="mb-0 fs-5 opacity-90">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    <?php echo e($travel->start_date->format('d/m/Y')); ?> - <?php echo e($travel->end_date->format('d/m/Y')); ?>

                                </p>
                            </div>
                            <?php if(Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')): ?>
                                <a href="<?php echo e(route('travels.edit', $travel)); ?>" class="btn btn-edit text-white shadow-sm">
                                    <i class="fa-solid fa-pen me-1"></i> Düzenle
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <div class="stat-box">
                                <h4><?php echo e($travel->bookings->count()); ?></h4>
                                <p class="mb-0">Rezervasyon</p>
                            </div>
                            <div class="stat-box">
                                <h4><?php echo e($travel->customerVisits->count()); ?></h4>
                                <p class="mb-0">Ziyaret</p>
                            </div>
                            <div class="stat-box">
                                <h4><?php echo e($travel->start_date->diffInDays($travel->end_date) + 1); ?></h4>
                                <p class="mb-0">Gün</p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        <i class="fa-solid fa-circle-check me-2"></i> <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                
                <div class="quick-add-card shadow-sm border-0">
                    <div class="section-title">
                        <i class="fa-solid fa-circle-plus text-primary"></i>
                        <h5 class="mb-0 fw-bold">Yeni Rezervasyon Ekle</h5>
                    </div>
                    <form action="<?php echo e(route('travels.bookings.store', $travel)); ?>" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('bookings._form', ['booking' => null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-gradient px-5 shadow">
                                <i class="fa-solid fa-plus me-2"></i>Rezervasyonu Kaydet
                            </button>
                        </div>
                    </form>
                </div>

                
                <div class="section-title">
                    <i class="fa-solid fa-ticket text-indigo"></i>
                    <h5 class="mb-0 fw-bold">Kayıtlı Rezervasyonlar <span
                            class="badge bg-primary ms-2"><?php echo e($travel->bookings->count()); ?></span></h5>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $travel->bookings->sortBy('start_datetime'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="booking-card">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <span class="booking-type <?php echo e($booking->type); ?>">
                                    <?php switch($booking->type):
                                        case ('flight'): ?>
                                            ✈️ Uçuş
                                        <?php break; ?>

                                        <?php case ('bus'): ?>
                                            🚌 Otobüs
                                        <?php break; ?>

                                        <?php case ('hotel'): ?>
                                            🏨 Otel
                                        <?php break; ?>

                                        <?php case ('car_rental'): ?>
                                            🚗 Araç
                                        <?php break; ?>

                                        <?php case ('train'): ?>
                                            🚆 Tren
                                        <?php break; ?>

                                        <?php default: ?>
                                            📋 Diğer
                                    <?php endswitch; ?>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1 fw-bold text-dark"><?php echo e($booking->provider_name); ?></h6>
                                <div class="text-muted small">
                                    <?php echo e($booking->origin ?? '?'); ?> <i class="fa-solid fa-arrow-right mx-1"></i>
                                    <?php echo e($booking->destination ?? '?'); ?>

                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="text-muted small">Tarih / Saat</div>
                                <div class="fw-bold">
                                    <?php echo e($booking->start_datetime ? $booking->start_datetime->format('d.m.Y H:i') : '-'); ?>

                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('bookings.edit', $booking)); ?>"
                                        class="btn btn-sm btn-outline-primary border-0"><i class="fa-solid fa-pen"></i></a>
                                    <form action="<?php echo e(route('bookings.destroy', $booking)); ?>" method="POST"
                                        class="d-inline" onsubmit="return confirm('Emin misiniz?');">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state mb-5">
                            <i class="fa-solid fa-inbox mb-3 d-block fs-1 text-muted"></i>
                            <h6 class="text-muted">Bu seyahate ait kayıtlı rezervasyon bulunmuyor.</h6>
                        </div>
                    <?php endif; ?>

                    
                    <div class="section-title mt-5">
                        <i class="fa-solid fa-handshake text-primary"></i>
                        <h5 class="mb-0 fw-bold">Bağlı Ziyaretler <span
                                class="badge bg-primary rounded-pill ms-2"><?php echo e($travel->customerVisits->count()); ?></span></h5>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $travel->customerVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="visit-card">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="text-muted small">Müşteri</div>
                                    <a href="<?php echo e(route('customers.show', $visit->customer)); ?>"
                                        class="fw-bold text-decoration-none" style="color: #667EEA;">
                                        <?php echo e($visit->customer->name ?? 'Silinmiş Müşteri'); ?>

                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small">Etkinlik / Tarih</div>
                                    <strong><?php echo e($visit->event->title ?? 'N/A'); ?></strong><br>
                                    <small><?php echo e($visit->event ? $visit->event->start_datetime->format('d.m.Y H:i') : '-'); ?></small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="badge bg-light text-dark border"><?php echo e($visit->visit_purpose); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state mb-5">
                            <i class="fa-solid fa-calendar-xmark mb-3 d-block fs-1 text-muted"></i>
                            <h6 class="text-muted">Henüz bu seyahate bağlı bir ziyaret kaydı yok.</h6>
                        </div>
                    <?php endif; ?>

                    
                    <?php echo $__env->make('partials._expense_section', ['model' => $travel], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </div> 
            </div> 
        </div> 
    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('expense-category-select')) {
                    const modalBody = e.target.closest('.modal-body');
                    const otherContainer = modalBody.querySelector('.other-desc-container');
                    const otherInput = otherContainer.querySelector('input');

                    if (e.target.value === 'Diğer') {
                        otherContainer.classList.remove('d-none');
                        otherInput.setAttribute('required', 'required');
                    } else {
                        otherContainer.classList.add('d-none');
                        otherInput.removeAttribute('required');
                    }
                }
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/travels/show.blade.php ENDPATH**/ ?>