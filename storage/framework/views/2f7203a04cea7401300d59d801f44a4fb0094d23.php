

<?php $__env->startSection('styles'); ?>
    <style>
        /* Importar la fuente */
        @import  url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        /* Estilos generales */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.8s ease-in-out;
        }

        h1, h2 {
            text-align: center;
            color: #007bff;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        h2 {
            color: #28a745;
            font-weight: 500;
            font-size: 1.8rem;
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            animation: fadeInUp 0.6s ease-in-out;
        }

        .table thead th {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-bottom: 2px solid #0056b3;
            text-align: center;
        }

        .table tbody tr {
            border-bottom: 1px solid #dee2e6;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
        }

        .table td {
            padding: 15px;
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-transform: uppercase;
            border-radius: 50px;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            padding: 10px 20px;
            text-transform: uppercase;
            border-radius: 50px;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        @keyframes  fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes  fadeInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Facturas del Cliente: <?php echo e($cliente_name); ?></h1>
        <h2>Saldo a Favor: $<?php echo e(number_format($saldoAFavor, 2, '.', '')); ?></h2>

        <a href="<?php echo e(route('estado.cuenta.descargar', $cliente_name)); ?>" class="btn btn-primary mb-3">
            Descargar Estado de Cuenta
        </a>

<!-- Botón para ir a la sección de pagos -->
<a href="<?php echo e(route('cuentas.pagos_por_cliente', $cliente_name)); ?>" class="btn btn-primary mb-3">
    Ir a Pagos
</a>


        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <strong>Éxito:</strong> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <strong>Error:</strong> <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <form id="payment-form" method="POST" action="<?php echo e(route('pagos.procesar')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="cliente_name" value="<?php echo e($cliente_name); ?>">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Total</th>
                            <th>Abonos</th>
                            <th>Restante</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_facturas[]" value="<?php echo e($factura->id); ?>">
                                </td>
                                <td><?php echo e($factura->id); ?></td>
                                <td><?php echo e($factura->producto_name); ?></td>
                                <td>$<?php echo e(number_format($factura->total, 2, '.', '')); ?></td>
                                <td>
                                    <?php $__currentLoopData = $factura->pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p><strong><?php echo e($pago->fecha_pago); ?>:</strong> $<?php echo e(number_format($pago->monto, 2, '.', '')); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td>$<?php echo e(number_format($factura->montoPendiente(), 2, '.', '')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Procesar Pagos</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="<?php echo e(route('cuentas.index')); ?>" class="btn btn-secondary">Regresar</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.getElementById('payment-form').addEventListener('submit', function(event) {
            const checkboxes = document.querySelectorAll('input[name="selected_facturas[]"]:checked');
            if (checkboxes.length === 0) {
                event.preventDefault(); // Evita el envío del formulario
                alert('Por favor, selecciona al menos una factura.');
            }
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/cnc-detalle.blade.php ENDPATH**/ ?>