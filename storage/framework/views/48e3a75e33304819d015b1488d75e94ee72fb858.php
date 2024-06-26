

<?php $__env->startSection('content'); ?>
    <h1>Registrar Pago para Factura #<?php echo e($factura->id); ?></h1>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('cuentas.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="factura_id" value="<?php echo e($factura->id); ?>">

        <div class="form-group">
            <label for="monto">Monto:</label>
            <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="fecha_pago">Fecha de Pago:</label>
            <input type="date" name="fecha_pago" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" name="referencia" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Pago</button>
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Regresar</a>
    </form>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/create.blade.php ENDPATH**/ ?>