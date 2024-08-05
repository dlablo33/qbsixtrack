

<?php $__env->startSection('content'); ?>
<style>
    /* Estilos personalizados */
    h1 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px; /* Aumenta el espacio debajo del encabezado */
    }

    .form-group {
        margin-bottom: 1.5rem; /* Espacio entre los campos del formulario */
    }

    .form-control {
        border-radius: 0.25rem; /* Bordes redondeados en campos de formulario */
    }

    .btn-primary {
        background-color: #007bff; /* Color de fondo del botón */
        border-color: #007bff; /* Color del borde del botón */
        transition: background-color 0.3s ease, border-color 0.3s ease; /* Animación para el cambio de color */
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Color de fondo del botón al pasar el ratón */
        border-color: #004085; /* Color del borde del botón al pasar el ratón */
    }

    .btn-success {
        background-color: #28a745; /* Color de fondo del botón */
        border-color: #28a745; /* Color del borde del botón */
        transition: background-color 0.3s ease, border-color 0.3s ease; /* Animación para el cambio de color */
    }

    .btn-success:hover {
        background-color: #218838; /* Color de fondo del botón al pasar el ratón */
        border-color: #1e7e34; /* Color del borde del botón al pasar el ratón */
    }

    .btn-animated {
        position: relative;
        overflow: hidden;
    }

    .btn-animated::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.5s ease;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        z-index: 0;
    }

    .btn-animated:hover::after {
        transform: translate(-50%, -50%) scale(1);
    }

    .btn-animated span {
        position: relative;
        z-index: 1;
    }

    .button-group {
        display: flex;
        gap: 10px; /* Espacio entre los botones */
    }
</style>

<div class="container mt-4">
    <h1>Registrar Depósito</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('Admin.processDeposit')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="cliente">Cliente</label>
            <select name="cliente" id="cliente" class="form-control" required>
                <option value="">Seleccione un cliente</option>
                <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cliente->id); ?>"><?php echo e($cliente->cliente); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="form-group">
            <label for="banco">Banco</label>
            <select name="banco" id="banco" class="form-control" required>
                <option value="">Seleccione un banco</option>
                <?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($banco->id); ?>"><?php echo e($banco->banco); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="float" name="cantidad" id="cantidad" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="moneda">Moneda</label>
            <select name="moneda" id="moneda" class="form-control" required>
                <option value="MXN">MXN</option>
                <option value="USD">USD</option>
            </select>
        </div>

        <div class="button-group mt-3">
            <button type="submit" class="btn btn-primary btn-animated">Registrar Depósito</button>
            <a href="<?php echo e(route('Admin.index')); ?>" class="btn btn-success btn-animated">Volver</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/Admin/depositar.blade.php ENDPATH**/ ?>