

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between;">
  <h1 style="margin: 0;">Petrollium</h1>
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
      <th>Estatus</th>
      <th>Date</th>
      <th>Amount</th>
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
        <td>
          <form action="<?php echo e(route('invoice.update.status', $invoice->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if($invoice->estatus == 'Pendiente'): ?>
              <select name="estatus">
                <option value="Pendiente">Pendiente</option>
                <option value="Completado">Completado</option>
              </select>
              <button type="submit">Actualizar</button>
            <?php else: ?>
              <?php echo e($invoice->estatus); ?>

            <?php endif; ?>
          </form>
        </td>
        <td><?php echo e($invoice->last_updated_time); ?></td>
        <td><?php echo e($invoice->total_amt); ?></td>
        <td>
          <a href="<?php echo e(route('invoice.remi', $invoice->NumeroFactura)); ?>">Crear Remicion</a>
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