

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Facturas del Cliente: <?php echo e($cliente_name); ?></h1>
        <h2>Saldo a Favor: $<?php echo e(number_format($saldoAFavor, 2, '.', '')); ?></h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Total</th>
                    <th>Abonos</th>
                    <th>Restante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($factura->id); ?></td>
                <td><?php echo e($factura->producto_name); ?></td>
                <td> $ <?php echo e(number_format($factura->total, 2, '.', '')); ?></td>
                <td>
                    <?php $__currentLoopData = $factura->pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><a><?php echo e($pago->fecha_pago); ?></a>: <a>$<?php echo e(number_format($pago->monto, 2, '.', '')); ?></a></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td> $ <?php echo e(number_format($factura->montoPendiente(), 2, '.', '')); ?></td>
                <td>
                    <form action="<?php echo e(route('cuentas.pagarCompleto', $factura->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="referencia">Referencia (en caso de pago completo):</label>
                            <input type="text" name="referencia" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Pagar</button>
                    </form>
                    <th><a href="<?php echo e(route('cuentas.create', $factura->id)); ?>" class="btn btn-primary">Abono</a></th>
                    <th>
                        <form action="<?php echo e(route('cuentas.usarSaldo', $factura->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary">Usar Saldo</button>
                        </form>
                    </th>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="form-group row mt-3">
            <div class="col-sm-12 text-center">
                <a href="<?php echo e(route('cuentas.index')); ?>" class="btn btn-secondary btn-block">Regresar</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/cnc-detalle.blade.php ENDPATH**/ ?>