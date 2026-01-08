

<?php $__env->startSection('title', 'Yeni Kanban Panosu Oluştur'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Yeni Kanban Panosu Oluştur</h1>
            <a href="<?php echo e(route('kanban-boards.index')); ?>" class="btn btn-sm btn-secondary shadow-sm">
                Geri Dön
            </a>
        </div>
        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('kanban-boards.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Genel Ayarlar</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Pano Adı</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Örn: Bakım İş Emirleri Panosu" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Fabrika / Birim</label>
                            <select name="business_unit_id" class="form-control form-select">
                                <?php $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Modül</label>
                            <select name="module_scope" class="form-control form-select">
                                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pano Sütunları (Süreç Adımları)</h6>
                    <button type="button" onclick="addColumn()" class="btn btn-sm btn-success">
                        + Yeni Sütun Ekle
                    </button>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Sütunları süreç sırasına göre ekleyin. Sürükle-bırak yaparken bu sıra
                        baz alınacaktır.</p>

                    <div id="columns-container">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Panoyu Kaydet
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let columnIndex = 0;

        // Sayfa açılışında otomatik 1 tane boş gelsin
        window.onload = function() {
            addColumn();
        };

        function addColumn() {
            const container = document.getElementById('columns-container');

            // Bootstrap Grid yapısına uygun HTML
            const html = `
            <div class="row mb-2 align-items-center p-2 border-bottom" id="row-${columnIndex}">
                <div class="col-md-6">
                    <input type="text" name="columns[${columnIndex}][title]" class="form-control" placeholder="Sütun Başlığı (Örn: Bekleyenler)" required>
                </div>
                <div class="col-md-3">
                    <select name="columns[${columnIndex}][color_class]" class="form-control form-select">
                        <option value="bg-light">Gri (Standart)</option>
                        <option value="bg-primary text-white">Mavi</option>
                        <option value="bg-warning text-dark">Sarı</option>
                        <option value="bg-success text-white">Yeşil</option>
                        <option value="bg-danger text-white">Kırmızı</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="columns[${columnIndex}][is_default]" value="1" id="defaultCheck${columnIndex}">
                        <label class="form-check-label" for="defaultCheck${columnIndex}">
                            Varsayılan
                        </label>
                    </div>
                </div>
                <div class="col-md-1 text-right">
                    <button type="button" onclick="removeColumn('row-${columnIndex}')" class="btn btn-danger btn-sm">Sil</button>
                </div>
            </div>
        `;

            //insertAdjacentHTML kullanımı performans için daha iyidir
            container.insertAdjacentHTML('beforeend', html);
            columnIndex++;
        }

        function removeColumn(rowId) {
            const el = document.getElementById(rowId);
            if (el) el.remove();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/admin/kanban/create.blade.php ENDPATH**/ ?>