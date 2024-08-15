<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registro de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Registro de Compras</h2>
    <table>
        <thead>
            <tr>
                <th>BOL</th>
                <th>Codigo Transporte</th>
                <th>Cliente</th>
                <th>Destino</th>
                <th>Transportista</th>
                <th>Litros</th>
                <th>Precio</th>
                <th>Moneda</th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Encuentra el nombre del cliente basado en el ID
                    $clienteNombre = $clientes->where('id', $record->cliente)->first()->NOMBRE_COMERCIAL ?? 'Cliente no asignado';

                    // Encuentra el nombre del destino basado en el ID
                    $destinoNombre = $destinos->where('id', $record->destino_id)->first()->nombre ?? 'Destino no asignado';
                ?>
                    <tr>

                        <td><?php echo e($record->bol); ?></td>
                        <td><?php echo e($record->codeka); ?></td>
                        <td><?php echo e($clienteNombre); ?></td>
                        <td><?php echo e($destinoNombre); ?></td>
                        <td><?php echo e($record->linea); ?></td>
                        <td><?php echo e(number_format($record->litros, 2, '.', ',')); ?></td> 
                        <td>$<?php echo e(number_format($record->precio, 2, '.', ',')); ?></td>
                        <td><?php echo e($record->moneda); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/registro_compras_pdf.blade.php ENDPATH**/ ?>