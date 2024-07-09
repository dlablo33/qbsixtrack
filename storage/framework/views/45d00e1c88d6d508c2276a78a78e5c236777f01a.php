

<?php $__env->startSection('content'); ?>
<div >
    <h1>Logística</h1>
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <a href="<?php echo e(route('logistica.transferData')); ?>" id="transferButton" class="btn btn-primary mb-3">Sincronizar Datos</a>
    <div id="loading" style="display: none;">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>BOL</th>
                    <th>Order Number</th>
                    <th>Semana</th>
                    <th>Fecha</th>
                    <th>Linea</th>
                    <th>No Pipa</th>
                    <th>Cliente</th>
                    <th>Destino</th>                    
                    <th>Transportista</th>
                    <th >Estatus</th>
                    <th>Litros</th>
                    <th>Cruce</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $logis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($logi->bol); ?></td>
                        <td><?php echo e($logi->order_number); ?></td>
                        <td><?php echo e($logi->semana); ?></td>
                        <td><?php echo e($logi->fecha); ?></td>
                        <td><?php echo e($logi->linea); ?></td>
                        <td><?php echo e($logi->no_pipa); ?></td>
                        <td>
                            <form action="<?php echo e(route('logistica.asignar_cliente')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="logistica_id" value="<?php echo e($logi->id); ?>">
                                <select name="cliente" class="form-control" <?php echo e($logi->cliente ? 'disabled' : ''); ?>>
                                    <option value="">Selecciona un cliente</option>
                                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cliente->id); ?>" <?php echo e($logi->cliente == $cliente->id ? 'selected' : ''); ?>><?php echo e($cliente->NOMBRE_COMERCIAL); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                        </td>
                        <td>
                            <select name="destino" class="form-control" <?php echo e($logi->destino_id ? 'disabled' : ''); ?> <?php echo e(strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : ''); ?>>
                                <option value="">Selecciona un destino</option>
                                <?php $__currentLoopData = $destinos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destino): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($destino->id); ?>" <?php echo e($logi->destino_id == $destino->id ? 'selected' : ''); ?>><?php echo e($destino->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <option value="FOB" <?php echo e($logi->destino == 'FOB' ? 'selected' : ''); ?>>FOB</option>
                            </select>
                        </td>
                        <td>
                            <select name="transportista" class="form-control" <?php echo e($logi->transportista_id ? 'disabled' : ''); ?> <?php echo e(strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : ''); ?>>
                                <option value="">Selecciona un transportista</option>
                                <?php $__currentLoopData = $transportistas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transportista): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($transportista->id); ?>" <?php echo e($logi->transportista_id == $transportista->id ? 'selected' : ''); ?>><?php echo e($transportista->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>

                        <td>
                            <select name="status" class="form-control">
                                <option value="pendiente" <?php echo e($logi->status == 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                <option value="cargada" <?php echo e($logi->status == 'cargada' ? 'selected' : ''); ?>>Cargada</option>
                                <option value="descargada" <?php echo e($logi->status == 'descargada' ? 'selected' : ''); ?>>Descargada</option>
                            </select>
                        </td>
                        <td><?php echo e($logi->litros); ?></td>
                        <td>
                            <select name="cruce" class="form-control ">
                                <option value="verde" <?php echo e($logi->cruce == 'verde' ? 'selected' : ''); ?>>Verde</option>
                                <option value="rojo" <?php echo e($logi->cruce == 'rojo' ? 'selected' : ''); ?>>Rojo</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary mt-2">Guardar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/logistica/index.blade.php ENDPATH**/ ?>