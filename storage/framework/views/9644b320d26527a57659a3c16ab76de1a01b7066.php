

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

        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.15);
            border-color: #667EEA;
        }

        /* Booking Type Badges */
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

        .file-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.6rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin: 0.1rem;
            font-size: 0.75rem;
            border: 1px solid #e9ecef;
            color: #495057;
            text-decoration: none;
            transition: 0.2s;
        }

        .file-badge:hover {
            background: #667EEA;
            color: white;
            border-color: #667EEA;
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

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: white;
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

        .btn-edit:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
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
                                    <?php echo e(\Carbon\Carbon::parse($travel->start_date)->format('d/m/Y')); ?> -
                                    <?php echo e(\Carbon\Carbon::parse($travel->end_date)->format('d/m/Y')); ?>

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
                                <p>Rezervasyon</p>
                            </div>
                            <div class="stat-box">
                                <h4><?php echo e($travel->customerVisits->count()); ?></h4>
                                <p>Ziyaret</p>
                            </div>
                            <div class="stat-box">
                                <h4><?php echo e(\Carbon\Carbon::parse($travel->start_date)->diffInDays(\Carbon\Carbon::parse($travel->end_date)) + 1); ?>

                                </h4>
                                <p>Gün</p>
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
                                    <?php if(in_array($booking->type, ['flight', 'bus', 'train'])): ?>
                                        <i class="fa-solid fa-location-dot text-primary"></i> <?php echo e($booking->origin ?? '?'); ?>

                                        <i class="fa-solid fa-arrow-right mx-1 text-muted"></i>
                                        <i class="fa-solid fa-location-arrow text-success"></i>
                                        <?php echo e($booking->destination ?? '?'); ?>

                                    <?php else: ?>
                                        <i class="fa-solid fa-map-pin text-info"></i>
                                        <?php echo e($booking->location ?? 'Lokasyon Belirtilmedi'); ?>

                                    <?php endif; ?>
                                </div>
                                <?php if($booking->confirmation_code): ?>
                                    <div class="text-primary small mt-1 fw-500"><i class="fa-solid fa-hashtag me-1"></i>PNR:
                                        <?php echo e($booking->confirmation_code); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="text-muted small">Tarih / Saat</div>
                                <div class="fw-bold">
                                    <?php echo e($booking->start_datetime ? \Carbon\Carbon::parse($booking->start_datetime)->format('d.m.Y H:i') : '-'); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <?php if($booking->getMedia('attachments')->isNotEmpty()): ?>
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php $__currentLoopData = $booking->getMedia('attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e($media->getUrl()); ?>" target="_blank" class="file-badge"
                                                title="<?php echo e($media->file_name); ?>">
                                                <i class="fa-solid fa-paperclip"></i>
                                                <?php echo e(Str::limit($media->file_name, 10)); ?>

                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2 text-end">
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
                                <div class="col-md-3">
                                    <div class="text-muted small">Müşteri</div>
                                    <a href="<?php echo e(route('customers.show', $visit->customer)); ?>"
                                        class="fw-bold text-decoration-none" style="color: #667EEA;">
                                        <?php echo e($visit->customer->name ?? 'Silinmiş Müşteri'); ?>

                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Etkinlik</div>
                                    <strong><?php echo e($visit->event->title ?? 'N/A'); ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Tarih</div>
                                    <strong><?php echo e($visit->event ? \Carbon\Carbon::parse($visit->event->start_datetime)->format('d.m.Y H:i') : '-'); ?></strong>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="badge bg-light text-dark border"><?php echo e($visit->visit_purpose); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-calendar-xmark mb-3 d-block fs-1 text-muted"></i>
                            <h6 class="text-muted">Henüz bu seyahate bağlı bir ziyaret kaydı yok.</h6>
                        </div>
                    <?php endif; ?>

                    
                    <div class="section-title mt-5 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-invoice-dollar text-warning"></i>
                            <h5 class="mb-0 fw-bold">Harcamalar ve Masraflar</h5>
                        </div>
                        <button type="button" class="btn btn-warning btn-sm text-white px-3 shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#addExpenseModal">
                            <i class="fa-solid fa-plus me-1"></i> Masraf Ekle
                        </button>
                    </div>

                    <?php if($travel->expenses->isEmpty()): ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-receipt mb-3 d-block fs-1 text-muted"></i>
                            <h6 class="text-muted">Henüz masraf kaydı girilmedi.</h6>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle bg-white border rounded shadow-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Kategori</th>
                                        <th>Açıklama</th>
                                        <th>Tarih</th>
                                        <th class="text-end">Tutar</th>
                                        <th class="text-end pe-4">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $travel->expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4"><span
                                                    class="badge bg-light text-dark border"><?php echo e($expense->category); ?></span>
                                            </td>
                                            <td><small class="text-muted"><?php echo e($expense->description ?? '-'); ?></small></td>
                                            <td><?php echo e($expense->receipt_date ? $expense->receipt_date->format('d.m.Y') : '-'); ?>

                                            </td>
                                            <td class="text-end fw-bold"><?php echo e(number_format($expense->amount, 2)); ?>

                                                <?php echo e($expense->currency); ?></td>
                                            <td class="text-end pe-4">
                                                <form action="<?php echo e(route('expenses.destroy', $expense->id)); ?>" method="POST"
                                                    onsubmit="return confirm('Silinsin mi?');">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-link text-danger p-0"><i
                                                            class="fa-solid fa-trash-can"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="bg-light fw-bold border-top border-2">
                                        <td colspan="3" class="text-end">GENEL TOPLAM:</td>
                                        <td class="text-end">
                                            <?php $__currentLoopData = $travel->expenses->groupBy('currency'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curr => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="text-dark"><?php echo e(number_format($items->sum('amount'), 2)); ?>

                                                    <?php echo e($curr); ?></div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-receipt me-2"></i>Yeni Masraf Kaydı</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('expenses.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="travel_id" value="<?php echo e($travel->id); ?>">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="category" id="expenseCategory" class="form-select" required>
                                    <option value="" selected disabled>Seçiniz...</option>
                                    <option value="Ulaşım">✈️ Ulaşım</option>
                                    <option value="Konaklama">🏨 Konaklama</option>
                                    <option value="Yemek">🍽️ Yemek</option>
                                    <option value="Temsil">🤝 Temsil & Ağırlama</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                            <div class="mb-3 d-none" id="otherDescriptionContainer">
                                <label class="form-label fw-bold text-danger">Detay Belirtin *</label>
                                <input type="text" name="description" id="expenseDescription" class="form-control"
                                    placeholder="Masraf türü nedir?">
                            </div>
                            <div class="row g-3">
                                <div class="col-8">
                                    <label class="form-label fw-bold">Tutar</label>
                                    <input type="number" step="0.01" name="amount" class="form-control"
                                        placeholder="0.00" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-bold">Döviz</label>
                                    <select name="currency" class="form-select fw-bold">
                                        <option value="TRY">TRY ₺</option>
                                        <option value="USD">USD $</option>
                                        <option value="EUR">EUR €</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label fw-bold">Fiş/Fatura Tarihi</label>
                                <input type="date" name="receipt_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Masraf Modalı: "Diğer" seçimi kontrolü
                const categorySelect = document.getElementById('expenseCategory');
                const otherContainer = document.getElementById('otherDescriptionContainer');
                const otherInput = document.getElementById('expenseDescription');

                if (categorySelect) {
                    categorySelect.addEventListener('change', function() {
                        if (this.value === 'Diğer') {
                            otherContainer.classList.remove('d-none');
                            otherInput.setAttribute('required', 'required');
                        } else {
                            otherContainer.classList.add('d-none');
                            otherInput.removeAttribute('required');
                        }
                    });
                }
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/travels/show.blade.php ENDPATH**/ ?>