

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h1>Invoices de QuickBooks</h1>

    <a href="<?php echo e(route('quickbooks.fetch')); ?>" class="btn btn-primary mb-3">Migrar Facturas</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha de Transacción</th>
                <th>Número de Documento</th>
                <th>Total</th>
                <th>Saldo</th>
                <th>Estado de Correo</th>
                <th>Nota Privada</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($invoice->Id); ?></td>
                    <td><?php echo e($invoice->CustomerRef->value); ?></td>
                    <td><?php echo e($invoice->TxnDate); ?></td>
                    <td><?php echo e($invoice->DocNumber); ?></td>
                    <td><?php echo e($invoice->TotalAmt); ?></td>
                    <td><?php echo e($invoice->Balance); ?></td>
                    <td><?php echo e($invoice->EmailStatus); ?></td>
                    <td><?php echo e($invoice->PrivateNote); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center">No hay facturas disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/quickbooks/index.blade.php ENDPATH**/ ?>