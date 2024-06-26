<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="margin: 0;">Invoice</h1>
    <div class="download-button-container">
        <form action="<?php echo e(route('invoice.karen')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="date" name="date" required>
            <button type="submit" class="btn btn-primary download-button">Inv. Diario</button>
        </form>
        <form action="<?php echo e(route('invoice.download')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary download-button">Descargar CSV</button>
        </form>
    </div>
</div>

<?php if(count($invoices) > 0): ?>
<div class="table-container">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Invoice ID</th>
        <th>bol</th>
        <th>Trailer</th>
        <th>Servicio</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><?php echo e($invoice->NumeroFactura); ?></td>
        <td><?php echo e($invoice->bol); ?></td>
        <td><?php echo e($invoice->Trailer); ?></td>
        <td><?php echo e($invoice->item_names); ?></td>
        <td><?php echo e($invoice->last_updated_time); ?></td>
        <td>$<?php echo e($invoice->total_amt); ?></td>
        <td>
          <a href="<?php echo e(route('invoice.show', $invoice->NumeroFactura)); ?>">Ver detalles</a>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
</div>
<?php else: ?>
  <p>No invoices found.</p>
<?php endif; ?>

<style>
.table-container {
  width: 100%;
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  font-size: 16px;
  text-align: left;
  table-layout: fixed; /* Asegura que las columnas respeten el ancho definido */
}

.table th, .table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
}

.table th {
  background-color: #f2f2f2;
  color: #333;
  font-weight: bold;
}

.table tbody tr:nth-of-type(even) {
  background-color: #f9f9f9;
}

.table tbody tr:hover {
  background-color: #f1f1f1;
}

.table tbody tr td a {
  color: #3498db;
  text-decoration: none;
}

.table tbody tr td a:hover {
  text-decoration: underline;
}

.download-button-container {
  text-align: right;
}

.download-button {
  background-color: #3498db;
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 5px;
}

.download-button:hover {
  background-color: #2980b9;
}

/* Ajustes específicos para las columnas */
.table th:nth-child(1),
.table td:nth-child(1) {
  width: 10%;
}

.table th:nth-child(2),
.table td:nth-child(2) {
  width: 15%;
}

.table th:nth-child(3),
.table td:nth-child(3) {
  width: 15%;
}

.table th:nth-child(4),
.table td:nth-child(4) {
  width: 15%; /* Ajusta el ancho de la columna de servicios */
}

.table th:nth-child(5),
.table td:nth-child(5) {
  width: 15%;
}

.table th:nth-child(6),
.table td:nth-child(6) {
  width: 10%;
}

.table th:nth-child(7),
.table td:nth-child(7) {
  width: 10%;
}
</style>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/invoice-list.blade.php ENDPATH**/ ?>