

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between;">
  <h1 style="margin: 0;">Traking</h1>
  <div class="download-button-container">
  </div>
</div>

<?php if(count($invoices) > 0): ?>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Invoice ID</th>
      <th>bol</th>
      <th>Trailer</th>
      <th>Servicio</th>
      <th>Fecha</th>
      <th>Total</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if($invoice->item_names == 'PETROLEUM DISTILLATES'): ?>
      <tr>
        <td><?php echo e($invoice->NumeroFactura); ?></td>
        <td><?php echo e($invoice->bol); ?></td>
        <td><?php echo e($invoice->Trailer); ?></td>
        <td><?php echo e($invoice->item_names); ?></td>
        <td><?php echo e($invoice->last_updated_time); ?></td>
        <td>$<?php echo e(number_format(number_format($invoice->total_amt, 2, '.', ''), 0, ',', ',')); ?></td>
        <td>
          <a href="<?php echo e(route('invoice.remi', $invoice->NumeroFactura)); ?>">Crear Factura</a>
        </td>
      </tr>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>
<?php else: ?>
<p>No invoices found.</p>
<?php endif; ?>

<?php if(session('status')): ?>
  <div class="alert alert-success" role="alert">
    <?php echo e(session('status')); ?>

  </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/petrolio.blade.php ENDPATH**/ ?>