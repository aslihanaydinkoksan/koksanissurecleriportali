<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="mb-3">
    <label for="name" class="form-label">Departman Adı (*)</label>
    <input type="text" class="form-control" id="name" name="name"
        value="<?php echo e(old('name', $department->name ?? '')); ?>" placeholder="Örn: Lojistik Departmanı" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label">Kısa Kod (slug) (*)</label>
    <input type="text" class="form-control" id="slug" name="slug"
        value="<?php echo e(old('slug', $department->slug ?? '')); ?>" placeholder="Örn: lojistik (Boşluksuz, küçük harf)"
        <?php if(isset($isCore) && $isCore): ?> readonly 
               style="background-color: #e9ecef;"
               title="Ana sistem departmanlarının kısa kodu değiştirilemez." <?php endif; ?>
        required>
    <small class="form-text text-muted">Bu kod, sistemdeki yetkilendirmeler için kullanılır (örn: 'lojistik', 'uretim').
        Değiştirilmesi önerilmez.</small>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/departments/_form.blade.php ENDPATH**/ ?>