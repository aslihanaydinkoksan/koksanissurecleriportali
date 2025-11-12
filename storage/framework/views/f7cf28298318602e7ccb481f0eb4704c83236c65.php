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
    <label for="name" class="form-label">Seyahat Adı (*)</label>
    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name"
        value="<?php echo e(old('name', $travel->name ?? '')); ?>" autocomplete="off" required>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="start_date" class="form-label">Başlangıç Tarihi (*)</label>
            <input type="date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="start_date"
                name="start_date" value="<?php echo e(old('start_date', $travel->start_date ?? '')); ?>" autocomplete="off"
                required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="end_date" class="form-label">Bitiş Tarihi (*)</label>
            <input type="date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="end_date"
                name="end_date" value="<?php echo e(old('end_date', $travel->end_date ?? '')); ?>" autocomplete="off" required>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Durum (*)</label>
    <select name="status" id="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
        <option value="planned" <?php if(old('status', $travel->status ?? '') == 'planned'): ?> selected <?php endif; ?>>
            Planlandı
        </option>
        <option value="completed" <?php if(old('status', $travel->status ?? '') == 'completed'): ?> selected <?php endif; ?>>
            Tamamlandı
        </option>
    </select>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/travels/_form.blade.php ENDPATH**/ ?>