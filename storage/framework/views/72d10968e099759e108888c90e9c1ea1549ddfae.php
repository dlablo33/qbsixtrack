

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between;">
  <h1 class="title">Factura Y Remisiones</h1>
  <div class="download-button-container">
  <form action="<?php echo e(route('facturas.transferLogisticaToFactura')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <button type="submit" class="btn btn-primary">Transferir a Factura</button>
    </form>
  </div>
</div>

<!-- Modal -->
<?php if(count($facturas) > 0): ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Codigo Facturacion</th>
        <th>Cliente ID</th>
        <th>Nombre Cliente</th>
        <th>Nombre Producto</th>
        <th>Producto ID</th>
        <th>Numero de Invoice</th>
        <th>Bol</th>
        <th>Trailer</th>
        <th>Estatus</th>
        <th>Cantidad</th>
        <th>Total</th>
        <th>Fecha Creación</th>
        <th>Codigo o Referencia</th>
        <th>Accion</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td><?php echo e($factura->id); ?></td>
          <td><?php echo e($factura->Numero_Factura); ?></td>
          <td><?php echo e($factura->cliente_id); ?></td>
          <td><?php echo e($factura->cliente_name); ?></td>
          <td><?php echo e($factura->producto_name); ?></td>
          <td><?php echo e($factura->producto_id); ?></td>
          <td><?php echo e($factura->Numero_Factura); ?></td>
          <td><?php echo e($factura->bol); ?></td>
          <td><?php echo e($factura->trailer); ?></td>
          <td><?php echo e($factura->estatus); ?></td>
          <td><?php echo e(number_format($factura->cantidad, 2, '.', ',')); ?></td>
          <td>$<?php echo e(number_format($factura->total, 2, '.', ',')); ?></td>
          <td><?php echo e($factura->fecha_create); ?></td>
          <td><?php echo e($factura->code_factura); ?></td>
          <td>
            <a href="<?php echo e(route('facturas.showPdf', ['id' => $factura->id])); ?>" class="btn btn-sm btn-info">Ver PDF</a>
            <!--<a href="" class="btn btn-sm btn-info" data-toggle="modal" data-target="#sendEmailModal-<?php echo e($factura->id); ?>">Enviar PDF</a>-->
            <?php if($factura->code_factura == null): ?>
              <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#linkInvoiceModal-<?php echo e($factura->id); ?>">Enlazar Factura</button>
            <?php endif; ?>
            <form action="<?php echo e(route('facturas.delete', ['id' => $factura->id])); ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta remisión?')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>

        
        <div class="modal fade" id="linkInvoiceModal-<?php echo e($factura->id); ?>" tabindex="-1" role="dialog" aria-labelledby="linkInvoiceModalLabel-<?php echo e($factura->id); ?>" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="linkInvoiceModalLabel-<?php echo e($factura->id); ?>">Enlazar Factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo e(route('facturas.link', ['id' => $factura->id])); ?>" method="POST">
                  <?php echo csrf_field(); ?>
                  <div class="form-group">
                    <label for="invoice_number">Número de Factura</label>
                    <input type="text" name="invoice_number" class="form-control" id="invoice_number" placeholder="Ingrese el número de factura">
                  </div>
                  <button type="submit" class="btn btn-primary">Enlazar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No Remisiones.</p>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/facturas/index.blade.php ENDPATH**/ ?>