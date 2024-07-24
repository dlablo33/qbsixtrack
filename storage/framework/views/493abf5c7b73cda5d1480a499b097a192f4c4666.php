

<?php $__env->startSection('content'); ?>
<div>
    <h1>Molecula 1</h1>

    <!-- Mensajes de éxito y error -->
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

    <!-- Formulario para transferir datos -->
    <form action="" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary">Transferir Datos de Logística a Moléculas</button>
    </form>
    <h1 class="mt-12">Lista de Precios de Moléculas</h1>
    <a href="<?php echo e(route('moleculas.create')); ?>" class="btn btn-primary mb-3">Añadir Precio Molecula</a>

    <!-- Tabla para mostrar los registros de PreciosMolecula -->
    <div class="mt-4">
        <h2>Registros de Precios de Moléculas</h2>

        <?php if($preciosMoleculas->count() > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>BOL</th>
                        <th>Litros</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Fecha de Creación</th>
                        <th>Fecha de Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $preciosMoleculas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $molecula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($molecula->id); ?></td>
                            <td><?php echo e($molecula->bol); ?></td>
                            <td><?php echo e(number_format($molecula->litros, 2, '.', ',')); ?></td>
                            <td>$<?php echo e(number_format($molecula->rate, 2, '.', ',')); ?></td>
                            <td>$<?php echo e(number_format($molecula->total, 2, '.', ',')); ?></td>
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
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/molecula1.blade.php ENDPATH**/ ?>