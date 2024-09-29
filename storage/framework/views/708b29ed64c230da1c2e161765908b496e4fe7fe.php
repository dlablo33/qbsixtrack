

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Depósitos en la parte superior derecha -->
        <div class="col-md-4 offset-md-8 text-right" id="depositos-section">
            <h4>Depósitos del Cliente: <?php echo e($cliente->NOMBRE_COMERCIAL); ?></h4>
            <div class="depositos-content">
                <h5>Depósitos Registrados</h5>
                <?php if($depositos->isEmpty()): ?>
                    <p>No hay depósitos registrados para este cliente.</p>
                <?php else: ?>
                    <ul class="list-unstyled">
                        <?php $__currentLoopData = $depositos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposito): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="deposito-item">
                                <strong>ID:</strong> <?php echo e($deposito->id); ?> |
                                <strong>Banco:</strong> <?php echo e($deposito->banco->banco ?? 'N/A'); ?> |
                                <strong>Saldo MXN:</strong> $<?php echo e(number_format($deposito->saldo_mxn, 2)); ?> |
                                <strong>Saldo USD:</strong> $<?php echo e(number_format($deposito->saldo_usd, 2)); ?> |
                                <strong>Fecha de Registro:</strong> <?php echo e($deposito->created_at->format('d/m/Y')); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabla de Pagos centrada -->
        <div class="col-md-10 offset-md-1">
            <div class="table-responsive" id="pagos-section">
                <h4 class="text-center">Pagos para el Cliente: <?php echo e($cliente->NOMBRE_COMERCIAL); ?></h4>
                <?php if($factura): ?>
                    <h5 class="text-center">Última Factura: #<?php echo e($factura->id); ?> | Total: $<?php echo e(number_format($factura->total, 2)); ?></h5>
                <?php else: ?>
                    <p class="text-center">No hay facturas asociadas a este cliente.</p>
                <?php endif; ?>

                <h5 class="text-center mt-3">Pagos Registrados:</h5>
                <?php if($pagos->isEmpty()): ?>
                    <p class="text-center">No hay pagos registrados para este cliente.</p>
                <?php else: ?>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>ID Pago</th>
                                <th>Monto</th>
                                <th>Fecha de Pago</th>
                                <th>Referencia</th>
                                
                                <th>Banco Proveniente</th>
                                <th>Número de Cuenta</th>
                                <th>Complemento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $ultimoComplemento = null; ?>
                            <?php $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($pago->id); ?></td>
                                    <td>$<?php echo e(number_format($pago->monto, 2)); ?></td>
                                    <td><?php echo e($pago->fecha_pago->format('d/m/Y')); ?></td>
                                    <td><?php echo e($pago->complemento); ?></td>
                                    <td>
                                        <input type="text" name="banco_proveniente[]" class="form-control form-control-sm" placeholder="Banco Proveniente" required>
                                    </td>
                                    <td>
                                        <input type="text" name="numero_cuenta[]" class="form-control form-control-sm" placeholder="Número de Cuenta" required>
                                    </td>
                                    <td>
                                        <?php echo e($pago->serial_baunche ?? 'N/A'); ?>

                                    </td>
                                    <td>
                                        <?php if($pago->complemento !== $ultimoComplemento): ?>
                                            <a href="<?php echo e(route('pagos.descargar.lote', $pago->lote_pago_id)); ?>" class="btn btn-primary btn-sm">
                                                Descargar PDF
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $ultimoComplemento = $pago->complemento; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Botones Centrados -->
    <div class="text-center mt-4">
        <form action="<?php echo e(route('pagos.asignar_datos')); ?>" method="POST" class="d-inline-block">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success btn-lg mx-2">Guardar Cambios</button>
        </form>
        <a href="<?php echo e(route('cuentas.index')); ?>" class="btn btn-secondary btn-lg mx-2">Regresar</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pagosSection = document.getElementById('pagos-section');
            pagosSection.style.opacity = 0;
            setTimeout(() => {
                pagosSection.style.opacity = 1;
                pagosSection.style.transition = 'opacity 0.5s ease-in-out';
            }, 200);
        });
    </script>
<?php $__env->stopSection(); ?>

<style>
    /* Fuentes y Estilos Generales */
    body {
        font-family: 'Lato', sans-serif;
    }

    h4, h5 {
        font-family: 'Montserrat', sans-serif;
    }

    .container-fluid {
        padding: 15px;
    }

    /* Depósitos en la parte superior derecha */
    #depositos-section {
        margin-top: 20px;
    }

    .depositos-content {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }

    .deposito-item {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #ffffff;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .deposito-item:hover {
        background-color: #f1f1f1;
    }

    /* Tabla centrada y responsiva */
    #pagos-section {
        margin-top: 30px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        background-color: #fff;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    /* Botones centrados */
    .btn {
        font-family: 'Montserrat', sans-serif;
        font-weight: bold;
    }

    .btn-lg {
        padding: 10px 25px;
        font-size: 18px;
    }

    /* Media Queries para Responsividad */
    @media (max-width: 768px) {
        .col-md-4 {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 576px) {
        .deposito-item {
            font-size: 14px;
        }

        .btn-lg {
            font-size: 16px;
            padding: 8px 20px;
        }
    }
</style>





<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/pagos_por_cliente.blade.php ENDPATH**/ ?>