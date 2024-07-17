

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

    <form action="<?php echo e(route('logistica.guardar_todos')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-success mb-3">Guardar Todos los Cambios</button>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
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
                        <th>Estatus</th>
                        <th>Litros</th>
                        <th>Cruce</th>
                        <?php if(Auth::user()->tipo_usuario == 1): ?> 
                        <th>Precio</th>
                        <th>Total</th>
                        <th>Fecha Salida</th>
                        <th>Fecha Entrega</th>
                        <th>Fecha Descarga</th>
                        <?php endif; ?>
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
                            <td>
                                <input type="hidden" name="logistica[<?php echo e($logi->id); ?>][id]" value="<?php echo e($logi->id); ?>">
                                <select name="logistica[<?php echo e($logi->id); ?>][cliente]" class="form-control cliente-select" <?php echo e($logi->cliente ? 'disabled' : ''); ?>>
                                    <option value="">Selecciona un cliente</option>
                                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cliente->id); ?>" <?php echo e($logi->cliente == $cliente->id ? 'selected' : ''); ?>><?php echo e($cliente->NOMBRE_COMERCIAL); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td>
                                <select name="logistica[<?php echo e($logi->id); ?>][destino]" class="form-control destino-select" <?php echo e($logi->destino_id ? 'disabled' : ''); ?>>
                                    <option value="">Selecciona un destino</option>
                                    <?php $__currentLoopData = $destinos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destino): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($destino->id); ?>" <?php echo e($logi->destino_id == $destino->id ? 'selected' : ''); ?>><?php echo e($destino->nombre); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="5" <?php echo e($logi->destino_id == 5 ? 'selected' : ''); ?>>FOB</option>
                                </select>
                            </td>
                            <td>
                                <select name="logistica[<?php echo e($logi->id); ?>][transportista]" class="form-control transportista-select" <?php echo e($logi->transportista_id ? 'disabled' : ''); ?>>
                                    <option value="">Selecciona un transportista</option>
                                    <?php $__currentLoopData = $transportistas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transportista): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($transportista->id); ?>" <?php echo e($logi->transportista_id == $transportista->id ? 'selected' : ''); ?>><?php echo e($transportista->nombre); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td class="status">
                                <select name="logistica[<?php echo e($logi->id); ?>][status]" class="form-control status-select">
                                    <option value="pendiente" <?php echo e($logi->status == 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                    <option value="cargada" <?php echo e($logi->status == 'cargada' ? 'selected' : ''); ?>>Cargada</option>
                                    <option value="descargada" <?php echo e($logi->status == 'descargada' ? 'selected' : ''); ?>>Descargada</option>
                                </select>
                            </td>
                            <td class="litros" id="litros-<?php echo e($logi->id); ?>"><?php echo e($logi->litros); ?></td>
                            <td class="cruce">
                                <select name="logistica[<?php echo e($logi->id); ?>][cruce]" class="form-control cruce-select">
                                    <option value="rojo" <?php echo e($logi->cruce == 'rojo' ? 'selected' : ''); ?>>Rojo</option>
                                    <option value="verde" <?php echo e($logi->cruce == 'verde' ? 'selected' : ''); ?>>Verde</option>
                                </select>
                            </td>

                            <?php if(Auth::user()->tipo_usuario == 1): ?> 
                            <td>
                                <?php if($logi->cliente): ?>
                                    <select name="logistica[<?php echo e($logi->id); ?>][precio]" class="form-control precio-select" data-logi-id="<?php echo e($logi->id); ?>">
                                        <option value="">Selecciona un precio</option>
                                        <?php $__currentLoopData = $precios[$logi->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $precioId => $precio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($precio); ?>" <?php echo e($logi->precio == $precio ? 'selected' : ''); ?>><?php echo e($precio); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                <?php else: ?>
                                    <?php echo e($logi->precio); ?>

                                <?php endif; ?>
                            </td>
                            <td id="total-<?php echo e($logi->id); ?>">
                                <?php if(isset($totales[$logi->id])): ?>
                                    $<?php echo e($totales[$logi->id] !== null ? number_format($totales[$logi->id], 2) : ''); ?>

                                <?php endif; ?>
                            </td>

                            <td><input type="date" name="logistica[<?php echo e($logi->id); ?>][fecha_salida]" class="form-control" value="<?php echo e($logi->fecha_salida); ?>"></td>
                            <td><input type="date" name="logistica[<?php echo e($logi->id); ?>][fecha_entrega]" class="form-control" value="<?php echo e($logi->fecha_entrega); ?>"></td>
                            <td><input type="date" name="logistica[<?php echo e($logi->id); ?>][fecha_descarga]" class="form-control" value="<?php echo e($logi->fecha_descarga); ?>"></td>
                            <?php endif; ?>
                            
                            <td>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar Todos los Cambios</button>
    </form>
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
        font-size: 0.8rem; /* Reduce the font size */
    }

    .table th, .table td {
        white-space: nowrap;
        padding: 0.5rem;
    }

    .form-control {
        font-size: 0.8rem; /* Reduce the font size for form controls */
    }

    .status.pendiente {
        background-color: red;
        color: white;
    }

    .status.cargada {
        background-color: yellow;
        color: black;
    }

    .status.descargada {
        background-color: green;
        color: white;
    }

    .cruce.rojo {
        background-color: red;
        color: white;
    }

    .cruce.verde {
        background-color: green;
        color: white;
    }

    .btn {
        font-size: 0.8rem; /* Reduce the font size for buttons */
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

            const total = selectedPrice * litros;

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

            calculateTotal(select);
        });

        function updateCruceColors() {
            const cruceSelects = document.querySelectorAll('.cruce-select');
            cruceSelects.forEach(select => {
                const cell = select.closest('td');
                const value = select.value;

                cell.classList.remove('rojo', 'verde');
                if (value === 'rojo') {
                    cell.classList.add('rojo');
                } else if (value === 'verde') {
                    cell.classList.add('verde');
                }
            });
        }

        function updateStatusColors() {
            const statusCells = document.querySelectorAll('.status');
            statusCells.forEach(cell => {
                const value = cell.querySelector('select').value;

                cell.classList.remove('pendiente', 'cargada', 'descargada');
                if (value === 'pendiente') {
                    cell.classList.add('pendiente');
                } else if (value === 'cargada') {
                    cell.classList.add('cargada');
                } else if (value === 'descargada') {
                    cell.classList.add('descargada');
                }
            });
        }

        document.querySelectorAll('.cruce-select').forEach(select => {
            select.addEventListener('change', updateCruceColors);
        });

        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', updateStatusColors);
        });

        updateCruceColors();
        updateStatusColors();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/logistica/index.blade.php ENDPATH**/ ?>