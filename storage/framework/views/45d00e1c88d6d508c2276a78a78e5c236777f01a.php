

<?php echo $__env->yieldPushContent('styles'); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->yieldContent('content'); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>

<div class="display">
    <h1>Logística</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Botón para sincronizar datos generales -->
    <a href="<?php echo e(route('logistica.transferData')); ?>" id="transferButton" class="btn btn-primary mb-3">Sincronizar Datos</a>

    <!-- Spinner de carga oculto -->
    <div id="loading" style="display: none;">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Formulario para guardar todos los cambios -->
    <form action="<?php echo e(route('logistica.guardar_todos')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-success mb-3">Guardar Todos los Cambios</button>

        <!-- Tabla responsiva -->
        <div class="table-responsive">
            <table id="example1" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th>BOL</th>
                        <th>Semana</th>
                        <th>Fecha</th>
                        <th>Linea</th>
                        <th>No Pipa</th>
                        <th>Cliente</th>
                        <th>Destino</th>
                        <th>Estatus</th>
                        <th>Cruce</th>
                        <th>Fecha Salida</th>
                        <th>Fecha Entrega</th>
                        <th>Fecha Descarga</th>
                        <th>Pedimento</th>
                        <?php if(Auth::user()->tipo_usuario == 1): ?>
                            <th>Precio</th>
                            <th>Total</th>
                        <?php endif; ?>
                        <th>Acciones</th> <!-- Nueva columna de acciones -->
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $logis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($logi->bol); ?></td>
                            <td class="text-center"><?php echo e($logi->fecha->weekOfYear); ?></td>
                            <td><?php echo e($logi->fecha->format('d-m-Y')); ?></td>
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
                            <td class="status">
                                <select name="logistica[<?php echo e($logi->id); ?>][status]" class="form-control status-select" style="background-color: 
                                <?php echo e($logi->status == 'pendiente' ? '#f8d7da' : 
                                ($logi->status == 'cargada' ? '#fff3cd' : 
                                ($logi->status == 'descargada' ? '#d4edda' : '#fff'))); ?>;">
                                <option value="pendiente" <?php echo e($logi->status == 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                <option value="cargada" <?php echo e($logi->status == 'cargada' ? 'selected' : ''); ?>>Cargada</option>
                                <option value="descargada" <?php echo e($logi->status == 'descargada' ? 'selected' : ''); ?>>Descargada</option>
                                </select>
                            </td>
                            <td class="cruce">
                                <select name="logistica[<?php echo e($logi->id); ?>][cruce]" class="form-control cruce-select" style="background-color: <?php echo e($logi->cruce == 'rojo' ? '#f8d7da' : ($logi->cruce == 'verde' ? '#d4edda' : '#fff')); ?>;">
                                <option value="rojo" <?php echo e($logi->cruce == 'rojo' ? 'selected' : ''); ?>>Rojo</option>
                                <option value="verde" <?php echo e($logi->cruce == 'verde' ? 'selected' : ''); ?>>Verde</option>
                                </select>
                            </td>

                            <td><input type="date" name="logistica[<?php echo e($logi->id); ?>][fecha_salida]" class="form-control" value="<?php echo e($logi->fecha_salida); ?>"></td>
                            <td><input type="date" name="logistica[<?php echo e($logi->id); ?>][fecha_entrega]" class="form-control" value="<?php echo e($logi->fecha_entrega); ?>"></td>
                            <td><input type="date" name="logistica[<?php echo e($logi->id); ?>][fecha_descarga]" class="form-control" value="<?php echo e($logi->fecha_descarga); ?>"></td>
                            <td><input type="text" name="logistica[<?php echo e($logi->id); ?>][pedimento]" class="form-control" value="<?php echo e($logi->pedimento); ?>"></td>

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
                                        $<?php echo e(number_format($totales[$logi->id], 2)); ?>

                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <!-- Botón en la columna de acciones -->
                            <td>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Botón para guardar todos los cambios -->
        <button type="submit" class="btn btn-success mt-3">Guardar Todos los Cambios</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Estilos personalizados y animaciones */
    .container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-top: 20px;
        animation: fadeIn 0.8s ease-in-out;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #007bff;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 2.2rem;
        position: relative;
        animation: slideInLeft 0.6s ease-out;
    }

    .btn-primary, .btn-info {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover, .btn-info:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .table {
        width: 100%;
        margin-top: 20px;
        background-color: #f8f9fa;
    }

    th {
        background-color: #007bff;
        color: white;
        padding: 10px;
    }

    td {
        padding: 8px;
        vertical-align: middle;
    }

    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }

    /* Animaciones */
    @keyframes  fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    @keyframes  slideInLeft {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(0); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inicializa DataTables
        var table = $('#example1').DataTable();

        // Cargar datos guardados desde localStorage para todos los campos
        function cargarDatos() {
            $('input, select').each(function() {
                var valorGuardado = localStorage.getItem($(this).attr('name'));
                if (valorGuardado) {
                    $(this).val(valorGuardado);
                }
            });
        }

        // Guardar datos al cambiar algún campo
        function guardarDatos() {
            $('input, select').on('change', function() {
                localStorage.setItem($(this).attr('name'), $(this).val());
            });
        }

        // Ejecutar la función para cargar los datos
        cargarDatos();

        // Ejecutar la función para guardar los cambios
        guardarDatos();

        // Evento DataTables: recargar datos al cambiar de página, búsqueda, etc.
        table.on('draw', function() {
            cargarDatos(); // Recargar los datos al redibujar la tabla
        });

        // Sincronizar datos generales
        $('#transferButton').on('click', function(e) {
            e.preventDefault();
            $('#loading').show(); // Mostrar el spinner de carga
            $.ajax({
                url: $(this).attr('href'),
                method: 'GET',
                success: function() {
                    $('#loading').hide(); // Ocultar el spinner de carga
                    alert('Datos sincronizados correctamente.');
                },
                error: function() {
                    $('#loading').hide(); // Ocultar el spinner de carga
                    alert('Error al sincronizar los datos.');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/logistica/index.blade.php ENDPATH**/ ?>