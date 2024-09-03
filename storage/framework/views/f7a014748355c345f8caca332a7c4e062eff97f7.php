<!DOCTYPE html>
<html>
<head>
    <title>BoLs Pagados</title>
    <style>
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
    </style>
</head>
<body>
    <h1>BoLs Pagados</h1>
    <table>
        <thead>
            <tr>
                <th>BoL ID</th>
                <th>Precio Molecula 1</th>
                <th>Precio Molecula 3</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $bolsPagados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($bol->bol_id); ?></td>
                <td><?php echo e($bol->precio_molecula1); ?></td>
                <td><?php echo e($bol->precio_molecula3); ?></td>
                <td><?php echo e($bol->total); ?></td>
                <td><?php echo e($bol->status); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/bols_pagados.blade.php ENDPATH**/ ?>