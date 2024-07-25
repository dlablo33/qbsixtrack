

<?php $__env->startSection('content'); ?>
<div>
    <h1>Moleculas</h1>

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

    <form action="<?php echo e(route('moleculas.transfer')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary">Transferir Datos de Logística a Moléculas</button>
    </form>

    <a href="<?php echo e(route('moleculas.create')); ?>" class="btn btn-secondary mt-3">Añadir Precio de Molécula</a>

    <h2 class="mt-4">Registros de Precios de Moléculas</h2>

    <?php if($moleculas->count() > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Molécula</th>
                    <th>Precio</th>
                    <th>Usuario</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Actualización</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $moleculas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $molecula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($molecula->id); ?></td>
                        <td><?php echo e($molecula->molecula); ?></td>
                        <td><?php echo e(number_format($molecula->precio, 2, '.', ',')); ?></td>
                        <td><?php echo e($molecula->usuario); ?></td>
                        <td><?php echo e($molecula->created_at); ?></td>
                        <td><?php echo e($molecula->updated_at); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay registros de precios de moléculas.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/index.blade.php ENDPATH**/ ?>