<!DOCTYPE html>
<html>
<head>
    <title>Pago PDF</title>
    <style>
        /* Estilos para el PDF */
        table {
            width: 60%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reporte de Pagos</h1>

    <?php if($records->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>BOL</th>
                    <th>Litros</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Fecha de Creación</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($record->id); ?></td>
                        <td><?php echo e($record->bol_number); ?></td>
                        <td><?php echo e(number_format($record->litros, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($record->rate, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($record->total, 2, '.', ',')); ?></td>
                        <td><?php echo e($record->created_at); ?></td>
                        <td><?php echo e($record->estatus); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay registros para mostrar.</p>
    <?php endif; ?>
</body>
</html>




<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/pdf.blade.php ENDPATH**/ ?>