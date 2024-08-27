<!-- resources/views/aduana/agentes/create.blade.php -->



<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Agregar Nuevo Agente Aduanal</h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('aduana.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo e(old('nombre')); ?>" required>
            </div>

            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo e(old('codigo')); ?>" required>
            </div>

            <div class="form-group">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc" class="form-control" value="<?php echo e(old('rfc')); ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo e(old('telefono')); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>">
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="<?php echo e(route('aduana.index')); ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/aduana/create.blade.php ENDPATH**/ ?>