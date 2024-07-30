

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h1 class="display-6">Historial de Depósitos para <?php echo e($cliente->cliente); ?></h1>

    <style>
        /* Estilos para el contenedor de la tabla y el título */
        .container {
            padding-top: 2rem;
        }

        h1.display-6 {
            margin-bottom: 1.5rem;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }

        /* Estilos para la tabla */
        .table {
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para el encabezado de la tabla */
        .table thead {
            background-color: #343a40;
            color: #ffffff;
        }

        .table thead th {
            text-align: center;
            padding: 1rem;
        }

        /* Estilos para el cuerpo de la tabla */
        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .table tbody td {
            text-align: center;
            padding: 1rem;
        }

        /* Estilos para los botones */
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            transition: background-color 0.3s, border-color 0.3s;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            transition: background-color 0.3s, border-color 0.3s;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>

    <div class="table-responsive mb-4">
        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Banco</th>
                    <th>Saldo en MXN</th>
                    <th>Saldo en USD</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $depositos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposito): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($deposito->banco ? $deposito->banco->banco : 'No asignado'); ?></td>
                        <td>$<?php echo e(number_format($deposito->saldo_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($deposito->saldo_usd, 2, '.', ',')); ?></td>
                        <td><?php echo e($deposito->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Botón para volver -->
    <a href="<?php echo e(route('Admin.showClientBanks', ['id' => $cliente->id])); ?>" class="btn btn-secondary mb-3">Volver</a>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/Admin/depositos_historial.blade.php ENDPATH**/ ?>