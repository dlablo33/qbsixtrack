

<?php $__env->startSection('content'); ?>
<div class="container">

    <!-- Mostrar mensaje de éxito si existe -->
    <?php if(session()->has('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <h2>Crear Cliente</h2>

    <form action="<?php echo e(route('admin.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <!-- Campo para el nombre del cliente -->
        <div class="form-group">
            <label for="cliente">Nombre del Cliente:</label>
            <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Ingresa el nombre del cliente" required>
        </div>

        <!-- Botón para enviar el formulario -->
        <button type="submit" class="btn btn-primary">Crear Cliente</button>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/Admin/create.blade.php ENDPATH**/ ?>