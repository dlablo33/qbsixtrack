<div class="container mt-4">
    <h3>Mejores Opciones según tu Presupuesto</h3>

    <p><strong>Presupuesto:</strong> $<?php echo e(number_format($budget, 2, '.', ',')); ?></p>

    <?php if(isset($bestCombination) && count($bestCombination) > 0): ?>
        <div class="alert alert-info mt-4">
            <strong>Total Calculado:</strong> $<?php echo e(number_format($bestTotal, 2, '.', ',')); ?>

        </div>
        
        <h2 class="mt-4">Mejores Opciones para Pagar</h2>
        <form action="<?php echo e(route('moleculas.processPaymentBatch')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>BOL</th>
                            <th>Litros</th>
                            <th>Rate</th>
                            <th>Total</th>
                            <th>Fecha de Creación</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $bestCombination; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_records[]" value="<?php echo e($record->id); ?>">
                                </td>
                                <td><?php echo e($record->bol_number); ?></td>
                                <td><?php echo e(number_format($record->litros, 2, '.', ',')); ?></td>
                                <td>$<?php echo e(number_format($record->rate, 2, '.', ',')); ?></td>
                                <td>$<?php echo e(number_format($record->total, 2, '.', ',')); ?></td>
                                <td><?php echo e($record->created_at); ?></td>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success">Procesar Pago y Descargar PDF</button>
        </form>

    <?php elseif(isset($bestCombination)): ?>
        <p>No se encontraron facturas dentro del presupuesto dado.</p>
    <?php endif; ?>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        table-layout: fixed;
    }

    th, td {
        word-wrap: break-word;
        text-align: center;
    }

    thead th {
        background-color: #f8f9fa;
    }
</style>

<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/best_options.blade.php ENDPATH**/ ?>