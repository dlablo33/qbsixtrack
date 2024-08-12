

<?php $__env->startSection('content'); ?>
<style>
    /* Estilos para la tabla */
    .table-custom {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .table-custom thead {
        background-color: #343a40;
        color: #fff;
    }
    .table-custom th, .table-custom td {
        padding: 1rem;
        text-align: center;
    }
    .table-custom tbody tr:hover {
        background-color: #f1f1f1;
        transition: background-color 0.3s ease;
    }

    /* Estilos para los botones */
    .btn-container {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }
    .btn-animated {
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    .btn-animated::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
        transition: transform 0.4s ease;
        z-index: 1;
    }
    .btn-animated:hover::after {
        transform: translateX(0);
    }
    .btn-animated:hover {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-animated.btn-success:hover {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-animated.btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    /* Estilos adicionales */
    .container {
        max-width: 1200px;
    }
</style>

<div class="container mt-4">
    <h1 class="display-6">Resumen de la Cuenta de la Empresa</h1>

    <div class="table-responsive">
        <table class="table table-hover table-striped table-custom">
            <thead class="thead-custom">
                <tr>
                    <th>ID</th>
                    <th>Banco</th>
                    <th>Ingreso en MXN</th>
                    <th>Ingreso en USD</th>
                    <th>Comisión en MXN</th>
                    <th>Comisión en USD</th>
                    <th>Saldo Final en MXN</th>
                    <th>Saldo Final en USD</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cuentas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($cuenta->id); ?></td>
                        <td><?php echo e($cuenta->banco); ?></td>
                        <td>$<?php echo e(number_format($cuenta->ingreso_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->ingreso_usd, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->comision_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->comision_usd, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->saldo_final_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->saldo_final_usd, 2, '.', ',')); ?></td>
                        <td><?php echo e($cuenta->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="btn-container mt-4">
        <a href="<?php echo e(route('Admin.index')); ?>" class="btn btn-success btn-animated">Volver</a>
        <a href="<?php echo e(route('empresa_cuenta.showGastosForm')); ?>" class="btn btn-primary btn-animated">Registrar Gasto</a>
        <a href="<?php echo e(route('empresa_cuenta.listaGastos')); ?>" class="btn btn-info btn-animated">Listado de Gastos</a>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/empresa_cuenta/index.blade.php ENDPATH**/ ?>