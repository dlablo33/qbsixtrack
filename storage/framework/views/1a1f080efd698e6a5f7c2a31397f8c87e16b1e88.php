<!-- Dentro de bluewi/not-in-invoice.blade.php -->



<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Registros no encontrados en Invoice</h1>

        <?php if($notInInvoice->isEmpty()): ?>
            <p>No se encontraron registros en Bluewi que no estén en Invoice.</p>
        <?php else: ?>
            <ul>
                <?php $__currentLoopData = $notInInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($item->bol_number); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>

        <a href="<?php echo e(route('bluewi.index')); ?>" class="btn btn-primary">Volver a Bluewi</a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/bluewi/not-in-invoice.blade.php ENDPATH**/ ?>