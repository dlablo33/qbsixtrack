

<?php $__env->startSection('content'); ?>
<style>
    /* Estilos personalizados */
    h1.display-6 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px; /* Aumenta el espacio debajo del encabezado */
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        background-color: #f8f9fa; /* Color de fondo de la tabla */
        border-radius: 8px; /* Bordes redondeados */
        overflow: hidden; /* Para que los bordes redondeados se apliquen */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
    }

    .thead-custom {
        background-color: #007bff; /* Color de fondo del encabezado */
        color: #ffffff; /* Color del texto en el encabezado */
    }

    .table-hover tbody tr:hover {
        background-color: #e9ecef; /* Color de fondo al pasar el ratón */
    }

    .btn-success {
        background-color: #28a745; /* Color de fondo del botón */
        border-color: #28a745; /* Color del borde del botón */
    }

    .btn-success:hover {
        background-color: #218838; /* Color de fondo del botón al pasar el ratón */
        border-color: #1e7e34; /* Color del borde del botón al pasar el ratón */
        transition: background-color 0.3s ease, border-color 0.3s ease; /* Animación para el cambio de color */
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
        justify-content: space-between; /* Espacio entre los botones */
        margin-top: 20px;
    }

    .btn-container a {
        margin: 0 5px; /* Espacio entre botones */
    }
</style>

<div class="container mt-4">
    <h1 class="display-6">Detalles de Bancos para <?php echo e($cliente->cliente); ?></h1>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="thead-custom">
                <tr>
                    <th>Banco</th>
                    <th>Saldo Total en MXN</th>
                    <th>Saldo Total en USD</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $totales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($total['banco']); ?></td>
                        <td>$<?php echo e(number_format($total['saldo_mxn'], 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($total['saldo_usd'], 2, '.', ',')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="btn-container">
        <a href="<?php echo e(route('Admin.index')); ?>" class="btn btn-success btn-animated">Volver</a>
        <a href="<?php echo e(route('Admin.showDepositHistory', ['id' => $cliente->id])); ?>" class="btn btn-success btn-animated">Ver Historial de Depósitos</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/Admin/showClientBanks.blade.php ENDPATH**/ ?>