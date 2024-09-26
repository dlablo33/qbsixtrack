<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Cuenta de <?php echo e($cliente_name); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #666;
            margin-top: 30px;
        }

        p {
            font-size: 1.1em;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .saldo {
            font-size: 1.2em;
            font-weight: bold;
            color: #007BFF;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Estado de Cuenta de <?php echo e($cliente_name); ?></h1>
    <p class="saldo">Saldo a Favor: $<?php echo e(number_format($saldoAFavor, 2, '.', ',')); ?></p>

    <h2>Facturas</h2>
<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Número de Factura</th>
            <th>Monto Total</th>
            <th>Fecha de Creación</th>
            <th>Pagos</th>
            <th>Restante</th> <!-- Nueva columna para el monto restante -->
        </tr>
    </thead>
    <tbody>
        <?php
            $totalRestante = 0; // Variable para acumular los restantes
        ?>

        <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($factura->id); ?></td>
                <td><?php echo e($factura->Numero_Factura); ?></td>
                <td>$<?php echo e(number_format($factura->total, 2, '.', ',')); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($factura->fecha_create)->format('d/m/Y')); ?></td>
                <td>
                    <?php if($factura->pagos->isEmpty()): ?>
                        Sin pagos
                    <?php else: ?>
                        <ul>
                            <?php $__currentLoopData = $factura->pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>$<?php echo e(number_format($pago->monto, 2, '.', ',')); ?> - <?php echo e(\Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y')); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($factura->pagos->isEmpty()): ?>
                        $<?php echo e(number_format($factura->total, 2, '.', ',')); ?> <!-- Muestra el total si no hay pagos -->
                        <?php $totalRestante += $factura->total; ?>
                    <?php else: ?>
                        <?php
                            $pagosTotales = $factura->pagos->sum('monto');
                            $restante = $factura->total - $pagosTotales;
                            $totalRestante += $restante; // Acumula el restante
                        ?>
                        $<?php echo e(number_format($restante, 2, '.', ',')); ?> <!-- Muestra el restante -->
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<!-- Total de Restantes -->
<div style="margin-top: 20px; font-size: 1.5em; color: red; font-weight: bold;">
    Total Restante: $<?php echo e(number_format($totalRestante, 2, '.', ',')); ?>

</div>




    <footer>
        <p>Este es un documento generado automáticamente. Por favor, conservelo para sus registros.</p>
    </footer>
</body>
</html>

<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/estado_cuenta.blade.php ENDPATH**/ ?>