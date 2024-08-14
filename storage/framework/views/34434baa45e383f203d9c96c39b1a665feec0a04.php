

<?php $__env->startSection('content'); ?>
<style>
    /* Estilos omitidos por brevedad */
</style>

<div class="container mt-4">
    <h1 class="display-6">Resumen General de Ingresos y Devoluciones</h1>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="thead-custom">
                <tr>
                    <th>ID</th>
                    <th>Banco</th>
                    <th>Cliente</th>
                    <th>Saldo en MXN</th>
                    <th>Saldo en USD</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $depositos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposito): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($deposito->id); ?></td>
                        <td><?php echo e($deposito->banco->banco); ?></td>
                        <td><?php echo e($deposito->cliente->cliente); ?></td>
                        <td>$<?php echo e(number_format($deposito->saldo_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($deposito->saldo_usd, 2, '.', ',')); ?></td>
                        <td><?php echo e($deposito->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <?php if(!$deposito->asignado): ?>
                                <form action="<?php echo e(route('depositos.asignarSaldo', $deposito->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="cliente_id">Asignar a Cliente:</label>
                                        <select name="cliente_id" id="cliente_id" class="form-control">
                                        <option value="" disabled selected>--Seleccionar cliente--</option>
                                            <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($cliente->id); ?>"><?php echo e($cliente->NOMBRE_COMERCIAL); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <?php if($deposito->saldo_usd > 0): ?>
                                        <div class="form-group">
                                            <label for="tipo_cambio">Tipo de Cambio:</label>
                                            <input type="text" name="tipo_cambio" id="tipo_cambio" class="form-control" required>
                                        </div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-success btn-animated">Asignar a Saldo a Favor</button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary btn-animated" disabled>Asignado</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $__currentLoopData = $devoluciones->where('id_deposito', $deposito->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $devolucion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-warning">
                            <td><?php echo e($devolucion->id); ?></td>
                            <td><?php echo e($devolucion->banco->banco); ?></td>
                            <td><?php echo e($deposito->cliente->cliente); ?></td>
                            <td>- $<?php echo e(number_format($devolucion->cantidad, 2, '.', ',')); ?></td>
                            <td>-</td>
                            <td><?php echo e($devolucion->created_at->format('d/m/Y H:i')); ?></td>
                            <td>Devolución en <?php echo e($devolucion->moneda); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    
    <div class="btn-container mt-4">
        <a href="<?php echo e(route('Admin.index')); ?>" class="btn btn-success btn-animated">Volver</a>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/Admin/ingresos_devoluciones.blade.php ENDPATH**/ ?>