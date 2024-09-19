

<?php $__env->startSection('content'); ?>
<style>
    h1 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px;
    }

    .alert-success {
        margin-top: 20px;
        font-size: 16px;
    }

    .btn-primary, .btn-info {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-primary:hover, .btn-info:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .table {
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .table thead {
        background-color: #007bff;
        color: #ffffff;
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-container a {
        margin: 0 5px;
    }

    .total-deposit {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }
</style>

<div class="container mt-4">
    <h1>Clientes y Saldos</h1>

    <!-- Mostrar el total de depósitos del día -->
    <div class="total-deposit">
        Depósitos del día: 
        <USD:>MXN: $<?php echo e($todayDeposits['total_mxn']); ?> | USD: $<?php echo e($todayDeposits['total_usd']); ?></p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="<?php echo e(route('Admin.showDepositForm')); ?>" class="btn btn-primary">Depositar</a>
        <a href="<?php echo e(route('Admin.incomesAndRefunds')); ?>" class="btn btn-info">Historia Y Asignaciones</a>
    </div>

    <table id="example1" class="table table-bordered">
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