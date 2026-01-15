
<?php $__env->startComponent('mail::message'); ?>
# <?php echo e($reportName); ?>


KÖKSAN İş Süreçleri Portalı periyodik raporunuz ektedir.

<?php $__env->startComponent('mail::panel'); ?>
**Rapor Özeti**
* **Adı:** <?php echo e($reportName); ?>

* **Tarih:** <?php echo e(now()->format('d.m.Y')); ?>

* **Saat:** <?php echo e(now()->format('H:i')); ?>

* **Format:** <?php echo e($fileFormat === 'pdf' ? 'PDF' : 'Excel'); ?>

<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::button', ['url' => config('app.url')]); ?>
Portala Git
<?php echo $__env->renderComponent(); ?>

Teşekkürler,
**<?php echo e(config('app.name')); ?>**
<?php echo $__env->renderComponent(); ?>

<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/emails/reports/scheduled.blade.php ENDPATH**/ ?>