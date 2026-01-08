

<?php $__env->startSection('title', 'Kanban Panosunu Düzenle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Panoyu Düzenle: <?php echo e($kanbanBoard->name); ?></h1>

            <form action="<?php echo e(route('kanban-boards.destroy', $kanbanBoard->id)); ?>" method="POST"
                onsubmit="return confirm('DİKKAT! Bu panoyu silerseniz içindeki tüm iş kartları sıralamasını kaybedebilir. Emin misiniz?');">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger btn-sm">Panoyu Sil</button>
            </form>
        </div>

        <form action="<?php echo e(route('kanban-boards.update', $kanbanBoard->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ayarlar</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Pano Adı</label>
                            <input type="text" name="name" value="<?php echo e($kanbanBoard->name); ?>" class="form-control"
                                required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Fabrika</label>
                            <input type="text" value="<?php echo e($kanbanBoard->businessUnit->name ?? 'Bilinmiyor'); ?>"
                                class="form-control bg-light" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Modül</label>
                            <input type="text" value="<?php echo e(strtoupper($kanbanBoard->module_scope)); ?>"
                                class="form-control bg-light" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Sütunlar</h6>
                    <button type="button" onclick="addColumn()" class="btn btn-sm btn-success">+ Sütun Ekle</button>
                </div>
                <div class="card-body">
                    <div id="columns-container">
                        <?php $__currentLoopData = $kanbanBoard->columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row mb-2 align-items-center p-2 border-bottom" id="row-<?php echo e($index); ?>">
                                
                                <input type="hidden" name="columns[<?php echo e($index); ?>][id]" value="<?php echo e($column->id); ?>">

                                <div class="col-md-6">
                                    <input type="text" name="columns[<?php echo e($index); ?>][title]"
                                        value="<?php echo e($column->title); ?>" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="columns[<?php echo e($index); ?>][color_class]"
                                        class="form-control form-select">
                                        <option value="bg-light"
                                            <?php echo e($column->color_class == 'bg-light' ? 'selected' : ''); ?>>Gri</option>
                                        <option value="bg-primary text-white"
                                            <?php echo e($column->color_class == 'bg-primary text-white' ? 'selected' : ''); ?>>Mavi
                                        </option>
                                        <option value="bg-warning text-dark"
                                            <?php echo e($column->color_class == 'bg-warning text-dark' ? 'selected' : ''); ?>>Sarı
                                        </option>
                                        <option value="bg-success text-white"
                                            <?php echo e($column->color_class == 'bg-success text-white' ? 'selected' : ''); ?>>Yeşil
                                        </option>
                                        <option value="bg-danger text-white"
                                            <?php echo e($column->color_class == 'bg-danger text-white' ? 'selected' : ''); ?>>Kırmızı
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="columns[<?php echo e($index); ?>][is_default]" value="1"
                                            <?php echo e($column->is_default ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Varsayılan</label>
                                    </div>
                                </div>
                                <div class="col-md-1 text-right">
                                    <button type="button" onclick="removeColumn('row-<?php echo e($index); ?>')"
                                        class="btn btn-danger btn-sm">Sil</button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Mevcut sayıdan başlatıyoruz
        let columnIndex = <?php echo e($kanbanBoard->columns->count()); ?>;

        function addColumn() {
            const container = document.getElementById('columns-container');
            const html = `
            <div class="row mb-2 align-items-center p-2 border-bottom" id="row-${columnIndex}">
                <div class="col-md-6">
                    <input type="text" name="columns[${columnIndex}][title]" class="form-control" placeholder="Yeni Sütun" required>
                </div>
                <div class="col-md-3">
                    <select name="columns[${columnIndex}][color_class]" class="form-control form-select">
                        <option value="bg-light">Gri</option>
                        <option value="bg-primary text-white">Mavi</option>
                        <option value="bg-warning text-dark">Sarı</option>
                        <option value="bg-success text-white">Yeşil</option>
                        <option value="bg-danger text-white">Kırmızı</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="columns[${columnIndex}][is_default]" value="1">
                        <label class="form-check-label">Varsayılan</label>
                    </div>
                </div>
                <div class="col-md-1 text-right">
                    <button type="button" onclick="removeColumn('row-${columnIndex}')" class="btn btn-danger btn-sm">Sil</button>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
            columnIndex++;
        }

        function removeColumn(rowId) {
            document.getElementById(rowId).remove();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/admin/kanban/edit.blade.php ENDPATH**/ ?>