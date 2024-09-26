<!DOCTYPE html>
<html>
<head>
    <title>Detalles del Lote de Pago</title>
    <style>
        /* Estilos para el PDF */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Detalles del Lote de Pago #<?php echo e($lotePago->id); ?></h1>
    <h3>Pagos:</h3>
    <table>
    <thead>
        <tr>
            <th>ID Pago</th>
            <th>Monto</th>
            <th>Fecha de Pago</th>
            <th>Referencia</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $lotePago->pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($pago->id); ?></td>
                <td>$<?php echo e(number_format($pago->monto, 2)); ?></td>
                <td><?php echo e($pago->fecha_pago->format('d/m/Y')); ?></td> <!-- Aquí está la llamada a format() -->
                <td><?php echo e($pago->complemento); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

</body>
</html>
<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/pdf_detalle_lote.blade.php ENDPATH**/ ?>