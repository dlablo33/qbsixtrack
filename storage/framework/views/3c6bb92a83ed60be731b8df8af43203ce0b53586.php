

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Molecula 2</h2>

<form action="<?php echo e(route('moleculas.migrateDataForMolecula2')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-primary">Migrar Datos para Molecula 2</button>
</form>

    <form method="POST" action="<?php echo e(route('moleculas.molecula2.process')); ?>">
        <?php echo csrf_field(); ?>
        <table id="example1" class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>BOL</th>
                    <th>Cliente</th>
                    <th>Destino</th>
                    <th>Transportista</th>
                    <th>Litros</th>
                    <th>Precio</th>
                    <th>Status</th>
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
                    <td><input type="checkbox" name="selected_records[]" value="<?php echo e($record->id); ?>"></td>
                    <td><?php echo e($record->bol); ?></td>
                    <td><?php echo e($clienteNombre); ?></td>
                    <td><?php echo e($destinoNombre); ?></td>
                    <td><?php echo e($record->linea); ?></td>
                    <td><?php echo e($record->litros); ?></td> 
                    <td><?php echo e($record->precio); ?></td>
                    <td><?php echo e($record->status); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Procesar Pagos</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/molecula2.blade.php ENDPATH**/ ?>