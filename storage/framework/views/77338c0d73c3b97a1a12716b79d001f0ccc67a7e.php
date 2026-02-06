

<?php $__env->startSection('title', $customer->name); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        @keyframes gradientWave {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Modern kart tasarımı */
        .customer-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            backdrop-filter: blur(10px);
        }

        /* Sekme tasarımı */
        .nav-tabs {
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
            gap: 0.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 0.75rem;
            color: #6c757d;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            background-color: transparent;
        }

        .nav-tabs .nav-link:hover {
            background-color: rgba(102, 126, 234, 0.08);
            color: #667EEA;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-tabs .nav-link i {
            margin-right: 0.5rem;
        }

        /* Sekme içerik alanı */
        .tab-content {
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        /* Detay listesi */
        .detail-list dt {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .detail-list dd {
            color: #212529;
            font-weight: 400;
            margin-bottom: 1rem;
            padding-left: 1rem;
        }

        /* Form stilleri */
        .quick-add-form {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px dashed rgba(102, 126, 234, 0.2);
        }

        .quick-add-form .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .quick-add-form .form-control,
        .quick-add-form .form-select {
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .quick-add-form .form-control:focus,
        .quick-add-form .form-select:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        /* Input Group Override */
        .quick-add-form .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .quick-add-form .input-group .form-select {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background-color: rgba(245, 247, 250, 0.9);
        }

        /* Tablo stilleri */
        .table {
            background-color: transparent;
        }

        .table thead th {
            background-color: rgba(102, 126, 234, 0.08);
            border-bottom: 2px solid #667EEA;
            font-weight: 600;
            color: #444;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.08);
        }

        /* Alert stilleri */
        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .alert-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .alert-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #495057;
        }

        /* Dosya listesi */
        .file-list-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .file-list-item:hover {
            background-color: rgba(102, 126, 234, 0.05);
            border-color: #667EEA;
        }

        /* Animasyonlu buton */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Başlık stilleri */
        h5 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
        }

        /* Filtreleme alanı stilleri */
        .filter-input {
            border-radius: 0.5rem;
            border: 1px solid rgba(102, 126, 234, 0.3);
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }

        .filter-input:focus {
            outline: none;
            border-color: #667EEA;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card">
                    <div class="card-header bg-transparent border-0 px-4 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <h2 class="mb-2 fw-bold" style="color: #2d3748;">
                                    <i class="fa-solid fa-building me-2" style="color: #667EEA;"></i>
                                    <?php echo e($customer->name); ?>

                                </h2>
                                <p class="text-muted mb-0">Müşteri Detayları ve İşlemler</p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?php echo e(route('customers.edit', $customer)); ?>"
                                    class="btn btn-animated-gradient rounded-pill px-4">
                                    <i class="fa-solid fa-pen me-2"></i>
                                    Bilgileri Düzenle
                                </a>
                                <form action="<?php echo e(route('customers.destroy', $customer->id)); ?>" method="POST"
                                    class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"
                                        onclick="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz? Bu işlem geri alınabilir (Arşiv).');">
                                        <i class="fa-solid fa-trash-alt me-2"></i>
                                        Kaydı Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="fa-solid fa-circle-check me-3 fs-4"></i>
                                <div><?php echo e(session('success')); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="fa-solid fa-circle-xmark me-3 fs-4"></i>
                                <div><?php echo e(session('error')); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>Kayıt eklenirken bir hata
                                    oluştu:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <ul class="nav nav-tabs mt-4" id="customerTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="details-tab" data-bs-toggle="tab"
                                    data-bs-target="#details" type="button" role="tab">
                                    <i class="fa-solid fa-user"></i>Detaylar
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="machines-tab" data-bs-toggle="tab" data-bs-target="#machines"
                                    type="button" role="tab">
                                    <i class="fa-solid fa-industry"></i>Makineler (<?php echo e($customer->machines->count()); ?>)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tests-tab" data-bs-toggle="tab" data-bs-target="#tests"
                                    type="button" role="tab">
                                    <i class="fa-solid fa-vial"></i>Test Sonuçları (<?php echo e($customer->testResults->count()); ?>)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="complaints-tab" data-bs-toggle="tab"
                                    data-bs-target="#complaints" type="button" role="tab">
                                    <i class="fa-solid fa-exclamation-triangle"></i>Şikayetler
                                    (<?php echo e($customer->complaints->count()); ?>)
                                </button>
                            </li>
                            
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns"
                                    type="button" role="tab">
                                    <i class="fa-solid fa-rotate-left"></i> İadeler (<?php echo e($customer->returns->count()); ?>)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="visits-tab" data-bs-toggle="tab" data-bs-target="#visits"
                                    type="button" role="tab">
                                    <i class="fa-solid fa-calendar-days"></i>Ziyaretler (<?php echo e($customer->visits->count()); ?>)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="activities-tab" data-bs-toggle="tab"
                                    data-bs-target="#activities" type="button" role="tab">
                                    <i class="fas fa-history me-1"></i> İletişim Geçmişi
                                    (<?php echo e($customer->activities->count()); ?>)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="logistics-tab" data-bs-toggle="tab"
                                    data-bs-target="#logistics" type="button" role="tab">
                                    <i class="fas fa-truck me-1"></i> Lojistik Hareketleri
                                    (<?php echo e($customer->vehicleAssignments->count()); ?>)
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="customerTabContent">
                            <div class="tab-pane fade show active" id="details" role="tabpanel">
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <h5><i class="fa-solid fa-building me-2"></i>Firma Bilgileri</h5>
                                        <dl class="row detail-list mt-3">
                                            <dt class="col-sm-4">Adres</dt>
                                            <dd class="col-sm-8"><?php echo e($customer->address); ?></dd>
                                            <dt class="col-sm-4">Genel Tel</dt>
                                            <dd class="col-sm-8"><?php echo e($customer->phone); ?></dd>
                                            <dt class="col-sm-4">Genel Email</dt>
                                            <dd class="col-sm-8"><?php echo e($customer->email); ?></dd>
                                        </dl>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h5><i class="fa-solid fa-users me-2"></i>İletişim Kişileri</h5>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Ad Soyad</th>
                                                        <th>Ünvan</th>
                                                        <th>İletişim</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__empty_1 = true; $__currentLoopData = $customer->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>
                                                            <td class="align-middle">
                                                                <span
                                                                    class="fw-bold text-dark"><?php echo e($contact->name); ?></span>
                                                                <?php if($contact->is_primary): ?>
                                                                    <i class="fa-solid fa-star text-warning small ms-1"
                                                                        title="Ana İletişim"></i>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="align-middle text-muted small">
                                                                <?php echo e($contact->title ?? '-'); ?>

                                                            </td>
                                                            <td class="small">
                                                                <?php if($contact->email): ?>
                                                                    <div class="mb-1"><i
                                                                            class="fa-solid fa-envelope text-primary me-1"></i>
                                                                        <?php echo e($contact->email); ?></div>
                                                                <?php endif; ?>
                                                                <?php if($contact->phone): ?>
                                                                    <div><i
                                                                            class="fa-solid fa-phone text-success me-1"></i>
                                                                        <?php echo e($contact->phone); ?></div>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">Kayıtlı kişi
                                                                yok.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="machines" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı Makine Ekle</h5>
                                <form action="<?php echo e(route('customers.machines.store', $customer)); ?>" method="POST"
                                    autocomplete="off" class="quick-add-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="model" class="form-label">Makine Adı (*)</label>
                                            <input type="text" name="model" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="serial_number" class="form-label">Seri No</label>
                                            <input type="text" name="serial_number" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="installation_date" class="form-label">Kurulum Tarihi</label>
                                            <input type="date" name="installation_date" class="form-control">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">
                                        <i class="fa-solid fa-plus me-2"></i>Makineyi Ekle
                                    </button>
                                </form>

                                <h5><i class="fa-solid fa-list me-2"></i>Kayıtlı Makineler</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Makine Adı</th>
                                                <th>Seri No</th>
                                                <th>Kurulum Tarihi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $customer->machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><strong><?php echo e($machine->model); ?></strong></td>
                                                    <td><?php echo e($machine->serial_number ?? '-'); ?></td>
                                                    <td><?php echo e($machine->installation_date ? \Carbon\Carbon::parse($machine->installation_date)->format('d/m/Y') : '-'); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        Bu müşteriye ait makine kaydı bulunamadı.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tests" role="tabpanel">
                                <h5><i class="fa-solid fa-upload me-2"></i>Hızlı Test Sonucu Yükle</h5>
                                <form action="<?php echo e(route('customers.test-results.store', $customer)); ?>" method="POST"
                                    autocomplete="off" class="quick-add-form" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="test_name" class="form-label">Test Adı (*)</label>
                                            <input type="text" name="test_name" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="test_date" class="form-label">Test Tarihi (*)</label>
                                            <input type="date" name="test_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="test_files" class="form-label">Dosya(lar)</label>
                                            <input type="file" name="test_files[]" class="form-control" multiple>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="summary" class="form-label">Özet</label>
                                        <textarea name="summary" class="form-control" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">
                                        <i class="fa-solid fa-plus me-2"></i>Test Sonucunu Ekle
                                    </button>
                                </form>

                                <h5><i class="fa-solid fa-list me-2"></i>Kayıtlı Test Sonuçları</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Test Adı</th>
                                                <th>Tarih</th>
                                                <th>Özet</th>
                                                <th>Dosyalar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $customer->testResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><strong><?php echo e($result->test_name); ?></strong></td>
                                                    <td><?php echo e(\Carbon\Carbon::parse($result->test_date)->format('d/m/Y')); ?>

                                                    </td>
                                                    <td><?php echo e($result->summary ?? '-'); ?></td>
                                                    <td>
                                                        <?php $__currentLoopData = $result->getMedia('test_reports'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="file-list-item">
                                                                <span>
                                                                    <i class="fa-solid fa-file-pdf me-2"></i>
                                                                    <?php echo e($media->file_name); ?>

                                                                </span>
                                                                <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                                    <i class="fa-solid fa-eye me-1"></i>Görüntüle
                                                                </a>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">
                                                        Bu müşteriye ait test sonucu bulunamadı.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="complaints" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı Şikayet Kaydı Ekle</h5>
                                <form action="<?php echo e(route('customers.complaints.store', $customer)); ?>" method="POST"
                                    autocomplete="off" class="quick-add-form" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="title" class="form-label">Şikayet Başlığı (*)</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="status" class="form-label">Durum (*)</label>
                                            <select name="status" class="form-select" required>
                                                <option value="open">Açık</option>
                                                <option value="in_progress">İşlemde</option>
                                                <option value="resolved">Çözüldü</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Detaylı Açıklama (*)</label>
                                        <textarea name="description" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="complaint_files" class="form-label">Kanıt Dosyaları</label>
                                        <input type="file" name="complaint_files[]" class="form-control" multiple>
                                    </div>
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">
                                        <i class="fa-solid fa-plus me-2"></i>Şikayeti Ekle
                                    </button>
                                </form>

                                <h5><i class="fa-solid fa-list me-2"></i>Kayıtlı Şikayetler</h5>
                                <?php $__empty_1 = true; $__currentLoopData = $customer->complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div
                                        class="alert <?php echo e($complaint->status == 'resolved' ? 'alert-success' : 'alert-warning'); ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-2">
                                                    <i class="fa-solid fa-exclamation-circle me-2"></i>
                                                    <strong><?php echo e($complaint->title); ?></strong>
                                                </h6>
                                                <p class="mb-2"><small>Durum:
                                                        <strong><?php echo e($complaint->status); ?></strong></small></p>
                                                <p class="mb-3"><?php echo e($complaint->description); ?></p>
                                                <?php $__currentLoopData = $complaint->getMedia('complaint_attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="file-list-item">
                                                        <span>
                                                            <i
                                                                class="fa-solid fa-paperclip me-2"></i><?php echo e($media->file_name); ?>

                                                        </span>
                                                        <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                                                            class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                            <i class="fa-solid fa-eye me-1"></i>Görüntüle
                                                        </a>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="alert alert-secondary text-center">
                                        <i class="fa-solid fa-inbox fa-2x mb-3 d-block" style="opacity: 0.3;"></i>
                                        Bu müşteriye ait şikayet kaydı bulunamadı.
                                    </div>
                                <?php endif; ?>
                            </div>

                            
                            <div class="tab-pane fade" id="returns" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı İade Kaydı Ekle</h5>
                                <form action="<?php echo e(route('customers.returns.store', $customer)); ?>" method="POST"
                                    class="quick-add-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Ürün Adı (*)</label>
                                            <input type="text" name="product_name" class="form-control" required
                                                placeholder="Örn: 20L Preform">
                                        </div>
                                        
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Miktar & Birim (*)</label>
                                            <div class="input-group">
                                                <input type="number" name="quantity" class="form-control" required
                                                    step="0.01" placeholder="0.00">
                                                <select name="unit" class="form-select" style="max-width: 90px;"
                                                    required>
                                                    <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($birim->ad); ?>"><?php echo e($birim->ad); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Tarih (*)</label>
                                            <input type="date" name="return_date" class="form-control"
                                                value="<?php echo e(date('Y-m-d')); ?>" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Bağlı Şikayet (Opsiyonel)</label>
                                            <select name="complaint_id" class="form-select">
                                                <option value="">Seçiniz...</option>
                                                <?php $__currentLoopData = $customer->complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($c->id); ?>">#<?php echo e($c->id); ?> -
                                                        <?php echo e(Str::limit($c->title, 20)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">İade Nedeni (*)</label>
                                            <textarea name="reason" class="form-control" rows="2" required placeholder="İade sebebini detaylandırın..."></textarea>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">
                                            <i class="fa-solid fa-save me-2"></i> Kaydet
                                        </button>
                                    </div>
                                </form>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı İadeler</h5>

                                    
                                    <div class="d-flex gap-2 flex-wrap">
                                        <input type="date" id="filterDate" class="filter-input" placeholder="Tarih"
                                            style="max-width: 150px;">
                                        <input type="text" id="filterProduct" class="filter-input"
                                            placeholder="Ürün adı...">
                                        <input type="text" id="filterQuantity" class="filter-input"
                                            placeholder="Miktar..." style="max-width: 100px;">
                                        <input type="text" id="filterUnit" class="filter-input"
                                            placeholder="Birim..." style="max-width: 100px;">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="returnsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Tarih</th>
                                                <th>Ürün</th>
                                                <th>Miktar</th>
                                                <th>Birim</th>
                                                <th>Neden</th>
                                                <th>Durum</th>
                                                <th>Bağlı Şikayet</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $customer->returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($return->return_date->format('d.m.Y')); ?></td>
                                                    <td class="fw-bold"><?php echo e($return->product_name); ?></td>
                                                    <td><?php echo e($return->quantity); ?></td>
                                                    <td><?php echo e($return->unit); ?></td>
                                                    <td><?php echo e(Str::limit($return->reason, 50)); ?></td>
                                                    <td>
                                                        <?php
                                                            $statusMap = [
                                                                'pending' => [
                                                                    'text' => 'Beklemede',
                                                                    'class' => 'warning',
                                                                ],
                                                                'approved' => [
                                                                    'text' => 'Onaylandı',
                                                                    'class' => 'success',
                                                                ],
                                                                'rejected' => [
                                                                    'text' => 'Reddedildi',
                                                                    'class' => 'danger',
                                                                ],
                                                                'completed' => [
                                                                    'text' => 'Tamamlandı',
                                                                    'class' => 'info',
                                                                ],
                                                            ];
                                                            $statusInfo = $statusMap[$return->status] ?? [
                                                                'text' => $return->status,
                                                                'class' => 'secondary',
                                                            ];
                                                        ?>
                                                        <span class="badge bg-<?php echo e($statusInfo['class']); ?> bg-opacity-75">
                                                            <?php echo e($statusInfo['text']); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if($return->complaint): ?>
                                                            <a href="#" class="text-decoration-none"
                                                                title="<?php echo e($return->complaint->title); ?>">
                                                                <i class="fa-solid fa-link me-1"></i>
                                                                <?php echo e(Str::limit($return->complaint->title, 25)); ?>

                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr id="no-returns-row">
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        <i class="fa-solid fa-rotate-left fa-2x mb-2 opacity-50"></i>
                                                        <p class="mb-0">Henüz iade kaydı bulunamadı.</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="visits" role="tabpanel">
                                <h5><i class="fa-solid fa-history me-2"></i>Ziyaret Geçmişi</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Etkinlik/Ziyaret</th>
                                                <th>Tarih</th>
                                                <th>Ziyaret Amacı</th>
                                                <th>Seyahat Programı</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $customer->visits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><strong><?php echo e($visit->event->title ?? 'N/A'); ?></strong></td>
                                                    <td><?php echo e($visit->event ? \Carbon\Carbon::parse($visit->event->start_datetime)->format('d/m/Y H:i') : '-'); ?>

                                                    </td>
                                                    <td><?php echo e($visit->visit_purpose ?? '-'); ?></td>
                                                    <td><?php echo e($visit->travel->name ?? 'Bağımsız Ziyaret'); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">
                                                        Bu müşteriye ait ziyaret kaydı bulunamadı.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            
                            <div class="tab-pane fade" id="activities" role="tabpanel">
                                <div class="row mt-4">
                                    
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm" style="background: #f8f9fa;">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-3 text-primary"><i
                                                        class="fas fa-plus-circle me-1"></i> Yeni İşlem Gir</h6>
                                                <form action="<?php echo e(route('customers.activities.store', $customer->id)); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted">İşlem
                                                            Tipi</label>
                                                        <select name="type" class="form-select">
                                                            <option value="phone">📞 Telefon Görüşmesi</option>
                                                            <option value="meeting">🤝 Yüz Yüze Toplantı</option>
                                                            <option value="email">✉️ E-Posta</option>
                                                            <option value="visit">🏢 Müşteri Ziyareti</option>
                                                            <option value="note">📝 Genel Not</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted">Tarih &
                                                            Saat</label>
                                                        <input type="datetime-local" name="activity_date"
                                                            class="form-control"
                                                            value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted">Detaylar</label>
                                                        <textarea name="description" class="form-control" rows="4" placeholder="Neler konuşuldu? Sonuç ne?" required></textarea>
                                                    </div>
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary text-white"
                                                            style="background: linear-gradient(135deg, #667EEA, #764BA2); border:none;">
                                                            Kaydet
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-8">
                                        <h6 class="fw-bold mb-3 text-secondary">Geçmiş Hareketler</h6>
                                        <div class="timeline">
                                            <?php $__empty_1 = true; $__currentLoopData = $customer->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <div class="card mb-3 border-0 shadow-sm">
                                                    <div class="card-body position-relative">
                                                        
                                                        <div class="position-absolute top-0 start-0 bottom-0 rounded-start"
                                                            style="width: 5px; background: 
                                 <?php echo e($activity->type == 'phone'
                                     ? '#3b82f6'
                                     : ($activity->type == 'meeting'
                                         ? '#10b981'
                                         : ($activity->type == 'email'
                                             ? '#f59e0b'
                                             : '#6b7280'))); ?>;">
                                                        </div>

                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2 ps-2">
                                                            <div>
                                                                <span class="badge bg-light text-dark border me-2">
                                                                    <?php if($activity->type == 'phone'): ?>
                                                                        <i class="fas fa-phone text-primary"></i> Telefon
                                                                    <?php elseif($activity->type == 'meeting'): ?>
                                                                        <i class="fas fa-handshake text-success"></i>
                                                                        Toplantı
                                                                    <?php elseif($activity->type == 'email'): ?>
                                                                        <i class="fas fa-envelope text-warning"></i>
                                                                        E-Posta
                                                                    <?php elseif($activity->type == 'visit'): ?>
                                                                        <i class="fas fa-building text-info"></i> Ziyaret
                                                                    <?php else: ?>
                                                                        <i class="fas fa-sticky-note text-secondary"></i>
                                                                        Not
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span
                                                                    class="text-muted small"><?php echo e($activity->activity_date->format('d.m.Y H:i')); ?></span>
                                                            </div>
                                                            <small class="text-muted fst-italic">
                                                                <i class="fas fa-user-circle me-1"></i>
                                                                <?php echo e($activity->user->name); ?>

                                                            </small>
                                                        </div>
                                                        <div class="ps-2 text-dark" style="white-space: pre-line;">
                                                            <?php echo e($activity->description); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <div class="alert alert-light text-center border border-dashed p-4">
                                                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0 text-muted">Henüz bu müşteriyle ilgili kaydedilmiş bir
                                                        aktivite yok.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="tab-pane fade" id="logistics" role="tabpanel">
                                <div class="card border-0 shadow-sm mt-4">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-4 py-3">Tarih</th>
                                                        <th>Görev Tanımı</th>
                                                        <th>Araç</th>
                                                        <th>Sorumlu</th>
                                                        <th>Durum</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__empty_1 = true; $__currentLoopData = $customer->vehicleAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>
                                                            <td class="ps-4">
                                                                <?php echo e($assignment->start_time->format('d.m.Y H:i')); ?></td>
                                                            <td class="fw-semibold"><?php echo e($assignment->title); ?></td>
                                                            <td>
                                                                <?php if($assignment->vehicle): ?>
                                                                    <?php if($assignment->isLogistics()): ?>
                                                                        <i class="fas fa-truck text-primary me-1"></i>
                                                                    <?php else: ?>
                                                                        <i class="fas fa-car text-info me-1"></i>
                                                                    <?php endif; ?>
                                                                    <?php echo e($assignment->vehicle->plate_number); ?>

                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo e($assignment->responsible->name ?? $assignment->responsible->users_count . ' Kişilik Takım'); ?>

                                                            </td>
                                                            <td>
                                                                <?php if($assignment->status == 'completed'): ?>
                                                                    <span
                                                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Tamamlandı</span>
                                                                <?php elseif($assignment->status == 'cancelled'): ?>
                                                                    <span
                                                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">İptal</span>
                                                                <?php else: ?>
                                                                    <span
                                                                        class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Süreçte</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center py-5 text-muted">
                                                                <i class="fas fa-truck-loading fa-2x mb-3 opacity-50"></i>
                                                                <p class="mb-0">Bu müşteriye yapılmış bir araç
                                                                    görevi/sevkiyat bulunamadı.</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filters = {
                date: document.getElementById('filterDate'),
                product: document.getElementById('filterProduct'),
                quantity: document.getElementById('filterQuantity'),
                unit: document.getElementById('filterUnit')
            };

            const table = document.getElementById('returnsTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(#no-returns-row)'));

            function filterTable() {
                // Tarih input'u YYYY-MM-DD formatında gelir (örn: 2025-10-25)
                // Tablodaki tarih DD.MM.YYYY formatında (örn: 25.10.2025)
                const dateInput = filters.date.value;
                let formattedDateInput = '';

                if (dateInput) {
                    const [year, month, day] = dateInput.split('-');
                    formattedDateInput = `${day}.${month}.${year}`;
                }

                const productValue = filters.product.value.toLowerCase();
                const quantityValue = filters.quantity.value.toLowerCase();
                const unitValue = filters.unit.value.toLowerCase();

                rows.forEach(row => {
                    const dateText = row.cells[0].textContent.trim();
                    const productText = row.cells[1].textContent.toLowerCase();
                    const quantityText = row.cells[2].textContent.toLowerCase();
                    const unitText = row.cells[3].textContent.toLowerCase();

                    // Tarih kontrolü: Filtre boşsa TRUE, doluysa EŞİT Mİ diye bak
                    const dateMatch = !formattedDateInput || dateText === formattedDateInput;
                    const productMatch = productText.includes(productValue);
                    const quantityMatch = quantityText.includes(quantityValue);
                    const unitMatch = unitText.includes(unitValue);

                    if (dateMatch && productMatch && quantityMatch && unitMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Tüm inputlara event listener ekle
            Object.values(filters).forEach(input => {
                input.addEventListener('input', filterTable);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/customers/show.blade.php ENDPATH**/ ?>