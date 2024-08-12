

<?php $__env->startSection('content'); ?>
<style>
    h1.display-6 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .thead-custom {
        background-color: #007bff;
        color: #ffffff;
    }

    .table-hover tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-animated {
        position: relative;
        overflow: hidden;
    }

    .btn-animated::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.5s ease;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        z-index: 0;
    }

    .btn-animated:hover::after {
        transform: translate(-50%, -50%) scale(1);
    }

    .btn-animated span {
        position: relative;
        z-index: 1;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-container a {
        margin: 0 5px;
    }
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
                        <td></td>
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


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/admin/ingresos_devoluciones.blade.php ENDPATH**/ ?>