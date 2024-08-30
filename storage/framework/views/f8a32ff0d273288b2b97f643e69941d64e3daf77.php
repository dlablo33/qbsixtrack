

<?php $__env->startSection('content'); ?>

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

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Listado Aduanas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li class="breadcrumb-item active">Aduanas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Fila para los botones -->
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <form action="<?php echo e(route('aduana.migrateAll')); ?>" method="POST" class="d-inline-block">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success btn-animated btn-sm">Migrar Todos los BoLs</button>
        </form>
        <form action="<?php echo e(route('aduana.listado')); ?>" method="POST" class="d-inline-block ml-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-secondary btn-animated btn-sm">Agentes Aduanales</button>
        </form>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php if($aduanas->isEmpty()): ?>
                            <div class="alert alert-warning">
                                No se encontraron registros.
                            </div>
                        <?php else: ?>
                            <!-- Formulario para la asignación de agentes aduanales -->
                            <form id="saveAgentsForm" action="<?php echo e(route('aduana.saveAllAgents')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <!-- Campo para ingresar el tipo de cambio -->
                                <div class="form-group d-inline-block float-right mb-3">
                                    <label for="tipo_de_cambio_global" class="mr-2">Tipo de Cambio (Obligatorio):</label>
                                    <input type="float" name="tipo_de_cambio_global" class="form-control d-inline-block w-auto" placeholder="Ingresa el tipo de cambio" required>
                                </div>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>BoL</th>
                                            <th>Pedimento</th>
                                            <th>Transporte</th>
                                            <th>Numero de Pipa</th>
                                            <th>Seleccionar Agente</th>
                                            <th>Honorarios (MXN)</th>
                                            <th>Comisión (USD)</th>
                                            <th>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $aduanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aduana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                <?php if($aduana->status !== 'pagado'): ?>
                    <input type="checkbox" class="select-row" name="selected_bols[]" value="<?php echo e($aduana->id); ?>" data-honorarios="<?php echo e($aduana->honorarios); ?>" data-comision="<?php echo e($aduana->dls); ?>">
                <?php else: ?>
                    <!-- Mostrar un mensaje o ícono si ya está pagado -->
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                <?php endif; ?>
                                                </td>
                                                <td><?php echo e($aduana->bol_number); ?></td>
                                                <td><?php echo e($aduana->pedimento); ?></td>
                                                <td><?php echo e($aduana->linea); ?></td>
                                                <td><?php echo e($aduana->no_pipa); ?></td>
                                                <td>
                                                    <select name="agents[<?php echo e($aduana->id); ?>]" class="form-control" <?php echo e($aduana->id_agente ? 'disabled' : ''); ?>>
                                                        <option value="">Seleccione un agente</option>
                                                        <?php $__currentLoopData = $agentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($agente->id); ?>" <?php echo e($aduana->id_agente == $agente->id ? 'selected' : ''); ?>>
                                                                <?php echo e($agente->nombre); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td><?php echo e($aduana->honorarios ?? 'N/A'); ?></td>
                                                <td><?php echo e($aduana->dls ?? 'N/A'); ?></td>
                                                <td><?php echo e($aduana->status); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                                <!-- Botón para guardar todos los cambios -->
                                <button type="submit" class="btn btn-primary mt-3">Guardar Asignaciones</button>
                            </form>

                            <!-- Mostrar total de honorarios y comisión seleccionados -->
                            <div class="mt-3">
                                <strong>Total Honorarios Seleccionados: </strong><span id="totalSelectedHonorarios">0.00</span> MXN<br>
                                <strong>Total Comisión Seleccionada: </strong><span id="totalSelectedComision">0.00</span> USD
                            </div>

                            <!-- Formulario para procesar el pago de los seleccionados -->
                            <form id="payForm" action="<?php echo e(route('aduana.paySelected')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="selected_ids" id="selectedIds">
                                <button type="submit" class="btn btn-warning mt-3" id="payButton" disabled>Pagar Seleccionados</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Seleccionar/Deseleccionar todos los checkboxes
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.select-row');
        let totalHonorarios = 0;
        let totalComision = 0;
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            if (checkbox.checked) {
                totalHonorarios += parseFloat(checkbox.getAttribute('data-honorarios')) || 0;
                totalComision += parseFloat(checkbox.getAttribute('data-comision')) || 0;
            }
        });
        updateTotals(totalHonorarios, totalComision);
    });

    // Actualizar totales cuando se selecciona un checkbox individual
    document.querySelectorAll('.select-row').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let totalHonorarios = 0;
            let totalComision = 0;
            document.querySelectorAll('.select-row:checked').forEach(checkedBox => {
                totalHonorarios += parseFloat(checkedBox.getAttribute('data-honorarios')) || 0;
                totalComision += parseFloat(checkedBox.getAttribute('data-comision')) || 0;
            });
            updateTotals(totalHonorarios, totalComision);
        });
    });

    // Actualizar los totales en la vista
    function updateTotals(totalHonorarios, totalComision) {
        document.getElementById('totalSelectedHonorarios').textContent = totalHonorarios.toFixed(2);
        document.getElementById('totalSelectedComision').textContent = totalComision.toFixed(2);
        document.getElementById('payButton').disabled = totalHonorarios === 0 && totalComision === 0;
        
        // Actualizar los IDs seleccionados para el pago
        let selectedIds = [];
        document.querySelectorAll('.select-row:checked').forEach(checkbox => {
            selectedIds.push(checkbox.value);
        });
        document.getElementById('selectedIds').value = selectedIds.join(',');
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/aduana/index.blade.php ENDPATH**/ ?>