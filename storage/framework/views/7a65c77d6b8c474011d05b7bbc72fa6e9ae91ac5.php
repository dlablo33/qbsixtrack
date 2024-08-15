

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Registrar Tipo de Cambio</h2>

    <form action="<?php echo e(route('tipocambio.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="tipo_cambio_mxn">Tipo de Cambio MXN:</label>
            <input type="text" name="tipo_cambio_mxn" class="form-control" id="tipo_cambio_mxn" required>
        </div>
        <div class="form-group">
            <label for="tipo_cambio_usd">Tipo de Cambio USD:</label>
            <input type="text" name="tipo_cambio_usd" class="form-control" id="tipo_cambio_usd" required>
        </div>
        <div class="form-group">
            <label for="tipo_conversion">Tipo de Conversión:</label>
            <select name="tipo_conversion" class="form-control" id="tipo_conversion" required>
                <option value="mxn_to_usd">MXN a USD</option>
                <option value="usd_to_mxn">USD a MXN</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/tipocambio/create.blade.php ENDPATH**/ ?>