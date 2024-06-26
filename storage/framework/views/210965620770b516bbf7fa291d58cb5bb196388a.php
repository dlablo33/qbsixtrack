

<?php $__env->startSection('content'); ?>
    <h1>Saldos</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Saldo Restante</th>
                <th>Saldo a Favor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $deudasPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($cliente->cliente_name); ?></td>
                <td><?php echo e(number_format($cliente->saldoRestante, 2, '.', '')); ?></td>
                <td><?php echo e(number_format($cliente->saldoAFavor, 2, '.', '')); ?></td>
                <td>
                    <a href="<?php echo e(route('cuentas.cnc-detalle', ['cliente_name' => $cliente->cliente_name])); ?>" class="btn btn-primary">Estados de cuentas</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/index.blade.php ENDPATH**/ ?>