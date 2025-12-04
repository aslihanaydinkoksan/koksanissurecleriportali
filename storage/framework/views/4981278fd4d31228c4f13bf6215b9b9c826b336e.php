

<?php $__env->startPush('styles'); ?>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            --glass-white: rgba(255, 255, 255, 0.95);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 15px 35px rgba(102, 126, 234, 0.15);
        }

        body {
            background-color: #f3f4f6;
        }

        /* Hero Alanı - Modernize Edildi */
        .event-hero {
            background: var(--primary-gradient);
            border-radius: 1.5rem;
            padding: 3rem 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(118, 75, 162, 0.25);
            margin-bottom: -3rem;
            /* Kartların içine girmesi için */
            z-index: 1;
        }

        /* Arka plan süslemeleri */
        .event-hero::before,
        .event-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .event-hero::before {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -50px;
        }

        .event-hero::after {
            width: 200px;
            height: 200px;
            bottom: -50px;
            left: 10%;
        }

        .hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Glass Buttons */
        .btn-glass {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-glass:hover {
            background: white;
            color: #764BA2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Ana İçerik Kartları */
        .content-container {
            position: relative;
            z-index: 2;
            padding-top: 0;
        }

        .custom-card {
            background: var(--glass-white);
            border-radius: 1.25rem;
            border: none;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .card-header-custom {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .detail-item {
            padding: 1rem;
            border-radius: 12px;
            background: #f8f9fc;
            height: 100%;
            transition: background 0.3s;
        }

        .detail-item:hover {
            background: #f1f4f9;
        }

        .detail-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #a0aec0;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .detail-value {
            font-size: 1.05rem;
            color: #2d3748;
            font-weight: 600;
            word-break: break-word;
        }

        /* Status Icon Styling */
        .status-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            background: #f7fafc;
            position: relative;
        }

        .status-icon-wrapper i {
            font-size: 2.5rem;
        }

        /* Booking & Ticket Styles */
        .booking-section {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-top: 2rem;
        }

        .ticket-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #edf2f7;
            border-left: 5px solid #cbd5e0;
            /* Default color */
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .ticket-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }

        /* Ticket Type Colors */
        .ticket-flight {
            border-left-color: #4299e1;
        }

        /* Blue */
        .ticket-flight .icon-box {
            background-color: #ebf8ff;
            color: #4299e1;
        }

        .ticket-hotel {
            border-left-color: #48bb78;
        }

        /* Green */
        .ticket-hotel .icon-box {
            background-color: #f0fff4;
            color: #48bb78;
        }

        .ticket-bus {
            border-left-color: #ed8936;
        }

        /* Orange */
        .ticket-bus .icon-box {
            background-color: #fffaf0;
            color: #ed8936;
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .btn-gradient-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-gradient-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Form Wrappers */
        .form-wrapper {
            background: #f8fafc;
            border: 1px dashed #cbd5e0;
            border-radius: 1rem;
            padding: 1.5rem;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                        <i class="fa-solid fa-circle-check fa-lg me-3"></i>
                        <div><?php echo e(session('success')); ?></div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                
                <div class="event-hero">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="hero-badge">
                                    <i class="fa-solid fa-tag me-1"></i>
                                    <?php echo e($eventTypes[$event->event_type] ?? ucfirst($event->event_type)); ?>

                                </span>
                                <?php if($event->visit_status == 'gerceklesti'): ?>
                                    <span
                                        class="badge bg-success bg-opacity-25 border border-success text-white rounded-pill">Tamamlandı</span>
                                <?php elseif($event->visit_status == 'iptal'): ?>
                                    <span
                                        class="badge bg-danger bg-opacity-25 border border-danger text-white rounded-pill">İptal</span>
                                <?php endif; ?>
                            </div>
                            <h1 class="hero-title mb-2"><?php echo e($event->title); ?></h1>
                            <p class="mb-0 opacity-75 fs-5">
                                <i class="fa-regular fa-calendar me-2"></i>
                                <?php echo e(\Carbon\Carbon::parse($event->start_datetime)->format('d F Y, H:i')); ?>

                                <i class="fa-solid fa-arrow-right mx-2 text-white-50"></i>
                                <?php echo e(\Carbon\Carbon::parse($event->end_datetime)->format('d F Y, H:i')); ?>

                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <div class="d-flex flex-column flex-sm-row justify-content-lg-end gap-2">
                                <a href="<?php echo e(route('service.events.index')); ?>" class="btn-glass">
                                    <i class="fa-solid fa-arrow-left"></i> Listeye Dön
                                </a>
                                <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                    <a href="<?php echo e(route('service.events.edit', $event)); ?>" class="btn-glass">
                                        <i class="fa-solid fa-pen-to-square"></i> Düzenle
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="content-container mt-5 pt-3">
                    <div class="row g-4 mb-5">

                        
                        <div class="col-lg-8">
                            <div class="custom-card p-2">
                                <div class="card-header-custom">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                                        <i class="fa-solid fa-layer-group fa-lg"></i>
                                    </div>
                                    <h5 class="card-header-title">Etkinlik Detayları</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fa-solid fa-location-dot me-1"></i>
                                                    Konum</div>
                                                <div class="detail-value"><?php echo e($event->location ?? '-'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fa-solid fa-user me-1"></i> Oluşturan
                                                </div>
                                                <div class="detail-value"><?php echo e($event->user->name ?? 'Bilinmiyor'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fa-solid fa-align-left me-1"></i>
                                                    Açıklama</div>
                                                <div class="detail-value text-muted fw-normal">
                                                    <?php echo e($event->description ?? 'Açıklama girilmemiş.'); ?>

                                                </div>
                                            </div>
                                        </div>

                                        
                                        <?php if($event->customerVisit): ?>
                                            <div class="col-12 mt-4">
                                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                                    <i class="fa-solid fa-briefcase me-2"></i>Müşteri İlişkisi
                                                </h6>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-item border border-primary border-opacity-10 bg-white">
                                                    <div class="detail-label text-primary">Müşteri</div>
                                                    <div class="detail-value">
                                                        <a href="<?php echo e(route('customers.show', $event->customerVisit->customer_id)); ?>"
                                                            class="text-decoration-none fw-bold text-primary stretched-link">
                                                            <?php echo e($event->customerVisit->customer->name ?? '-'); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-item bg-white border">
                                                    <div class="detail-label">Ziyaret Amacı</div>
                                                    <div class="detail-value">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $event->customerVisit->visit_purpose))); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-4">
                            <div class="custom-card h-100">
                                <div class="card-header-custom">
                                    <div class="bg-info bg-opacity-10 p-2 rounded-circle text-info">
                                        <i class="fa-solid fa-chart-pie fa-lg"></i>
                                    </div>
                                    <h5 class="card-header-title">Durum Analizi</h5>
                                </div>
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">

                                    <?php switch($event->visit_status):
                                        case ('planlandi'): ?>
                                            <div class="status-icon-wrapper text-info bg-info bg-opacity-10">
                                                <i class="fa-solid fa-clock"></i>
                                            </div>
                                            <h4 class="fw-bold text-info mb-1">Planlandı</h4>
                                            <p class="text-muted small">Etkinlik zamanı bekleniyor.</p>
                                        <?php break; ?>

                                        <?php case ('gerceklesti'): ?>
                                            <div class="status-icon-wrapper text-success bg-success bg-opacity-10">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <h4 class="fw-bold text-success mb-1">Gerçekleşti</h4>
                                            <p class="text-muted small">Etkinlik başarıyla tamamlandı.</p>
                                        <?php break; ?>

                                        <?php case ('iptal'): ?>
                                            <div class="status-icon-wrapper text-danger bg-danger bg-opacity-10">
                                                <i class="fa-solid fa-xmark"></i>
                                            </div>
                                            <h4 class="fw-bold text-danger mb-1">İptal Edildi</h4>
                                            <p class="text-muted small">Etkinlik iptal edildi.</p>
                                        <?php break; ?>

                                        <?php default: ?>
                                            <div class="status-icon-wrapper text-secondary">
                                                <i class="fa-solid fa-question"></i>
                                            </div>
                                            <h4 class="fw-bold text-secondary mb-1"><?php echo e(ucfirst($event->visit_status)); ?></h4>
                                    <?php endswitch; ?>

                                    <?php if($event->cancellation_reason): ?>
                                        <div
                                            class="alert alert-danger bg-danger bg-opacity-10 border-0 mt-3 w-100 text-start">
                                            <div class="d-flex gap-2">
                                                <i class="fa-solid fa-circle-exclamation mt-1"></i>
                                                <div>
                                                    <small class="fw-bold d-block">İptal/Erteleme Nedeni:</small>
                                                    <small class="text-dark"><?php echo e($event->cancellation_reason); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($event->event_type === 'fuar'): ?>

                        <div class="booking-section mt-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h4 class="mb-0 fw-bold text-dark">
                                    <span class="text-primary me-2"><i class="fa-solid fa-plane-departure"></i></span>
                                    Seyahat & Konaklama Planlaması
                                </h4>
                                <span class="badge bg-white border text-dark px-3 py-2 rounded-pill shadow-sm">
                                    <i class="fa-solid fa-layer-group me-1 text-muted"></i>
                                    Toplam: <b><?php echo e($event->bookings->count()); ?></b> Rezervasyon
                                </span>
                            </div>

                            
                            <div class="card border-0 shadow-sm mb-5">
                                <div class="card-header bg-white border-bottom py-3">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="fa-solid fa-circle-plus me-2"></i>Yeni Rezervasyon Ekle
                                    </h6>
                                </div>
                                <div class="card-body bg-light bg-opacity-25">
                                    <form action="<?php echo e(route('service.events.bookings.store', $event->id)); ?>"
                                        method="POST" enctype="multipart/form-data" autocomplete="off">
                                        <?php echo csrf_field(); ?>

                                        <div class="row g-3 align-items-end">
                                            
                                            <div class="col-md-2">
                                                <label class="form-label small fw-bold text-muted">Tip (*)</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="flight">Uçak</option>
                                                    <option value="hotel">Otel</option>
                                                    <option value="bus">Otobüs/Transfer</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label small fw-bold text-muted">Sağlayıcı (*)</label>
                                                <input type="text" name="provider_name" class="form-control"
                                                    placeholder="Örn: THY, Hilton" required>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label small fw-bold text-muted">Rezervasyon Kodu</label>
                                                <input type="text" name="confirmation_code" class="form-control"
                                                    placeholder="PNR / Kod">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label small fw-bold text-muted">Başlangıç Tarihi
                                                    (*)</label>
                                                <input type="datetime-local" name="start_datetime" class="form-control"
                                                    required>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label small fw-bold text-muted">Tutar</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="cost"
                                                        class="form-control" placeholder="0.00">
                                                    <span class="input-group-text bg-white">₺</span>
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-3">
                                                <label class="form-label small fw-bold text-muted">Bitiş Tarihi</label>
                                                <input type="datetime-local" name="end_datetime" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label small fw-bold text-muted">Dosya
                                                    (Bilet/Voucher)</label>
                                                <input type="file" name="files[]" class="form-control" multiple>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted">Notlar</label>
                                                <input type="text" name="notes" class="form-control"
                                                    placeholder="Örn: Bagaj hakkı, oda tipi vb.">
                                            </div>

                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary w-100 fw-bold">
                                                    <i class="fa-solid fa-save me-1"></i> Kaydet
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            
                            <?php if($event->bookings->isEmpty()): ?>
                                <div class="text-center py-5 border rounded-3 bg-white border-dashed">
                                    <i class="fa-solid fa-ticket fa-3x text-muted opacity-25 mb-3"></i>
                                    <h6 class="text-muted">Henüz kayıtlı bir rezervasyon yok.</h6>
                                    <p class="text-muted small mb-0">Yukarıdaki formu kullanarak ilk kaydı
                                        oluşturabilirsiniz.</p>
                                </div>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php $__currentLoopData = $event->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $typeClass = match ($booking->type) {
                                                'flight' => 'border-primary',
                                                'hotel' => 'border-success',
                                                'bus' => 'border-warning',
                                                default => 'border-secondary',
                                            };

                                            $iconClass = match ($booking->type) {
                                                'flight' => 'fa-plane text-primary bg-primary bg-opacity-10',
                                                'hotel' => 'fa-hotel text-success bg-success bg-opacity-10',
                                                'bus' => 'fa-bus text-warning bg-warning bg-opacity-10',
                                                default => 'fa-ticket text-secondary bg-secondary bg-opacity-10',
                                            };
                                            $typeLabel = match ($booking->type) {
                                                'flight' => 'Uçak Bileti',
                                                'hotel' => 'Otel Konaklama',
                                                'bus' => 'Otobüs / Transfer',
                                                default => 'Diğer Rezervasyon',
                                            };
                                        ?>

                                        <div class="col-md-6 col-xl-4">
                                            <div
                                                class="card h-100 border-0 shadow-sm <?php echo e($typeClass); ?> border-start border-start-5">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width: 45px; height: 45px; min-width: 45px;">
                                                                <i
                                                                    class="fa-solid <?php echo e($iconClass); ?> p-2 rounded-circle w-100 h-100 d-flex justify-content-center align-items-center"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold mb-0 text-dark">
                                                                    <?php echo e($booking->provider_name); ?></h6>
                                                                <small class="text-muted"><?php echo e($typeLabel); ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-light rounded-circle"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fa-solid fa-ellipsis"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                                <?php if(Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager')): ?>
                                                                    <li><a class="dropdown-item"
                                                                            href="<?php echo e(route('bookings.edit', $booking)); ?>">Düzenle</a>
                                                                    </li>
                                                                    <li>
                                                                        <form
                                                                            action="<?php echo e(route('bookings.destroy', $booking)); ?>"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Silmek istediğine emin misin?')">
                                                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger">Sil</button>
                                                                        </form>
                                                                    </li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="bg-light rounded p-2 mb-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <small class="text-muted">Tarih:</small>
                                                            <small
                                                                class="fw-bold"><?php echo e(\Carbon\Carbon::parse($booking->start_datetime)->format('d.m.Y H:i')); ?></small>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <small class="text-muted">Kod:</small>
                                                            <small
                                                                class="fw-bold"><?php echo e($booking->confirmation_code ?? '-'); ?></small>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <?php if($booking->notes): ?>
                                                                <span class="badge bg-light text-dark border"
                                                                    data-bs-toggle="tooltip"
                                                                    title="<?php echo e($booking->notes); ?>">
                                                                    <i class="fa-solid fa-note-sticky me-1"></i> Not Var
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h5 class="text-success mb-0 fw-bold">
                                                            <?php echo e(number_format($booking->cost, 2)); ?> ₺</h5>
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
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/events/show.blade.php ENDPATH**/ ?>