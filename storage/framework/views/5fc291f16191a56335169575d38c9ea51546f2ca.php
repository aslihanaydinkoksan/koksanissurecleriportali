

<?php $__env->startSection('title', 'Kanban Panosunu Düzenle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fa fa-edit text-warning mr-2"></i> Panoyu Düzenle: <?php echo e($kanbanBoard->name); ?>

            </h1>

            <div class="d-flex border-left pl-3">
                
                <form action="<?php echo e(route('kanban-boards.destroy', $kanbanBoard->id)); ?>" method="POST"
                    onsubmit="return confirm('DİKKAT! Bu panoyu silerseniz içindeki tüm kartlar ve sütun yapısı tamamen silinecektir. Bu işlem geri alınamaz! Emin misiniz?');">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm mr-2">
                        <i class="fa fa-trash mr-1"></i> Panoyu Tamamen Sil
                    </button>
                </form>

                <a href="<?php echo e(route('kanban-boards.index')); ?>" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Geri Dön
                </a>
            </div>
        </div>

        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger shadow-sm border-left-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('kanban-boards.update', $kanbanBoard->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-cog mr-2"></i> Temel Bilgiler</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <?php if(auth()->user()->isAdmin()): ?>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-primary">Pano Sahibi</label>
                                <select name="user_id" class="form-control form-select border-primary shadow-sm">
                                    <?php $__currentLoopData = \App\Models\User::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($u->id); ?>"
                                            <?php echo e($kanbanBoard->user_id == $u->id ? 'selected' : ''); ?>>
                                            <?php echo e($u->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-primary font-weight-bold">Yönetici: Panoyu başka birine
                                    devredebilirsiniz.</small>
                            </div>
                        <?php endif; ?>

                        
                        <div class="<?php echo e(auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4'); ?> mb-3">
                            <label class="form-label font-weight-bold">Pano Adı</label>
                            <input type="text" name="name" value="<?php echo e(old('name', $kanbanBoard->name)); ?>"
                                class="form-control shadow-sm" required>
                            <small class="text-muted">Görünen pano başlığı.</small>
                        </div>

                        
                        <div class="<?php echo e(auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4'); ?> mb-3">
                            <label class="form-label font-weight-bold text-dark">Fabrika / Birim</label>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <select name="business_unit_id" class="form-control form-select shadow-sm">
                                    <?php $__currentLoopData = \App\Models\BusinessUnit::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($unit->id); ?>"
                                            <?php echo e($kanbanBoard->business_unit_id == $unit->id ? 'selected' : ''); ?>>
                                            <?php echo e($unit->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php else: ?>
                                <div class="form-control bg-light border-dashed" style="border-style: dashed;">
                                    <?php echo e($kanbanBoard->businessUnit->name ?? 'Bilinmiyor'); ?>

                                </div>
                                <input type="hidden" name="business_unit_id" value="<?php echo e($kanbanBoard->business_unit_id); ?>">
                            <?php endif; ?>
                        </div>

                        
                        <div class="<?php echo e(auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4'); ?> mb-3">
                            <label class="form-label font-weight-bold text-dark">İş Akışı (Kapsam)</label>
                            <div class="form-control bg-light border-dashed text-muted" style="border-style: dashed;">
                                <i class="fa fa-lock mr-2 text-warning"></i> <?php echo e(strtoupper($kanbanBoard->module_scope)); ?>

                            </div>
                            <small class="text-danger">Modül tipi oluşturulduktan sonra değiştirilemez.</small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fa fa-columns mr-2"></i> Pano Sütunları</h6>
                    <button type="button" onclick="addColumn()" class="btn btn-sm btn-success shadow-sm">
                        <i class="fa fa-plus"></i> Yeni Sütun Ekle
                    </button>
                </div>
                <div class="card-body">
                    <div id="columns-container" class="bg-light p-3 rounded" style="border: 1px solid #e3e6f0;">
                        <?php $__currentLoopData = $kanbanBoard->columns->sortBy('order_index'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row mb-3 align-items-center p-3 bg-white rounded shadow-sm border mx-0 column-row"
                                id="row-<?php echo e($index); ?>">
                                
                                <input type="hidden" name="columns[<?php echo e($index); ?>][id]" value="<?php echo e($column->id); ?>">

                                <div class="col-md-5">
                                    <label class="small font-weight-bold text-gray-700">Sütun Başlığı</label>
                                    <input type="text" name="columns[<?php echo e($index); ?>][title]"
                                        value="<?php echo e($column->title); ?>" class="form-control shadow-sm" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="small font-weight-bold text-gray-700">Renk Teması</label>
                                    <select name="columns[<?php echo e($index); ?>][color_class]"
                                        class="form-control form-select">
                                        <option value="bg-light"
                                            <?php echo e($column->color_class == 'bg-light' ? 'selected' : ''); ?>>Gri (Standart)
                                        </option>
                                        <option value="bg-primary text-white"
                                            <?php echo e(str_contains($column->color_class, 'bg-primary') ? 'selected' : ''); ?>>Mavi
                                        </option>
                                        <option value="bg-warning text-dark"
                                            <?php echo e(str_contains($column->color_class, 'bg-warning') ? 'selected' : ''); ?>>Sarı
                                        </option>
                                        <option value="bg-success text-white"
                                            <?php echo e(str_contains($column->color_class, 'bg-success') ? 'selected' : ''); ?>>Yeşil
                                        </option>
                                        <option value="bg-danger text-white"
                                            <?php echo e(str_contains($column->color_class, 'bg-danger') ? 'selected' : ''); ?>>Kırmızı
                                        </option>
                                        <option value="bg-info text-white"
                                            <?php echo e(str_contains($column->color_class, 'bg-info') ? 'selected' : ''); ?>>Turkuaz
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4 text-center">
                                    <div class="form-check form-switch shadow-sm border p-2 rounded"
                                        style="min-height: 38px;">
                                        <input type="radio" name="default_selection" value="<?php echo e($index); ?>"
                                            <?php echo e($column->is_default ? 'checked' : ''); ?> class="form-check-input"
                                            id="defaultCheck<?php echo e($index); ?>"
                                            onclick="updateDefaultValue(<?php echo e($index); ?>)"
                                            style="float: none; margin-left: 0;">
                                        <input type="hidden" name="columns[<?php echo e($index); ?>][is_default]"
                                            id="hiddenDefault<?php echo e($index); ?>"
                                            value="<?php echo e($column->is_default ? '1' : '0'); ?>">
                                        <label class="form-check-label small d-block"
                                            for="defaultCheck<?php echo e($index); ?>">Varsayılan mı?</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mt-4 text-right">
                                    <button type="button" onclick="removeColumn('row-<?php echo e($index); ?>')"
                                        class="btn btn-outline-danger btn-sm px-3">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fa fa-save mr-2"></i> Değişiklikleri Kaydet
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Mevcut sütun sayısından başlatıyoruz
        let columnIndex = <?php echo e($kanbanBoard->columns->count()); ?>;

        function addColumn() {
            const container = document.getElementById('columns-container');
            const html = `
                <div class="row mb-3 align-items-center p-3 bg-white rounded shadow-sm border mx-0 column-row" id="row-${columnIndex}">
                    <div class="col-md-5">
                        <label class="small font-weight-bold text-gray-700">Sütun Başlığı</label>
                        <input type="text" name="columns[${columnIndex}][title]" class="form-control shadow-sm" placeholder="Yeni Sütun Adı" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-gray-700">Renk Teması</label>
                        <select name="columns[${columnIndex}][color_class]" class="form-control form-select">
                            <option value="bg-light">Gri (Standart)</option>
                            <option value="bg-primary text-white">Mavi</option>
                            <option value="bg-warning text-dark">Sarı</option>
                            <option value="bg-success text-white">Yeşil</option>
                            <option value="bg-danger text-white">Kırmızı</option>
                            <option value="bg-info text-white">Turkuaz</option>
                        </select>
                    </div>
                    <div class="col-md-2 mt-4 text-center">
                        <div class="form-check form-switch shadow-sm border p-2 rounded" style="min-height: 38px;">
                            <input type="radio" name="default_selection" value="${columnIndex}" class="form-check-input" id="defaultCheck${columnIndex}" onclick="updateDefaultValue(${columnIndex})" style="float: none; margin-left: 0;">
                            <input type="hidden" name="columns[${columnIndex}][is_default]" id="hiddenDefault${columnIndex}" value="0">
                            <label class="form-check-label small d-block" for="defaultCheck${columnIndex}">Varsayılan mı?</label>
                        </div>
                    </div>
                    <div class="col-md-2 mt-4 text-right">
                        <button type="button" onclick="removeColumn('row-${columnIndex}')" class="btn btn-outline-danger btn-sm px-3">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            columnIndex++;
        }

        function removeColumn(id) {
            const el = document.getElementById(id);
            if (el) {
                // Eğer içinde ID olan bir sütunsa, kullanıcıyı uyarabiliriz.
                const hasId = el.querySelector('input[name*="[id]"]');
                if (hasId) {
                    if (!confirm(
                            'Bu mevcut bir sütun. Sildiğinizde bu sütundaki kartlar "Varsayılan" sütuna taşınacaktır. Emin misiniz?'
                        )) {
                        return;
                    }
                }
                el.remove();
            }
        }

        function updateDefaultValue(index) {
            // Tüm gizli inputları sıfırla
            document.querySelectorAll('input[id^="hiddenDefault"]').forEach(input => input.value = '0');
            // Seçilenin gizli inputunu 1 yap
            const target = document.getElementById('hiddenDefault' + index);
            if (target) target.value = '1';
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/admin/kanban/edit.blade.php ENDPATH**/ ?>