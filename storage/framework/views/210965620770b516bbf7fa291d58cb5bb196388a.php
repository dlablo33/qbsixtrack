

<?php $__env->startSection('styles'); ?>
    <style>
        /* Importar una fuente empresarial */
        @import  url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        /* Estilos generales */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f2f2;
            color: #333;
        }

        /* Estilo del título principal */
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #0056b3;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 2.5rem;
        }

        /* Estilo de la tabla */
        .table {
            width: 85%;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            opacity: 0; /* Para la animación */
            transform: translateY(30px); /* Para la animación */
            animation: fadeInUp 0.6s forwards; /* Animación de aparición */
        }

        /* Estilo de las celdas de la tabla */
        .table th, .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }

        /* Estilo del encabezado de la tabla */
        .table thead {
            background-color: #0056b3;
            color: #fff;
            text-transform: uppercase;
        }

        /* Estilo para el hover de las filas */
        .table tbody tr:hover {
            background-color: #e9f5ff;
            transition: background-color 0.3s ease;
        }

        /* Estilo del botón */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            padding: 8px 16px;
            text-transform: uppercase;
            border-radius: 50px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-weight: 500;
        }

        /* Efecto hover del botón */
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Definición de la animación */
        @keyframes  fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1>Saldos</h1>

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
                    <a href="<?php echo e(route('cuentas.cnc-detalle', ['cliente_name' => $cliente->cliente_name])); ?>" class="btn btn-primary">Estados de cuentas</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
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