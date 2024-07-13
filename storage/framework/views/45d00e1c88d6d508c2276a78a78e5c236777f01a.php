

<?php $__env->startSection('content'); ?>
<div>
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
                    <th class="width:20%">Estatus</th>
                    <th>Litros</th>
                    <th class="width:24%">Cruce</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $logis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($logi->bol); ?></td>
                        <td><?php echo e($logi->order_number); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($logi->fecha)->weekOfYear); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($logi->fecha)->format('d-m-Y')); ?></td>
                        <td><?php echo e($logi->linea); ?></td>
                        <td><?php echo e($logi->no_pipa); ?></td>
                        <!-- Logistica de clientes-->
                        <td>
                            <form action="<?php echo e(route('logistica.asignar_cliente')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="logistica_id" value="<?php echo e($logi->id); ?>">
                                <select name="cliente" class="form-control" <?php echo e($logi->cliente ? 'disabled' : ''); ?> onchange="this.form.submit()">
                                    <option value="">Selecciona un cliente</option>
                                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cliente->id); ?>" <?php echo e($logi->cliente == $cliente->id ? 'selected' : ''); ?>><?php echo e($cliente->NOMBRE_COMERCIAL); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                        </td>
                        <!-- Logistica de destino -->
                        <td>
                            <select name="destino" class="form-control" <?php echo e($logi->destino_id ? 'disabled' : ''); ?> <?php echo e(strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : ''); ?>>
                                <option value="">Selecciona un destino</option>
                                <?php $__currentLoopData = $destinos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destino): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($destino->id); ?>" <?php echo e($logi->destino_id == $destino->id ? 'selected' : ''); ?>><?php echo e($destino->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <option value="FOB" <?php echo e($logi->destino == 'FOB' ? 'selected' : ''); ?>>FOB</option>
                            </select>
                        </td>
                        <!-- Logistica de Transportes-->
                        <td>
                            <select name="transportista" class="form-control" <?php echo e($logi->transportista_id ? 'disabled' : ''); ?> <?php echo e(strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : ''); ?>>
                                <option value="">Selecciona un transportista</option>
                                <?php $__currentLoopData = $transportistas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transportista): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($transportista->id); ?>" <?php echo e($logi->transportista_id == $transportista->id ? 'selected' : ''); ?>><?php echo e($transportista->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>
                        <!-- Logistica de Estatus -->
                        <td>
                            <select name="status" class="form-control">
                                <option value="pendiente" <?php echo e($logi->status == 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                <option value="cargada" <?php echo e($logi->status == 'cargada' ? 'selected' : ''); ?>>Cargada</option>
                                <option value="descargada" <?php echo e($logi->status == 'descargada' ? 'selected' : ''); ?>>Descargada</option>
                            </select>
                        </td>
                        <!-- Logistica de litros-->
                        <td class="litros" id="litros-<?php echo e($logi->id); ?>"><?php echo e($logi->litros); ?></td>
                        <!-- Logistica de Cruce-->
                        <td id="cruceCell">
                            <select id="cruceSelect" name="cruce" class="form-control">
                                <option value="rojo" <?php echo e($logi->cruce == 'rojo' ? 'selected' : ''); ?> data-color="red">Rojo</option>
                                <option value="verde" <?php echo e($logi->cruce == 'verde' ? 'selected' : ''); ?> data-color="green">Verde</option>
                            </select>
                        </td>
                        <!-- Logistica de Precio -->
                        <td>
                            <?php if($logi->cliente): ?>
                                <select name="precio" class="form-control precio-select" data-logi-id="<?php echo e($logi->id); ?>" onchange="this.form.submit()">
                                    <option value="">Selecciona un precio</option>
                                    <?php $__currentLoopData = $precios[$logi->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $precioId => $precio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($precio); ?>" <?php echo e($logi->precio == $precio ? 'selected' : ''); ?>><?php echo e($precio); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php else: ?>
                                <?php echo e($logi->precio); ?>

                            <?php endif; ?>
                        </td>
                        <!-- Total -->
                        <td id="total-<?php echo e($logi->id); ?>">
                             <?php if(isset($totales[$logi->id])): ?>
                                 $<?php echo e($totales[$logi->id] !== null ? number_format($totales[$logi->id], 2) : ''); ?>

                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Guardar</button>
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

    .form-control option[data-color="green"] {
        background-color: green;
        color: white;
    }

    .form-control option[data-color="red"] {
        background-color: red;
        color: white;
    }

    td.green {
        background-color: green;
        color: white;
    }

    td.red {
        background-color: red;
        color: white;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const precioSelects = document.querySelectorAll('.precio-select');

        function calculateTotal(precioSelect) {
    const logiId = precioSelect.getAttribute('data-logi-id');
    const selectedPrice = parseFloat(precioSelect.value) || 0;
    const litros = parseFloat(document.getElementById(`litros-${logiId}`).innerText) || 0;
    
    console.log('Logi ID:', logiId);
    console.log('Selected Price:', selectedPrice);
    console.log('Litros:', litros);

    const total = selectedPrice * litros;

    console.log('Total:', total);

    if (!isNaN(total) && total > 0) {
        document.getElementById(`total-${logiId}`).innerText = total.toFixed(2);
    } else {
        document.getElementById(`total-${logiId}`).innerText = '';
    }
}


        precioSelects.forEach(select => {
            select.addEventListener('change', function () {
                calculateTotal(select);
            });

            // Initial calculation
            calculateTotal(select);
        });

        const cruceSelects = document.querySelectorAll('#cruceSelect');
        cruceSelects.forEach(select => {
            select.addEventListener('change', function () {
                const cell = select.closest('#cruceCell');
                const selectedOption = select.options[select.selectedIndex];
                const color = selectedOption.getAttribute('data-color');

                cell.classList.remove('green', 'red');
                if (color === 'green') {
                    cell.classList.add('green');
                } else if (color === 'red') {
                    cell.classList.add('red');
                }
            });

            // Initial color setting
            const cell = select.closest('#cruceCell');
            const selectedOption = select.options[select.selectedIndex];
            const color = selectedOption.getAttribute('data-color');
            if (color === 'green') {
                cell.classList.add('green');
            } else if (color === 'red') {
                cell.classList.add('red');
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/logistica/index.blade.php ENDPATH**/ ?>