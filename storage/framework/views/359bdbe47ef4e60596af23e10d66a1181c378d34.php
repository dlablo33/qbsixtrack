

<?php $__env->startSection('content'); ?>
<div class="container">
    <style>
        .table-heading {
            color: #333;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 8px 16px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table th {
            background-color: #f2f2f2;
            color: #333;
        }

        .btn-regresar {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-regresar:hover {
            background-color: #0056b3;
            border-color: #004080;
        }
    </style>

    <div class="container-fluid">
        <a href="<?php echo e(url()->previous()); ?>" class="btn-regresar">Regresar</a>
        <?php if($precios->isNotEmpty()): ?>
            <h2>Historial de precios del cliente: <?php echo e($precios->first()->cliente_id); ?> - <?php echo e($precios->first()->cliente_name); ?></h2>

            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Numero de Producto</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Fecha de Actualizacion</th>
                        <th>Fecha de Vigencia</th>
                        <th>Semana</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $precios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $precio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($precio->id); ?></td>
                            <td><?php echo e($precio->producto_id); ?></td>
                            <td><?php echo e($precio->producto_name); ?></td>
                            <td><?php echo e($precio->precio); ?></td>
                            <td><?php echo e($precio->updated_at); ?></td>
                            <td><?php echo e($precio->fecha_vigencia); ?></td>
                            <td><?php echo e($precio->semana); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Cliente no encontrado.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/marchants/show.blade.php ENDPATH**/ ?>