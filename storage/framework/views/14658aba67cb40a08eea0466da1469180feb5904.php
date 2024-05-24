 
 <?php $__env->startSection('content'); ?>
 <div style="display: flex; justify-content: space-between;">
    <h1 style="margin: 0;">Remisiones</h1>
     <div class="download-button-container">
        <form action="<?php echo e(route('invoice.create')); ?>" method="GET">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary download-button">Crear Remicion</button>
        </form>
    </div>
</div>
<?php if(count($facturas) > 0): ?>
  <table class="table table-striped">
  <thead>
        <tr>
            <th>ID</th>
            <th>Cliente ID</th>
            <th>Nombre Cliente</th>
            <th>Nombre Producto</th>
            <th>Producto ID</th>
            <th>Fecha Creación</th>
            <th>Fecha Vencimiento</th>
            <th>Total</th> </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($factura->id); ?></td>
        <td><?php echo e($factura->cliente_id); ?></td>
        <td><?php echo e($factura->cliente_name); ?></td>
        <td><?php echo e($factura->producto_name); ?></td>
        <td><?php echo e($factura->producto_id); ?></td>
        <td><?php echo e($factura->fecha_create); ?></td>
        <td><?php echo e($factura->due_fecha); ?></td>
        <td><?php echo e($factura->total); ?></td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No Remiciones.</p>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/index.blade.php ENDPATH**/ ?>