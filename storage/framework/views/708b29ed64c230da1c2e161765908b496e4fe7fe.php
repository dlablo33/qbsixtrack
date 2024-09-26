

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Pagos para el Cliente: <?php echo e($cliente->NOMBRE_COMERCIAL); ?></h1>

    <?php if($factura): ?>
        <h2>Última Factura: #<?php echo e($factura->id); ?></h2>
        <h3>Total: $<?php echo e(number_format($factura->total, 2)); ?></h3>
    <?php else: ?>
        <p>No hay facturas asociadas a este cliente.</p>
    <?php endif; ?>

    <h4>Pagos Registrados:</h4>
    <?php if($pagos->isEmpty()): ?>
        <p>No hay pagos registrados para este cliente.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>Monto</th>
                    <th>Fecha de Pago</th>
                    <th>Referencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $ultimoComplemento = null; ?>
                <?php $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($pago->id); ?></td>
                        <td>$<?php echo e(number_format($pago->monto, 2)); ?></td>
                        <td><?php echo e($pago->fecha_pago->format('d/m/Y')); ?></td>
                        <td><?php echo e($pago->complemento); ?></td>
                        <td>
                            <?php if($pago->complemento !== $ultimoComplemento): ?>
                                <a href="<?php echo e(route('pagos.descargar.lote', $pago->lote_pago_id)); ?>" class="btn btn-primary">
                                    Descargar PDF
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $ultimoComplemento = $pago->complemento; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="<?php echo e(route('cuentas.index')); ?>" class="btn btn-secondary">Regresar</a>
    </div>
</div>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/pagos_por_cliente.blade.php ENDPATH**/ ?>