<!-- resources/views/pago_pdf.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Pago PDF</title>
    <style>
        /* Agrega estilos CSS aquí */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Detalles del Pago</h1>
    <p>Cliente: <?php echo e($pago->cliente->NOMBRE_COMERCIAL); ?></p>
    <p>Monto: $<?php echo e(number_format($pago->monto, 2)); ?></p>
    <p>Fecha de Pago: <?php echo e($pago->fecha_pago); ?></p>
    <p>Referencia: <?php echo e($pago->referencia); ?></p>
    <p>Complemento: <?php echo e($pago->complemento); ?></p>

    <h2>Información Adicional</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Factura</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $pago->facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($factura->id); ?></td>
                    <td><?php echo e($factura->numero); ?></td>
                    <td>$<?php echo e(number_format($factura->total, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/pago_pdf.blade.php ENDPATH**/ ?>