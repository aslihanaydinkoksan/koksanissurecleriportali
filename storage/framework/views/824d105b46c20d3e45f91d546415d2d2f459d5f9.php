

<?php $__env->startSection('title', 'Yeni Görev Oluştur'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        @keyframes gradientWave {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .create-assignment-card {
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
        }

        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            transform: scale(1.1);
        }

        .step-item.completed .step-circle {
            background: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            color: #666;
            font-weight: 500;
        }

        .step-item.active .step-label {
            color: #667EEA;
            font-weight: 600;
        }

        .selection-card {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
            display: block;
        }

        .selection-card:hover {
            border-color: #667EEA;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .selection-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .selection-card input[type="radio"]:checked~.card-content {
            border-left: 4px solid #667EEA;
            padding-left: 1.25rem;
        }

        .selection-card input[type="radio"]:checked~.card-content .card-icon {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
        }

        .card-content {
            display: flex;
            align-items: start;
            transition: all 0.3s ease;
            padding-left: 0.5rem;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .card-text h6 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            color: #1f2937;
        }

        .card-text p {
            margin: 0;
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* --- PULSE UYARI KUTUSU STİLLERİ --- */
        .warning-box {
            background: linear-gradient(135deg, #fff5e6 0%, #fff9f0 100%);
            border: 2px solid #ff9800;
            border-radius: 12px;
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.15);
            margin-top: 1.5rem;
        }

        .warning-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff9800, #ffa726, #ff9800);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .warning-box-content {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .icon-wrapper {
            flex-shrink: 0;
            position: relative;
            width: 30px;
            height: 30px;
        }

        .warning-icon {
            width: 28px;
            height: 28px;
            background: #ff9800;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            position: relative;
            z-index: 2;
        }

        .pulse-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 28px;
            height: 28px;
            border: 2px solid #ff9800;
            border-radius: 50%;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .pulse-ring:nth-child(2) {
            animation-delay: 0.5s;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(2.5);
                opacity: 0;
            }
        }

        .text-content {
            flex: 1;
            line-height: 1.6;
            color: #5d4037;
        }

        .text-content strong {
            color: #e65100;
            font-weight: 600;
        }

        .time-badge {
            display: inline-block;
            background: white;
            padding: 2px 10px;
            border-radius: 6px;
            font-weight: 600;
            color: #ff9800;
            border: 1px solid #ffe0b2;
            margin: 0 2px;
            font-size: 0.95em;
        }

        .info-text {
            margin-top: 10px;
            font-size: 0.9em;
            color: #6d4c41;
            padding-top: 10px;
            border-top: 1px dashed #ffcc80;
        }

        /* --- PULSE UYARI STİLLERİ BİTİŞ --- */

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            transition: all 0.2s ease;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .fade-in-up {
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card create-assignment-card" x-data="{
                    needsVehicle: '<?php echo e(old('needs_vehicle', '')); ?>',
                    vehicleType: '<?php echo e(old('vehicle_type', '')); ?>',
                    responsibleType: '<?php echo e(old('responsible_type', '')); ?>',
                    currentStep: 1,
                
                    get step1Complete() {
                        return this.needsVehicle === 'no' || (this.needsVehicle === 'yes' && this.vehicleType !== '');
                    },
                    get step2Complete() {
                        return this.responsibleType !== '';
                    }
                }">

                    <div class="card-header bg-transparent border-0 pt-4 pb-3">
                        <h4 class="mb-1">Yeni Görev Oluştur</h4>
                        <p class="text-muted mb-0">Adım adım görev ataması yapın</p>
                    </div>

                    <div class="card-body px-4 py-3">

                        
                        <div class="step-indicator mb-4">
                            <div class="step-item"
                                :class="{ 'active': currentStep === 1, 'completed': step1Complete && currentStep > 1 }">
                                <div class="step-circle">
                                    <span x-show="!step1Complete || currentStep === 1">1</span>
                                    <span x-show="step1Complete && currentStep > 1">✓</span>
                                </div>
                                <div class="step-label">Görev Tipi</div>
                            </div>
                            <div class="step-item"
                                :class="{ 'active': currentStep === 2, 'completed': step2Complete && currentStep > 2 }">
                                <div class="step-circle">
                                    <span x-show="!step2Complete || currentStep === 2">2</span>
                                    <span x-show="step2Complete && currentStep > 2">✓</span>
                                </div>
                                <div class="step-label">Sorumlular</div>
                            </div>
                            <div class="step-item" :class="{ 'active': currentStep === 3 }">
                                <div class="step-circle">3</div>
                                <div class="step-label">Detaylar</div>
                            </div>
                        </div>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <strong>Hata!</strong> Lütfen aşağıdaki sorunları düzeltin:
                                <ul class="mb-0 mt-2">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('service.assignments.store')); ?>">
                            <?php echo csrf_field(); ?>

                            
                            <div x-show="currentStep === 1" class="fade-in-up">
                                <h5 class="mb-3">1️⃣ Bu görev için araç gerekli mi?</h5>

                                <label class="selection-card">
                                    <input type="radio" name="needs_vehicle" value="yes" x-model="needsVehicle"
                                        @change="vehicleType = ''">
                                    <div class="card-content">
                                        <div class="card-icon">🚗</div>
                                        <div class="card-text">
                                            <h6>Evet, Araç Gerekli</h6>
                                            <p>Şirket aracı veya nakliye ile yapılacak görevler</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="selection-card">
                                    <input type="radio" name="needs_vehicle" value="no" x-model="needsVehicle"
                                        @change="vehicleType = ''; currentStep = 2">
                                    <div class="card-content">
                                        <div class="card-icon">👤</div>
                                        <div class="card-text">
                                            <h6>Hayır, Araç Gereksiz</h6>
                                            <p>Şirket içi, telefon görüşmesi, toplantı vb. görevler</p>
                                        </div>
                                    </div>
                                </label>

                                <div x-show="needsVehicle === 'yes'" class="mb-4 fade-in-up">
                                    <h6 class="mb-3">Hangi tür araç kullanılacak?</h6>

                                    <label class="selection-card">
                                        <input type="radio" name="vehicle_type" value="company" x-model="vehicleType">
                                        <div class="card-content">
                                            <div class="card-icon">🚙</div>
                                            <div class="card-text">
                                                <h6>Şirket Aracı</h6>
                                                <p>Kısa mesafeli görevler, evrak taşıma, toplantı vb.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="selection-card">
                                        <input type="radio" name="vehicle_type" value="logistics" x-model="vehicleType">
                                        <div class="card-content">
                                            <div class="card-icon">🚚</div>
                                            <div class="card-text">
                                                <h6>Nakliye Aracı</h6>
                                                <p>Uzun mesafeli görevler, ağır yük taşıma</p>
                                            </div>
                                        </div>
                                    </label>

                                    
                                    
                                    
                                    <div x-show="vehicleType === 'company'" class="warning-box fade-in-up">
                                        <div class="warning-box-content">
                                            <div class="icon-wrapper">
                                                <div class="pulse-ring"></div>
                                                <div class="pulse-ring"></div>
                                                <div class="warning-icon">!</div>
                                            </div>
                                            <div class="text-content">
                                                <strong>Önemli Bilgilendirme:</strong>
                                                <p class="mb-2">
                                                    Şirket araçları her gün <span class="time-badge">09:30</span> ve
                                                    <span class="time-badge">13:30</span> saatlerinde şirketten hareket
                                                    etmektedir.
                                                </p>
                                                <p class="info-text mb-0">
                                                    Görev talebinizi, servis saatinden en az <strong>30 dakika önce</strong>
                                                    oluşturmanız gerekmektedir.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    
                                    <?php if(!in_array(auth()->user()->role, ['müdür', 'admin', 'yönetici'])): ?>
                                        
                                        <div x-show="vehicleType !== ''" x-transition.opacity x-cloak class="mt-3">

                                            
                                            <div class="alert alert-info border-info d-flex align-items-center mb-0">
                                                <div class="h2 me-3 mb-0">🅿️</div>
                                                <div>
                                                    <h6 class="alert-heading fw-bold mb-1">Araç Ataması Onay Bekleyecek</h6>
                                                    <p class="mb-0 small">
                                                        Seçtiğiniz
                                                        
                                                        <strong
                                                            x-text="vehicleType === 'company' ? 'Şirket Aracı' : 'Nakliye Aracı'"></strong>
                                                        türü için talep oluşturuyorsunuz. Detayları girip kaydettikten sonra
                                                        <strong>Araç Sorumlusu</strong> uygun aracı atayacaktır.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <input type="hidden" name="vehicle_id" value="">
                                    <?php endif; ?>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-primary" x-show="step1Complete"
                                        @click="currentStep = 2">
                                        Devam Et →
                                    </button>
                                </div>
                            </div>

                            
                            <div x-show="currentStep === 2" class="fade-in-up">
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-3"
                                    @click="currentStep = 1; vehicleType = ''; responsibleType = ''">← Geri</button>

                                <h5 class="mb-3">2️⃣ Görevi kim yapacak?</h5>

                                <label class="selection-card">
                                    <input type="radio" name="responsible_type" value="user"
                                        x-model="responsibleType">
                                    <div class="card-content">
                                        <div class="card-icon">👤</div>
                                        <div class="card-text">
                                            <h6>Tek Kişi</h6>
                                            <p>Belirli bir çalışana görev ata</p>
                                        </div>
                                    </div>
                                </label>

                                <div x-show="responsibleType === 'user'"
                                    class="mt-3 mb-4 ps-4 border-start border-3 border-primary">
                                    <label class="form-label fw-bold">Sorumlu Kişiyi Seçin *</label>
                                    <select name="responsible_user_id" class="form-select"
                                        :required="responsibleType === 'user'"
                                        :disabled="responsibleType !== 'user'">
                                        <option value="">Kişi seçiniz...</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="mt-2 text-end">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            @click="currentStep = 3">Devam Et →</button>
                                    </div>
                                </div>

                                <label class="selection-card mt-3">
                                    <input type="radio" name="responsible_type" value="team"
                                        x-model="responsibleType">
                                    <div class="card-content">
                                        <div class="card-icon">👥</div>
                                        <div class="card-text">
                                            <h6>Takım</h6>
                                            <p>Birden fazla kişiye aynı anda görev ata</p>
                                        </div>
                                    </div>
                                </label>

                                <div x-show="responsibleType === 'team'"
                                    class="mt-3 mb-4 ps-4 border-start border-3 border-primary">
                                    <label class="form-label fw-bold">Sorumlu Takımı Seçin *</label>
                                    <div class="input-group">
                                        <select id="responsible_team_id" name="responsible_team_id" class="form-select"
                                            :required="responsibleType === 'team'"
                                            :disabled="responsibleType !== 'team'">
                                            <option value="">Takım seçiniz...</option>
                                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($team->id); ?>"><?php echo e($team->name); ?>

                                                    (<?php echo e($team->members_count); ?> kişi)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button class="btn btn-outline-success" type="button" data-bs-toggle="modal"
                                            data-bs-target="#newTeamModal">
                                            <i class="fas fa-plus"></i> Yeni
                                        </button>
                                    </div>
                                    <div class="mt-2 text-end">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            @click="currentStep = 3">Devam Et →</button>
                                    </div>
                                </div>
                            </div>

                            
                            <div x-show="currentStep === 3" class="fade-in-up">
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-3"
                                    @click="currentStep = 2">← Geri</button>

                                <h5 class="mb-4">3️⃣ Görev Detayları</h5>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">📢 Görev Başlığı *</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="Görevin kısa adı" required value="<?php echo e(old('title')); ?>">
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">📅 Planlanan Başlangıç *</label>
                                        <input type="datetime-local" name="start_time" class="form-control"
                                            min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
                                            max="<?php echo e(now()->addMonth()->format('Y-m-d\TH:i')); ?>"
                                            value="<?php echo e(old('start_time')); ?>" required>
                                        <div class="form-text">En fazla 1 ay sonrasına kadar görev oluşturabilirsiniz.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">🏁 Tahmini Bitiş *</label>
                                        <input type="datetime-local" name="end_time" class="form-control"
                                            min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
                                            max="<?php echo e(now()->addMonth()->endOfDay()->format('Y-m-d\TH:i')); ?>"
                                            value="<?php echo e(old('end_time')); ?>" required>
                                    </div>
                                </div>

                                
                                <?php if(in_array(auth()->user()->role, ['müdür', 'admin', 'yönetici'])): ?>
                                    <div x-show="needsVehicle === 'yes'" class="mb-4">
                                        <label class="form-label fw-bold">
                                            <span
                                                x-text="vehicleType === 'company' ? '🚙 Şirket Aracı' : '🚚 Nakliye Aracı'"></span>
                                            Seçin *
                                        </label>

                                        <div x-show="vehicleType === 'company'">
                                            <select name="vehicle_id" class="form-select"
                                                :required="needsVehicle === 'yes' && vehicleType === 'company'"
                                                :disabled="vehicleType !== 'company'">
                                                <option value="">Şirket aracı seçiniz...</option>
                                                <?php $__currentLoopData = $companyVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->plate_number); ?> -
                                                        <?php echo e($vehicle->brand_model); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <div x-show="vehicleType === 'logistics'">
                                            <select name="vehicle_id" class="form-select"
                                                :required="needsVehicle === 'yes' && vehicleType === 'logistics'"
                                                :disabled="vehicleType !== 'logistics'">
                                                <option value="">Nakliye aracı seçiniz...</option>
                                                <?php $__currentLoopData = $logisticsVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->plate_number); ?> -
                                                        <?php echo e($vehicle->brand); ?> <?php echo e($vehicle->model); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>

                                    
                                    <div x-show="needsVehicle === 'yes' && vehicleType === 'logistics'"
                                        class="row mb-4 p-3 bg-light rounded border mx-1">
                                        <div class="col-12 mb-2">
                                            <h6 class="text-primary"><i class="fas fa-tachometer-alt me-1"></i> Sefer
                                                Başlangıç Bilgileri</h6>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Başlangıç KM *</label>
                                            <input type="number" step="0.1" name="start_km" class="form-control"
                                                placeholder="Örn: 125000.5">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Yakıt Durumu *</label>
                                            <select name="start_fuel_level" class="form-select">
                                                <option value="">Seçiniz...</option>
                                                <option value="full">Dolu (Full)</option>
                                                <option value="3/4">3/4</option>
                                                <option value="1/2">1/2</option>
                                                <option value="1/4">1/4</option>
                                                <option value="empty">Boş</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">📝 Görev Açıklaması *</label>
                                    <textarea name="task_description" class="form-control" rows="3" required><?php echo e(old('task_description')); ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">🏢 İlgili Müşteri (Opsiyonel)</label>
                                    <select name="customer_id" class="form-select" x-data
                                        @change="
                                        if($el.options[$el.selectedIndex].text !== 'Seçiniz...' && document.querySelector('[name=destination]').value === '') {
                                            document.querySelector('[name=destination]').value = $el.options[$el.selectedIndex].text;
                                        }
                                    ">
                                        <option value="">Seçiniz...</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>"
                                                <?php echo e(old('customer_id') == $customer->id ? 'selected' : ''); ?>>
                                                <?php echo e($customer->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">📍 Hedef Konum</label>
                                    <input type="text" name="destination" class="form-control"
                                        value="<?php echo e(old('destination')); ?>">
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <a href="<?php echo e(route('service.assignments.index')); ?>"
                                        class="btn btn-outline-secondary">İptal</a>
                                    <button type="submit" class="btn btn-animated-gradient">✓ Görevi Oluştur</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="newTeamModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hızlı Takım Oluştur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="newTeamForm" action="<?php echo e(route('teams.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div id="newTeamErrors" class="alert alert-danger" style="display: none;"></div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Takımın İlk Üyesi/Yöneticisi *</label>
                            <select name="members[]" class="form-select" required>
                                <option value="">Bir kullanıcı seçiniz...</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Takım Adı *</label>
                            <input type="text" class="form-control" name="name" placeholder="Örn: Lojistik Ekibi"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary" id="saveTeamButton">Takımı Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('assignmentForm', () => ({
                needsVehicle: '<?php echo e(old('needs_vehicle', '')); ?>',
                vehicleType: '<?php echo e(old('vehicle_type', '')); ?>',
                responsibleType: '<?php echo e(old('responsible_type', '')); ?>',
                currentStep: 1,

                // Getter'ları fonksiyon olarak tanımlayalım (daha güvenli)
                isStep1Complete() {
                    return this.needsVehicle === 'no' || (this.needsVehicle === 'yes' && this
                        .vehicleType !== '');
                },
                isStep2Complete() {
                    return this.responsibleType !== '';
                }
            }))
        });
    </script>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newTeamForm = document.getElementById('newTeamForm');
            // ... (senin diğer kodların aynen kalacak)
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/assignments/create.blade.php ENDPATH**/ ?>