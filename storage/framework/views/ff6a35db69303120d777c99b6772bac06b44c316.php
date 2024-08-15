<!-- resources/views/tipo_cambio/index.blade.php -->



<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Tipos de Cambio Registrados</h2>

    <a href="<?php echo e(route('tipocambio.create')); ?>" class="btn btn-primary mb-3">Registrar Nuevo Tipo de Cambio</a>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo de Cambio (MXN)</th>
                <th>Tipo de Cambio (USD)</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tiposCambio; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipoCambio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($tipoCambio->id); ?></td>
                    <td><?php echo e($tipoCambio->fecha->format('Y-m-d')); ?></td>
                    <td><?php echo e(number_format($tipoCambio->tipo_cambio_mxn, 4)); ?></td>
                    <td><?php echo e(number_format($tipoCambio->tipo_cambio_usd, 4)); ?></td>
                    <td><?php echo e($tipoCambio->created_at->format('Y-m-d H:i:s')); ?></td>
                    <td><?php echo e($tipoCambio->updated_at->format('Y-m-d H:i:s')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/tipocambio/index.blade.php ENDPATH**/ ?>