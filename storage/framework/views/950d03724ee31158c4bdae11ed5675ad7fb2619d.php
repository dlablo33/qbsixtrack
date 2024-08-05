

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h1>Clientes y Saldos</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="<?php echo e(route('Admin.showDepositForm')); ?>" class="btn btn-primary">Depositar</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Saldo en MXN</th>
                <th>Saldo en USD</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($cliente->cliente); ?></td>
                    <td>$<?php echo e(number_format($cliente->saldo_mxn, 2, '.', ',')); ?></td>
                    <td>$<?php echo e(number_format($cliente->saldo_usd, 2, '.', ',')); ?></td>
                    <td>
                    <a href="<?php echo e(route('Admin.showClientBanks', ['id' => $cliente->id])); ?>" class="btn btn-info">Ver Bancos</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/Admin/index.blade.php ENDPATH**/ ?>