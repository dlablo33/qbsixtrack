<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="container mt-4">
    <h3>Mejores Opciones según tu Presupuesto</h3>

    <p><strong>Presupuesto:</strong> $<?php echo e(number_format($budget, 2, '.', ',')); ?></p>

    <?php if(isset($bestCombination) && count($bestCombination) > 0): ?>
        <div class="alert alert-info mt-4">
            <strong>Total Calculado:</strong> $<?php echo e(number_format($bestTotal, 2, '.', ',')); ?>

        </div>
        
        <h2 class="mt-4">Mejores Opciones para Pagar</h2>
        <form id="paymentBatchForm" action="<?php echo e(route('moleculas.processPaymentBatch')); ?>" method="POST">
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

            <button type="submit" id="processPaymentButton" class="btn btn-success">Procesar Pago y Descargar PDF</button>
        </form>

        <a href="<?php echo e(route('moleculas.molecula1')); ?>" class="btn btn-primary mt-4">Volver a la Página de Opciones</a>

    <?php elseif(isset($bestCombination)): ?>
        <p>No se encontraron facturas dentro del presupuesto dado.</p>
    <?php endif; ?>
</div>

<script>
document.getElementById('paymentBatchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    var form = document.createElement('form');
    form.method = 'POST';
    form.action = this.action;

    // Agregar el token CSRF
    var csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('input[name="_token"]').value;
    form.appendChild(csrfToken);

    // Agregar los datos del formulario
    var formData = new FormData(this);
    for (var [key, value] of formData.entries()) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();

    // Redirigir después de un breve retraso para asegurar que el formulario se envíe primero
    setTimeout(function() {
        window.location.reload();
    }, 100);
});
</script><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/best_options.blade.php ENDPATH**/ ?>