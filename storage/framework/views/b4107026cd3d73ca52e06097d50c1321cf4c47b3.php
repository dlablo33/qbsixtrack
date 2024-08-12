<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Editar Usuario</h1>
        <form action="<?php echo e(route('cardknox.update', $settings->id)); ?>" method="POST">
  <?php echo csrf_field(); ?>
  <div class="form-group">
    <label for="name">Nombre:</label>
    <input type="text" name="name" id="name" class="form-control" value="<?php echo e($settings->name); ?>">
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" class="form-control" value="<?php echo e($settings->email); ?>">
  </div>
  <div class="form-group">
    <label for="tipo_usuario">Tipo de Usuario:</label>
    <select name="tipo_usuario" id="tipo_usuario" class="form-control">
      <option value="1" <?php echo e($settings->tipo_usuario === '1' ? 'selected' : ''); ?>>Desarrollador</option>
      <option value="2" <?php echo e($settings->tipo_usuario === '2' ? 'selected' : ''); ?>>Administrador</option>
      <option value="3" <?php echo e($settings->tipo_usuario === '3' ? 'selected' : ''); ?>>Lector</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
  <a href="<?php echo e(url()->previous()); ?>" class="btn btn-primary">Regresar</a>
</form>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cardknox/edit.blade.php ENDPATH**/ ?>