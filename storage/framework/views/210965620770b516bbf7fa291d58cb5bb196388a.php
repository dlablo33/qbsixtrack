

<?php $__env->startSection('styles'); ?>
    <style>
        /* Estilos personalizados */
        h1 {
            text-align: center;
            color: #0056b3;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 2.5rem;
        }

        .table {
            width: 85%;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s forwards;
        }

        .table th, .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }

        .table thead {
            background-color: #0056b3;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            text-transform: uppercase;
            border-radius: 50px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        @keyframes  fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1>Estado de Cuenta de Clientes</h1>

    <a href="<?php echo e(route('estado_cuenta.descargar_pdf')); ?>" class="btn btn-primary">Descargar Estado de Cuenta</a>


    <table id="example1" class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Saldo Restante</th>
                <th>Saldo a Favor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $deudasPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($cliente->cliente_name); ?></td>
                <td>$<?php echo e(number_format($cliente->saldoRestante, 2, '.', ',')); ?></td>
                <td>$<?php echo e(number_format($cliente->saldoAFavor, 2, '.', ',')); ?></td>
                <td>
                    <a href="<?php echo e(route('cuentas.cnc-detalle', ['cliente_name' => $cliente->cliente_name])); ?>" class="btn btn-primary">Estado de Cuenta</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('#example1').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cuentas/index.blade.php ENDPATH**/ ?>