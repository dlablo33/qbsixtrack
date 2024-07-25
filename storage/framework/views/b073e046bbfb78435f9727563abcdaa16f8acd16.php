

<?php $__env->startSection('content'); ?>
<div>
    <h1>Añadir Precio de Molécula</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('moleculas.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="molecula">Molécula</label>
            <input type="number" class="form-control" id="molecula" name="molecula" required>
        </div>
        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="float" class="form-control" id="precio" name="precio" required>
        </div>
        <button type="submit" class="btn btn-primary">Añadir Precio</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/create.blade.php ENDPATH**/ ?>