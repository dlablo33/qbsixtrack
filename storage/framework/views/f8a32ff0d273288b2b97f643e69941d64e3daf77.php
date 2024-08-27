

<?php $__env->startSection('content'); ?>

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

<!-- Botón para migrar todos los BoLs -->
<form action="<?php echo e(route('aduana.migrateAll')); ?>" method="POST" class="mb-3">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-success">Migrar Todos los BoLs</button>
</form>

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
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>BoL</th>
                                            <th>Pedimento</th>
                                            <th>Transporte</th>
                                            <th>Numero de Pipa</th>
                                            <th>Seleccionar Agente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $aduanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aduana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($aduana->bol_number); ?></td>
                                                <td><?php echo e($aduana->pedimento); ?></td>
                                                <td><?php echo e($aduana->linea); ?></td>
                                                <td><?php echo e($aduana->no_pipa); ?></td>
                                                <td>
                                                    <select name="agentes[<?php echo e($aduana->id); ?>]" class="form-control">
                                                        <option value="">Seleccione un agente</option>
                                                        <?php $__currentLoopData = $agentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($agente->id); ?>" <?php echo e($aduana->id_agente == $agente->id ? 'selected' : ''); ?>>
                                                                <?php echo e($agente->nombre); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                                <!-- Botón para guardar todos los cambios -->
                                <button type="submit" class="btn btn-primary mt-2">Guardar Todos</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para ingresar el tipo de cambio -->
<?php if(session('showTipoCambioModal')): ?>
    <div class="modal fade" id="tipoCambioModal" tabindex="-1" aria-labelledby="tipoCambioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tipoCambioModalLabel">Asignar Tipo de Cambio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(route('aduana.assignTipoCambio')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tipo_de_cambio_global">Tipo de Cambio</label>
                            <input type="number" name="tipo_de_cambio_global" class="form-control" placeholder="Ingresa el tipo de cambio" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Tipo de Cambio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        <?php if(session('showTipoCambioModal')): ?>
            $('#tipoCambioModal').modal('show');
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/aduana/index.blade.php ENDPATH**/ ?>