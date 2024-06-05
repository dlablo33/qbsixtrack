<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between;">
  <h1>Remisiones</h1>
  <div class="download-button-container">
    <form action="<?php echo e(route('invoice.create')); ?>" method="GET">
      <?php echo csrf_field(); ?>
      <button type="submit" class="btn btn-primary download-button">Crear Remision</button>
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
        <th>Numero de Factura</th>
        <th>Bol</th>
        <th>Trailer</th>
        <th>Cantidad</th>
        <th>Total</th>
        <th>Fecha Creación</th>
        <th>Fecha Vencimiento</th>
        <th></th> </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td><?php echo e($factura->id); ?></td>
          <td><?php echo e($factura->cliente_id); ?></td>
          <td><?php echo e($factura->cliente_name); ?></td>
          <td><?php echo e($factura->producto_name); ?></td>
          <td><?php echo e($factura->producto_id); ?></td>
          <td><?php echo e($factura->Numero_Factura); ?></td>
          <td><?php echo e($factura->bol); ?></td>
          <td><?php echo e($factura->trailer); ?></td>
          <td><?php echo e($factura->cantidad); ?></td>
          <td><?php echo e($factura->total); ?></td>
          <td><?php echo e($factura->fecha_create); ?></td>
          <td><?php echo e($factura->due_fecha); ?></td>
          <td>
            <td><a href="" class="btn btn-sm btn-info">Ver PDF</a></td>
            
           <td><a href="" class="btn btn-sm btn-info">Enviar PDF</a></td>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No Remisiones.</p>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/index.blade.php ENDPATH**/ ?>