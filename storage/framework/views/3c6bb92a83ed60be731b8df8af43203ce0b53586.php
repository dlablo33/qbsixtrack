

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Molecula 2</h2>

    <!-- Botón para migrar los datos a Molecula 2 -->
    <form action="<?php echo e(route('moleculas.migrateDataForMolecula2')); ?>" method="POST" class="mb-4 text-center">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary btn-lg">Migrar Datos para Molecula 2</button>
    </form>

     <!-- Botón para sincronizar los BOLs con las facturas en Molecula 2 -->
     <form action="<?php echo e(route('sync.bols.factura2')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary mb-3">Sincronizar BOLs con Facturas en Molecula 2</button>
        </form>

    <form id="process-payments-form" method="POST" action="<?php echo e(route('pagos.procesar')); ?>">
        <?php echo csrf_field(); ?>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>BOL</th>
                        <th>Numero de Facturacion</th>
                        <th>Codigo Transporte</th>
                        <th>Cliente</th>
                        <th>Destino</th>
                        <th>Transportista</th>
                        <th>Litros</th>
                        <th>Precio</th>
                        <th>Moneda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Encuentra el nombre del cliente basado en el ID
                    $clienteNombre = $clientes->where('id', $record->cliente)->first()->NOMBRE_COMERCIAL ?? 'Cliente no asignado';

                    // Encuentra el nombre del destino basado en el ID
                    $destinoNombre = $destinos->where('id', $record->destino_id)->first()->nombre ?? 'Destino no asignado';
                ?>
                    <tr>
                        <td class="text-center">
                            <?php if($record->status !== 'pagado'): ?>
                                <input type="checkbox" name="selected_records[]" value="<?php echo e($record->id); ?>" data-price="<?php echo e($record->precio); ?>" data-moneda="<?php echo e($record->moneda); ?>">
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($record->bol); ?></td>
                        <td><?php echo e($record->NumeroFactura); ?></td>
                        <td>
                        <?php if($record->status !== 'pagado'): ?>
                            <input type="text" name="codeka[<?php echo e($record->id); ?>]" class="form-control" placeholder="Ingrese Codigo">
                        <?php else: ?>
                            <?php echo e($record->codeka); ?>

                        <?php endif; ?>
                        </td>
                        <td><?php echo e($clienteNombre); ?></td>
                        <td><?php echo e($destinoNombre); ?></td>
                        <td><?php echo e($record->linea); ?></td>
                        <td><?php echo e(number_format($record->litros, 2, '.', ',')); ?></td> 
                        <td>$<?php echo e(number_format($record->precio, 2, '.', ',')); ?></td>
                        <td><?php echo e($record->moneda); ?></td>
                        <td><?php echo e($record->status); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <h4>Total en MXN: $<span id="total-mxn">0.00</span></h4>
            <h4>Total en USD: $<span id="total-usd">0.00</span></h4>
            <button type="submit" class="btn btn-success btn-lg">Procesar Pagos</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('process-payments-form');
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const totalMXNElement = document.getElementById('total-mxn');
        const totalUSDElement = document.getElementById('total-usd');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                let totalMXN = 0;
                let totalUSD = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const price = parseFloat(cb.getAttribute('data-price'));
                        const moneda = cb.getAttribute('data-moneda');

                        if (moneda === 'MXN') {
                            totalMXN += price;
                        } else if (moneda === 'USD') {
                            totalUSD += price;
                        }
                    }
                });

                totalMXNElement.textContent = totalMXN.toFixed(2);
                totalUSDElement.textContent = totalUSD.toFixed(2);
            });
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    // Descargar el PDF
                    const a = document.createElement('a');
                    a.href = data.url;
                    a.download = 'moleculas_registro_compras.pdf';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();

                    // Redirigir después de la descarga
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route('moleculas.molecula2')); ?>';
                    }, 2000); // Esperar 2 segundos antes de redirigir
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar el pago.');
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<style>
    .table-responsive {
        margin-top: 20px;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #343a40;
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .text-center {
        text-align: center;
    }

    .btn-lg {
        padding: 10px 20px;
        font-size: 1.25rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .table td input[type="checkbox"] {
        transform: scale(1.5);
    }
</style>



<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/molecula2.blade.php ENDPATH**/ ?>