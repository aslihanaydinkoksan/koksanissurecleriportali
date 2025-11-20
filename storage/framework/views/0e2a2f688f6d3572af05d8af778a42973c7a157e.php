<?php if($errors->any()): ?>
    <div class="alert alert-danger d-flex align-items-start" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-3 fs-4 mt-1"></i>
        <div class="flex-grow-1">
            <strong>Lütfen aşağıdaki hataları düzeltin:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name"
                value="<?php echo e(old('name', $customer->name ?? '')); ?>" placeholder="Müşteri Unvanı" autocomplete="off"
                required>
            <label for="name">
                <i class="fa-solid fa-building me-2 text-primary"></i>Müşteri Unvanı <span class="text-danger">*</span>
            </label>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="contact_person" name="contact_person"
                value="<?php echo e(old('contact_person', $customer->contact_person ?? '')); ?>" placeholder="İlgili Kişi"
                autocomplete="off">
            <label for="contact_person">
                <i class="fa-solid fa-user me-2 text-primary"></i>İlgili Kişi
            </label>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email"
                name="email" value="<?php echo e(old('email', $customer->email ?? '')); ?>" placeholder="Email Adresi"
                autocomplete="off">
            <label for="email">
                <i class="fa-solid fa-envelope me-2 text-primary"></i>Email Adresi
            </label>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="tel" class="form-control" id="phone" name="phone"
                value="<?php echo e(old('phone', $customer->phone ?? '')); ?>" placeholder="Telefon Numarası" autocomplete="off">
            <label for="phone">
                <i class="fa-solid fa-phone me-2 text-primary"></i>Telefon Numarası
            </label>
        </div>
    </div>
</div>

<div class="form-floating mb-3">
    <textarea class="form-control" id="address" name="address" placeholder="Adres" style="height: 120px"
        autocomplete="off"><?php echo e(old('address', $customer->address ?? '')); ?></textarea>
    <label for="address">
        <i class="fa-solid fa-location-dot me-2 text-primary"></i>Adres
    </label>
</div>

<div class="alert alert-info border-0 d-flex align-items-center" role="alert">
    <i class="fa-solid fa-info-circle me-3 fs-4"></i>
    <div>
        <strong>Not:</strong> (<span class="text-danger">*</span>) işareti olan alanlar zorunludur.
    </div>
</div>

<style>
    .form-floating>.form-control {
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 0.75rem;
        padding: 1rem 0.75rem;
        transition: all 0.3s ease;
        background-color: rgba(255, 255, 255, 0.9);
    }

    .form-floating>.form-control:focus {
        border-color: #667EEA;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        background-color: white;
    }

    .form-floating>label {
        padding: 1rem 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 0.75rem;
        padding: 1rem;
        border: none;
    }

    .alert-danger ul {
        padding-left: 1.5rem;
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/customers/_form.blade.php ENDPATH**/ ?>