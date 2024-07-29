

<?php $__env->startSection('content'); ?>
<style>
    .modal-header {
        background-color: #007bff;
        color: white;
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.05);
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .form-group label {
        font-weight: bold;
    }
</style>

<div>
    <h1>Molecula 1</h1>

    <!-- Total de Facturas Pendientes -->
    <div class="alert alert-info mt-4">
        <strong>Total de Facturas con Estatus Pendiente:</strong> $<?php echo e(number_format($totalPendiente, 2, '.', ',')); ?>

    </div>

    <!-- Botón para abrir la ventana modal -->
    <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#calculateModal">
        Calcular Mejores Opciones
    </button>

    <!-- Ventana modal -->
    <div class="modal fade" id="calculateModal" tabindex="-1" role="dialog" aria-labelledby="calculateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calculateModalLabel">Calcular Mejores Opciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para introducir el presupuesto -->
                    <form id="calculateForm" action="<?php echo e(route('moleculas.calculateBestOptions')); ?>" method="POST" class="mt-2">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="budget">Presupuesto:</label>
                            <input type="number" step="0.01" class="form-control" id="budget" name="budget" placeholder="Introduce el presupuesto" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Calcular</button>
                    </form>

                    <!-- Resultados de las mejores opciones -->
                    <div id="results" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito y error -->
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Botón para migrar los datos -->
    <form action="<?php echo e(route('moleculas.migrateLogisticaToMolecula1')); ?>" method="POST" class="mt-2">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary">Migrar Datos a Molecula 1</button>
    </form>

    <?php if($molecula1Records->count() > 0): ?>
        <table id="example1" class="table table-striped">
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
                <?php $__currentLoopData = $molecula1Records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
    <?php else: ?>
        <p>No hay registros en Molecula 1.</p>
    <?php endif; ?>
</div>

<!-- Scripts para manejar el formulario y mostrar los resultados en la modal -->
<script>
document.getElementById('calculateForm').addEventListener('submit', function(event) {
    event.preventDefault();
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('results').innerHTML = data.html;
        } else {
            alert('Error al calcular las mejores opciones');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/molecula1.blade.php ENDPATH**/ ?>