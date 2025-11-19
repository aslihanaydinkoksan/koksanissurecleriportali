

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

        .selection-card input[type="radio"]:checked {
            &~.card-content .card-icon {
                background: linear-gradient(135deg, #667EEA, #764BA2);
                color: white;
            }
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

        .info-box {
            background: linear-gradient(135deg, rgba(219, 234, 254, 0.8), rgba(191, 219, 254, 0.8));
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: "💡";
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.75rem;
        }

        .info-box-content {
            margin-left: 2.5rem;
            color: #1e40af;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .warning-box {
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.8), rgba(253, 230, 138, 0.8));
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            position: relative;
        }

        .warning-box::before {
            content: "⚠️";
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.75rem;
        }

        .warning-box-content {
            margin-left: 2.5rem;
            color: #92400e;
            font-size: 0.9rem;
            line-height: 1.6;
        }

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
                    // Düzeltme 1 ve Yeni Sorun Çözümü: Tüm başlangıç değerlerini boş yapıyoruz.
                    needsVehicle: '<?php echo e(old('needs_vehicle', '')); ?>',
                    vehicleType: '<?php echo e(old('vehicle_type', '')); ?>',
                    responsibleType: '<?php echo e(old('responsible_type', '')); ?>',
                    currentStep: 1,
                
                    get step1Complete() {
                        // Adım 1'in tamamlanması için ya araç GEREKMEMELİ ya da araç GEREKMELİ ve araç tipi seçilmeli
                        return this.needsVehicle === 'no' || (this.needsVehicle === 'yes' && this.vehicleType !== '');
                    },
                    get step2Complete() {
                        return this.responsibleType !== '';
                    }
                }" x-cloak>

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

                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <strong>Başarılı!</strong> <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('service.assignments.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div x-show="currentStep === 1" class="fade-in-up">
                                <h5 class="mb-3">1️⃣ Bu görev için araç gerekli mi?</h5>

                                <label class="selection-card">
                                    
                                    <input type="radio" name="needs_vehicle" value="yes" x-model="needsVehicle"
                                        @change="vehicleType = 'company'">
                                    <div class="card-content">
                                        <div class="card-icon">🚗</div>
                                        <div class="card-text">
                                            <h6>Evet, Araç Gerekli</h6>
                                            <p>Şirket aracı veya nakliye ile
                                                yapılacak görevler</p>
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
                                            <p>Ofis içi, telefon görüşmesi,
                                                toplantı vb. görevler</p>
                                        </div>
                                    </div>
                                </label>

                                <div x-show="needsVehicle === 'yes'" class="mt-4 fade-in-up">
                                    <h6 class="mb-3">Hangi tür araç kullanılacak?</h6>

                                    <label class="selection-card">
                                        
                                        <input type="radio" name="vehicle_type" value="company" x-model="vehicleType"
                                            @click="currentStep = 2">
                                        <div class="card-content">
                                            <div class="card-icon">🚙</div>
                                            <div class="card-text">
                                                <h6>Şirket Aracı</h6>
                                                <p>Kısa mesafeli görevler, evrak taşıma, toplantı vb.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="selection-card">
                                        
                                        <input type="radio" name="vehicle_type" value="logistics" x-model="vehicleType"
                                            @click="currentStep = 2">
                                        <div class="card-content">
                                            <div class="card-icon">🚚</div>
                                            <div class="card-text">
                                                <h6>Nakliye Aracı</h6>
                                                <p>Uzun mesafeli görevler, ağır yük taşıma - KM ve yakıt takibi yapılır</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div x-show="currentStep === 2" class="fade-in-up">
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-3" 
                                    @click="currentStep = 1; vehicleType = ''; responsibleType = ''">
                                    ← Geri
                                </button>

                                <h5 class="mb-3">2️⃣ Görevi kim yapacak?</h5>

                                <label class="selection-card">
                                    <input type="radio" name="responsible_type" value="user" x-model="responsibleType"
                                         @change="currentStep = 3">
                                    <div class="card-content">
                                        <div class="card-icon">👤</div>
                                        <div class="card-text">
                                            <h6>Tek Kişi</h6>
                                            <p>Belirli bir çalışana görev ata
                                            </p>
                                        </div>
                                    </div>
                                </label>

                                <label class="selection-card">
                                    <input type="radio" name="responsible_type" value="team" x-model="responsibleType"
                                        @change="currentStep = 3">
                                    <div class="card-content">
                                        <div class="card-icon">👥</div>
                                        <div class="card-text">
                                            <h6>Takım</h6>
                                            <p>Birden fazla kişiye aynı anda
                                                görev ata</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div x-show="currentStep === 3" class="fade-in-up">
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-3" 
                                    @click="currentStep = 2">
                                    ← Geri
                                </button>

                                <h5 class="mb-4">3️⃣ Görev Detayları</h5>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">📢 Görev Başlığı
                                        *</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="Görevin kısa adı (Örn: İstanbul Şubesi Evrak Teslimi)" required
                                        value="<?php echo e(old('title')); ?>">
                                </div>

                                
                                <div x-show="needsVehicle === 'yes'" class="mb-4">
                                    <label class="form-label fw-bold">
                                        <span
                                            x-text="vehicleType === 'company' ? '🚙 Şirket Aracı' : '🚚 Nakliye Aracı'"></span>
                                        Seçin *
                                    </label>
                                    
                                    <select name="vehicle_id" class="form-select"
                                        :required="needsVehicle === 'yes'">
                                        <option value="">Araç seçiniz...
                                        </option>
                                        <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($vehicle->id); ?>">
                                                <?php echo e($vehicle->plate_number); ?> <?php echo e($vehicle->model); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <template x-if="vehicleType === 'logistics'">
                                            <?php $__currentLoopData = $vehicles->where('type', 'logistics'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($vehicle->id); ?>">

                                                    <?php echo e($vehicle->plate_number); ?> - <?php echo e($vehicle->model); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </template>
                                    </select>
                                </div>

                                
                                <div x-show="needsVehicle === 'yes' && vehicleType === 'logistics'" class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Başlangıç
                                            KM *</label>
                                        <input type="number" step="0.01" name="start_km" class="form-control"
                                            placeholder="Örn: 125000.50"
                                            :required="needsVehicle === 'yes' && vehicleType === 'logistics'">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Yakıt
                                            Durumu *</label>
                                        <select name="start_fuel_level" class="form-select"
                                            :required="needsVehicle === 'yes' && vehicleType === 'logistics'">
                                            <option value="">Seçiniz...
                                            </option>
                                            <option value="full">Dolu (Full)
                                            </option>
                                            <option value="3/4">3/4</option>
                                            <option value="1/2">1/2 (Yarım)
                                            </option>
                                            <option value="1/4">1/4</option>
                                            <option value="empty">Boş</option>
                                        </select>
                                    </div>
                                </div>

                                
                                <div x-show="responsibleType === 'user'" class="mb-4">
                                    <label class="form-label fw-bold">👤 Sorumlu Kişi *</label>
                                    <select :name="responsibleType === 'user' ? 'responsible_user_id' : ''"
                                        class="form-select" :required="responsibleType === 'user'">
                                        <option value="">Kişi seçiniz...</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div x-show="responsibleType === 'team'" class="mb-4">
                                    <label class="form-label fw-bold">👥 Sorumlu Takım
                                        *</label>
                                    <div class="input-group">
                                        
                                        <select :name="responsibleType === 'team' ? 'responsible_team_id' : ''"
                                            id="responsible_team_id" class="form-select"
                                            :required="responsibleType === 'team'">
                                            <option value="">Takım
                                                seçiniz...</option>
                                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($team->id); ?>">
                                                    <?php echo e($team->name); ?>

                                                    (<?php echo e($team->members_count); ?> kişi)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button class="btn btn-outline-success" type="button" data-bs-toggle="modal"
                                            data-bs-target="#newTeamModal">
                                            <i class="fas fa-plus"></i> Yeni
                                            Oluştur
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">📝 Görev
                                        Açıklaması *</label>

                                    <textarea name="task_description" class="form-control" rows="3"                                        
                                        placeholder="Ne yapılması gerekiyor? Detaylı açıklayın..." required><?php echo e(old('task_description')); ?></textarea>

                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">📍 Hedef
                                        Konum</label>
                                    <input type="text" name="destination" class="form-control"
                                        placeholder="Örn: Merkez Ofis, ABC Tedarikçi, İstanbul Şubesi"
                                        value="<?php echo e(old('destination')); ?>">
                                    <small class="text-muted">Opsiyonel - Eğer belirli
                                        bir yere gidilecekse</small>
                                </div>

                                <div x-show="needsVehicle === 'yes' && vehicleType === 'company'" class="warning-box">
                                    <div class="warning-box-content">
                                        <strong>Önemli:</strong> Şirket araçları
                                        <strong>09:30</strong> ve
                                        <strong>13:30</strong>
                                        saatlerinde firmadan ayrılır. Görevinizin
                                        ilgili sefere atanabilmesi için
                                        en az <strong>30 dakika önce</strong> (09:00
                                        veya 13:00'e kadar) görev girişi
                                        yapmalısınız.
                                    </div>
                                </div>

                                <div class="info-box">
                                    <div class="info-box-content">
                                        Görev oluşturulduktan sonra sorumlu
                                        kişi/takım bildirim alacak ve
                                        <strong>"Görevlerim"</strong> sayfasında
                                        görevi görecektir.
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <a href="<?php echo e(route('service.assignments.index')); ?>" class="btn btn-outline-secondary">
                                        İptal
                                    </a>
                                    <button type="submit" class="btn btn-animated-gradient">
                                        ✓ Görevi Oluştur
                                    </button>
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

                        <p class="text-muted mb-3">
                            Takımınıza üye eklemek için görev oluşturduktan sonra
                            <strong>Takım Yönetimi</strong> sayfasını kullanabilirsiniz.
                        </p>

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Takımın İlk Üyesi/Yöneticisi
                                *</label>
                            <select name="members[]" class="form-select" required>
                                <option value="">Bir kullanıcı seçiniz...</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        

                        <div class="mb-3">
                            <label class="form-label fw-bold">Takım Adı *</label>
                            <input type="text" class="form-control" name="name"
                                placeholder="Örn: Lojistik Ekibi, Üretim Takımı" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary" id="saveTeamButton">
                            Takımı Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newTeamForm = document.getElementById('newTeamForm');
            const saveButton = document.getElementById('saveTeamButton');
            const errorContainer = document.getElementById('newTeamErrors');
            const teamDropdown = document.getElementById('responsible_team_id');
            const newTeamModal = new bootstrap.Modal(document.getElementById('newTeamModal'));

            newTeamForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                saveButton.disabled = true;
                saveButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Kaydediliyor...';
                errorContainer.style.display = 'none';

                try {
                    const formData = new FormData(newTeamForm);
                    const response = await fetch(newTeamForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            let errorList = '<ul class="mb-0">';
                            for (const key in data.errors) {
                                errorList += `<li>${data.errors[key][0]}</li>`;
                            }
                            errorList += '</ul>';
                            errorContainer.innerHTML = errorList;
                        } else {
                            errorContainer.innerHTML = data.message || 'Bir hata oluştu.';
                        }
                        errorContainer.style.display = 'block';
                    } else {
                        // Başarılı - Dropdown'a ekle
                        // Ekleme notu: Yeni takım üyesi sayısını formdan gelen üye sayısına göre ayarlayabilirsiniz.
                        const newOption = new Option(
                            `${data.team.name} (1 kişi)`,
                            data.team.id,
                            true,
                            true
                        );
                        teamDropdown.appendChild(newOption);

                        newTeamForm.reset();
                        newTeamModal.hide();

                        // Başarı mesajı göster
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success alert-dismissible fade show';
                        successAlert.innerHTML = `
                            <strong>Başarılı!</strong> ${data.team.name} takımı oluşturuldu.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.card-body').prepend(successAlert);

                        setTimeout(() => successAlert.remove(), 5000);
                    }
                } catch (error) {
                    errorContainer.innerHTML = 'Ağ hatası: ' + error.message;
                    errorContainer.style.display = 'block';
                } finally {
                    saveButton.disabled = false;
                    saveButton.innerHTML = 'Takımı Kaydet';
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/assignments/create.blade.php ENDPATH**/ ?>