<h1>Estado de Cuenta de Clientes</h1>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Saldo Restante</th>
            <th>Saldo a Favor</th>
        </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $deudasPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($cliente->cliente_name); ?></td>
            <td>$<?php echo e(number_format($cliente->saldoRestante, 2, '.', ',')); ?></td>
            <td>$<?php echo e(number_format($cliente->saldoAFavor, 2, '.', ',')); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/pdf.blade.php ENDPATH**/ ?>