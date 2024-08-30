

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Listado de Agentes Aduanales</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Botón de regreso -->
        <a href="<?php echo e(route('aduana.index')); ?>" class="btn btn-secondary mb-3">Regresar a Aduanas</a>

        <a href="<?php echo e(route('aduana.create')); ?>" class="btn btn-primary mb-3">Agregar Nuevo Agente</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>RFC</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $agentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($agente->id); ?></td>
                        <td><?php echo e($agente->nombre); ?></td>
                        <td><?php echo e($agente->codigo); ?></td>
                        <td><?php echo e($agente->rfc); ?></td>
                        <td><?php echo e($agente->telefono); ?></td>
                        <td><?php echo e($agente->email); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/aduana/listado.blade.php ENDPATH**/ ?>