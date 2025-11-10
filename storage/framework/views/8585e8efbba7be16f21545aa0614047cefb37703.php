<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Müşteri Unvanı (*)</label>
            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name"
                value="<?php echo e(old('name', $customer->name ?? '')); ?>" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="contact_person" class="form-label">İlgili Kişi</label>
            <input type="text" class="form-control" id="contact_person" name="contact_person"
                value="<?php echo e(old('contact_person', $customer->contact_person ?? '')); ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="email" class="form-label">Email Adresi</label>
            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email"
                name="email" value="<?php echo e(old('email', $customer->email ?? '')); ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="phone" class="form-label">Telefon Numarası</label>
            <input type="tel" class="form-control" id="phone" name="phone"
                value="<?php echo e(old('phone', $customer->phone ?? '')); ?>">
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="address" class="form-label">Adres</label>
    <textarea class="form-control" id="address" name="address" rows="3"><?php echo e(old('address', $customer->address ?? '')); ?></textarea>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/customers/_form.blade.php ENDPATH**/ ?>