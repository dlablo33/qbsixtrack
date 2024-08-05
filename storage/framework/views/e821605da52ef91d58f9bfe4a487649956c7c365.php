 <!-- Asegúrate de que la plantilla base sea la correcta -->

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Registrar Devolución</h1>
    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <form action="<?php echo e(route('refund-deposit')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="deposito_id" id="deposito_id" value="<?php echo e(old('deposito_id')); ?>">
        <input type="hidden" name="cliente_id" id="cliente_id" value="<?php echo e(old('cliente_id')); ?>">
        <input type="hidden" name="banco_id" id="banco_id" value="<?php echo e(old('banco_id')); ?>">
        
        <div class="form-group">
            <label for="banco">Banco</label>
            <select name="banco" id="banco" class="form-control" onchange="setBancoId(this)">
                <option value="">Selecciona un banco</option>
                <!-- Aquí deberías cargar dinámicamente los bancos disponibles -->
                <?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($banco->id); ?>" <?php echo e(old('banco') == $banco->id ? 'selected' : ''); ?>><?php echo e($banco->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" step="0.01" value="<?php echo e(old('cantidad')); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="moneda">Moneda</label>
            <select name="moneda" id="moneda" class="form-control" required>
                <option value="MXN" <?php echo e(old('moneda') == 'MXN' ? 'selected' : ''); ?>>MXN</option>
                <option value="USD" <?php echo e(old('moneda') == 'USD' ? 'selected' : ''); ?>>USD</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar Devolución</button>
    </form>
</div>

<script>
    function setBancoId(select) {
        var bancoIdField = document.getElementById('banco_id');
        bancoIdField.value = select.value;
    }
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/Admin/devoluciones.blade.php ENDPATH**/ ?>