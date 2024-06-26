<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Actualiza el precio</div>

                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('marchants.store')); ?>" id="priceUpdateForm">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label for="customer_id">Customer:</label>
                                <select name="customer_id" id="customer_id" class="form-control">
                                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cliente->CVE_CTE . '|' . $cliente->NOMBRE_COMERCIAL); ?>"><?php echo e($cliente->NOMBRE_COMERCIAL); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_id">Product:</label>
                                <select name="product_id" id="product_id" class="form-control">
                                    <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($producto->clv_producto . '|' . $producto->nombre); ?>"><?php echo e($producto->nombre); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="text" name="price" id="price" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="fecha_vigencia">Fecha de Vigencia:</label>
                                <input type="date" name="fecha_vigencia" id="fecha_vigencia" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary">Actualizacion</button>
                            <a href="<?php echo e(route('marchants.index')); ?>" class="btn btn-primary">Regresar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('priceUpdateForm').addEventListener('submit', function(event) {
            var fechaVigencia = new Date(document.getElementById('fecha_vigencia').value);
            var hoy = new Date();

            hoy.setHours(0, 0, 0, 0);  // Ignora la hora para la comparación de fechas

            if (fechaVigencia <= hoy) {
                alert('La fecha de vigencia debe ser posterior a la fecha de hoy.');
                event.preventDefault();
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/marchants/create.blade.php ENDPATH**/ ?>