

<?php $__env->startSection('content'); ?>
<style>
    .form-check-input:checked {
        background-color: #0d6efd; /* Color del switch cuando está activo */
        border-color: #0d6efd;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25); /* Efecto de foco */
    }
    .form-check-input {
        width: 40px; /* Ancho del switch */
        height: 20px; /* Alto del switch */
        border-radius: 10px; /* Bordes redondeados */
    }
    /* Estilos para la tabla */
    .table-custom {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .table-custom thead {
        background-color: #343a40;
        color: #fff;
    }
    .table-custom th, .table-custom td {
        padding: 1rem;
        text-align: center;
    }
    .table-custom tbody tr:hover {
        background-color: #f1f1f1;
        transition: background-color 0.3s ease;
    }

    /* Estilos para los botones */
    .btn-container {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .btn-animated {
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    .btn-animated::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
        transition: transform 0.4s ease;
        z-index: 1;
    }
    .btn-animated:hover::after {
        transform: translateX(0);
    }
    .btn-animated:hover {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-animated.btn-success:hover {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-animated.btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    /* Estilos adicionales */
    .container {
        max-width: 1200px;
    }
</style>

<div class="container mt-4">
    <h1 class="display-6">Resumen de la Cuenta de la Empresa</h1>

    <!-- Botón para abrir ventana flotante (Modal) -->
    <button type="button" class="btn btn-primary btn-animated" data-toggle="modal" data-target="#convertCurrencyModal">
        Convertir Divisas
    </button>

    <!-- Modal para la conversión de divisas -->
    <div class="modal fade" id="convertCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="convertCurrencyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="convertCurrencyModalLabel">Conversión de Divisas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('empresa_cuenta.convertCurrency')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="banco">Banco</label>
                            <select class="form-control" name="banco" required>
                                <?php $__currentLoopData = $cuentas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cuenta->id); ?>"><?php echo e($cuenta->banco); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Monto</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="from_currency">De</label>
                            <select class="form-control" name="from_currency" required>
                                <option value="MXN">MXN</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="to_currency">A</label>
                            <select class="form-control" name="to_currency" required>
                                <option value="USD">USD</option>
                                <option value="MXN">MXN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exchange_rate">Tipo de Cambio</label>
                            <input type="number" step="0.01" class="form-control" name="exchange_rate" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-animated">Convertir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito o error -->
    <?php if(session('success')): ?>
        <div class="alert alert-success mt-3">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger mt-3">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Tabla de resumen -->
    <div class="table-responsive mt-4">
        <table class="table table-hover table-striped table-custom">
            <thead class="thead-custom">
                <tr>
                    <th>ID</th>
                    <th>Banco</th>
                    <th>Ingreso en MXN</th>
                    <th>Ingreso en USD</th>
                    <th>Comisión en MXN</th>
                    <th>Comisión en USD</th>
                    <th>Saldo Final en MXN</th>
                    <th>Saldo Final en USD</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cuentas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($cuenta->id); ?></td>
                        <td><?php echo e($cuenta->banco); ?></td>
                        <td>$<?php echo e(number_format($cuenta->ingreso_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->ingreso_usd, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->comision_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->comision_usd, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->saldo_final_mxn, 2, '.', ',')); ?></td>
                        <td>$<?php echo e(number_format($cuenta->saldo_final_usd, 2, '.', ',')); ?></td>
                        <td><?php echo e($cuenta->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-animated" onclick="openTransferModal(<?php echo e($cuenta->id); ?>, '<?php echo e($cuenta->banco); ?>', <?php echo e($cuenta->saldo_final_mxn); ?>, <?php echo e($cuenta->saldo_final_usd); ?>)">Transferir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Botones para acciones adicionales -->
    <div class="btn-container mt-4">
        <a href="<?php echo e(route('Admin.index')); ?>" class="btn btn-success btn-animated">Volver</a>
        <a href="<?php echo e(route('empresa_cuenta.showGastosForm')); ?>" class="btn btn-primary btn-animated">Registrar Gasto</a>
        <a href="<?php echo e(route('empresa_cuenta.listaGastos')); ?>" class="btn btn-info btn-animated">Listado de Gastos</a>
    </div>

    <!-- Modal para Transferir Fondos -->
    <div class="modal fade" id="transferFundsModal" tabindex="-1" role="dialog" aria-labelledby="transferFundsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transferFundsModalLabel">Transferir Fondos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="transferForm" method="POST" action="<?php echo e(route('empresa_cuenta.transferFunds')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="banco_origen" id="banco_origen">
    <input type="hidden" name="banco_destino" id="banco_destino">
    <input type="hidden" name="moneda" id="moneda">
    
    <div class="form-group">
        <label for="bancoOrigen">Banco Origen</label>
        <input type="text" class="form-control" id="bancoOrigen" readonly>
    </div>
    
    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="saldoActualMXN">Saldo Actual (MXN)</label>
            <input type="number" class="form-control" id="saldoActualMXN" readonly>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="saldoActualUSD">Saldo Actual (USD)</label>
            <input type="number" class="form-control" id="saldoActualUSD" readonly>
        </div>
    </div>
</div>

    
    <div class="form-group">
        <label for="cantidadTransferir">Cantidad a Transferir</label>
        <input type="float" class="form-control" name="cantidad" id="cantidadTransferir" required>
    </div>

    <div class="form-group">
        <label for="bancoDestino">Banco Destino</label>
        <select class="form-control" name="banco_destino" id="banco_destino_select" required>
            <option value="" disabled selected>Seleccione un banco</option>
            <?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($banco->id); ?>"><?php echo e($banco->banco); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="form-group row">
    <label for="currencySwitch" class="col-sm-3 col-form-label">Moneda</label>
    <div class="col-sm-9">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="currencySwitch">
            <label class="custom-control-label" for="currencySwitch">MXN / USD</label>
        </div>
    </div>
</div>

    <button type="submit" class="btn btn-success btn-animated">Transferir</button>
</form>



<!-- Modal para Transferir Fondos -->
<div class="modal fade" id="transferFundsModal" tabindex="-1" role="dialog" aria-labelledby="transferFundsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferFundsModalLabel">Transferir Fondos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Aquí va el formulario de transferencia -->
            </div>
        </div>
    </div>
</div>

<script>

    // Función para abrir el modal de transferencia de fondos
    function openTransferModal(bancoId, bancoNombre, saldoMXN, saldoUSD) {
        document.getElementById('banco_origen').value = bancoId;
        document.getElementById('bancoOrigen').value = bancoNombre;
        document.getElementById('saldoActualMXN').value = saldoMXN; // Muestra el saldo en MXN
        document.getElementById('saldoActualUSD').value = saldoUSD; // Muestra el saldo en USD
        document.getElementById('banco_destino').value = ''; // Limpia el valor por defecto
        document.getElementById('moneda').value = 'MXN'; // Cambia esto según la lógica que necesites

        // Mostrar el modal
        $('#transferFundsModal').modal('show');
    }

    function setMoneda(moneda) {
        document.getElementById('moneda').value = moneda; // Actualiza el campo oculto según la selección
    }
</script>



<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/empresa_cuenta/index.blade.php ENDPATH**/ ?>