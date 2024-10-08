

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Molecula 3 - Pagos</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Botón de migrar BoLs -->
        <form action="<?php echo e(route('migrar.bols')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary mb-3">Migrar BoLs</button>
        </form>
        
        <!-- Botón para sincronizar los BOLs con las facturas -->
        <form action="<?php echo e(route('sync.bols.factura')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-warning mb-3">Sincronizar BOLs con Facturas</button>
        </form>

        <!-- Formulario de pago -->
        <form id="pago-form" action="<?php echo e(route('pagar.bols')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success mb-3" id="pagar-button">Pagar BoLs</button>

            <h3>Total seleccionado: <span id="total-suma">0.00</span></h3>

            <table id="example1" class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>BoL</th>
                        <th>Numero de Factura</th>
                        <th>Precio Molecula 1</th>
                        <th>Precio Molecula 3</th>
                        <th>Service Fee</th>
                        <th>Transportación Fee</th>
                        <th>Weight Controller</th>
                        <th>Total</th>
                        <th>Empresa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $bols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                        <td>
                                <?php if($bol->status == 'pagado'): ?>
                                    <!-- Mostrar palomita si el BoL ya está pagado -->
                                    <i class="fa fa-check" aria-hidden="true" style="color: green;"></i>
                                <?php else: ?>
                                    <!-- Mostrar checkbox si el BoL está pendiente -->
                                    <input type="checkbox" name="bol_ids[]" value="<?php echo e($bol->bol_id); ?>">
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($bol->bol_id); ?></td>
                            <td><?php echo e($bol->NumeroFactura); ?></td>
                            <td><?php echo e($bol->precio_molecula1); ?></td>
                            <td><?php echo e($bol->precio_molecula3); ?></td>
                            <td><?php echo e(number_format($bol->resultado, 2)); ?></td>
                            <td><?php echo e(number_format($bol->transportation_fee, 2)); ?></td>
                            <td><?php echo e(number_format($bol->weight_controller, 2)); ?></td>
                            <td><?php echo e(number_format($bol->total, 2)); ?></td>
                            <td><?php echo e($bol->customer_name); ?></td>
                            <td>
                                <?php if($bol->status === 'pagado'): ?>
                                    <span class="badge bg-success">Pagado</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pendiente</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        // Función para actualizar la suma total seleccionada
        function actualizarSumaTotal() {
            let totalSuma = 0;
            document.querySelectorAll('.bol-checkbox:checked').forEach(function(checkbox) {
                totalSuma += parseFloat(checkbox.dataset.total);
            });
            document.getElementById('total-suma').innerText = totalSuma.toFixed(2);
        }

        // Seleccionar todos los checkboxes
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.bol-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            actualizarSumaTotal(); // Actualizar la suma total
        });

        // Actualizar la suma cada vez que se selecciona/deselecciona un checkbox
        document.querySelectorAll('.bol-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', actualizarSumaTotal);
        });

        // Enviar formulario de pago
        document.getElementById('pago-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var form = this;
            var formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            }).then(response => response.blob())
            .then(blob => {
                // Descargar el PDF
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'boLs_pagados.pdf';
                link.click();

                // Refrescar la página después de la descarga
                window.location.reload();
            }).catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/moleculas/molecula3.blade.php ENDPATH**/ ?>