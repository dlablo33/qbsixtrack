<?php if(isset($bestCombination) && count($bestCombination) > 0): ?>
    <div class="alert alert-info mt-4">
        <strong>Total Calculado:</strong> $<?php echo e(number_format($bestTotal, 2, '.', ',')); ?>

    </div>
    
    <h2 class="mt-4">Mejores Opciones para Pagar</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>BOL</th>
                <th>Litros</th>
                <th>Rate</th>
                <th>Total</th>
                <th>Fecha de Creación</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $bestCombination; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
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
<?php elseif(isset($bestCombination)): ?>
    <p>No se encontraron facturas dentro del presupuesto dado.</p>
<?php endif; ?>
<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/best_options.blade.php ENDPATH**/ ?>