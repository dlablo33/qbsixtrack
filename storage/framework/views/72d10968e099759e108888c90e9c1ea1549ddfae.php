

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <h1 class="title" style="font-size: 24px; color: #333; font-weight: bold;">Factura Y Remisiones</h1>
  <div class="download-button-container">
    <form action="<?php echo e(route('facturas.transferLogisticaToFactura')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <button type="submit" class="btn btn-primary custom-btn">Transferir a Factura</button>
    </form>
  </div>
</div>

<!-- Modal -->
<?php if(count($facturas) > 0): ?>
  <table class="table table-striped custom-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Codigo Facturacion</th>
        <th>Nombre Cliente</th>
        <th>Nombre Producto</th>
        <th>Bol</th>
        <th>Precio</th>
        <th>Trailer</th>
        <th>Estatus</th>
        <th>Cantidad</th>
        <th>Precio Sin IVA</th>
        <th>Total</th>
        <th>Fecha Creación</th>
        <th>Pedimento</th>
        <th>Codigo o Referencia</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="custom-row">
          <td><?php echo e($factura->id); ?></td>
          <td><?php echo e($factura->Numero_Factura); ?></td>
          <td><?php echo e($factura->cliente_name); ?></td>
          <td><?php echo e($factura->producto_name); ?></td>
          <td><?php echo e($factura->bol); ?></td>
          <td>$<?php echo e(number_format($factura->precio, 2, '.', ',')); ?></td>
          <td><?php echo e($factura->trailer); ?></td>
          <td><?php echo e($factura->estatus); ?></td>
          <td><?php echo e(number_format($factura->cantidad, 2, '.', ',')); ?></td>
          <td>$<?php echo e(number_format((($factura->precio - 0.137205) / 1.16), 2, '.', ',')); ?></td>
          <td>$<?php echo e(number_format($factura->total, 2, '.', ',')); ?></td>
          <td><?php echo e($factura->fecha_create); ?></td>
          <td>
            <?php echo e($factura->pedimento); ?>

            <?php if($factura->pedimento): ?>
              <button class="btn btn-sm btn-success custom-copy-btn" onclick="copyToClipboard('<?php echo e($factura->pedimento); ?> pipa <?php echo e($factura->trailer); ?> bol <?php echo e($factura->bol); ?>')">Copiar</button>
            <?php endif; ?>
          </td>
          <td><?php echo e($factura->code_factura); ?></td>
          <td>
            <!-- Botón Ver PDF (Gris) -->
            <a href="<?php echo e(route('facturas.showPdf', ['id' => $factura->id])); ?>" class="btn btn-sm btn-secondary custom-pdf-btn">Ver PDF</a>
            <!-- Botón Enlazar Factura (Verde) -->
            <?php if($factura->Numero_Factura == null): ?>
              <button type="button" class="btn btn-sm btn-success custom-link-btn" data-toggle="modal" data-target="#linkInvoiceModal-<?php echo e($factura->id); ?>">Enlazar Factura</button>
            <?php endif; ?>
            <!-- Botón Eliminar (Rojo) -->
            <form action="<?php echo e(route('facturas.delete', ['id' => $factura->id])); ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta remisión?')">
              <?php echo csrf_field(); ?>
              <?php echo method_field('DELETE'); ?>
              <button type="submit" class="btn btn-sm btn-danger custom-delete-btn">Eliminar</button>
            </form>
          </td>
        </tr>

        <!-- Modal para enlazar la factura -->
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
                  <button type="submit" class="btn btn-success custom-link-btn">Enlazar</button>
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

<script>
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
      alert('Texto copiado al portapapeles: ' + text);
    }, function() {
      alert('Error al copiar el texto.');
    });
  }
</script>

<style>
  /* Estilos personalizados para los botones */
  .custom-btn {
    border: none;
    color: white;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .custom-pdf-btn {
    background-color: #6c757d; /* Gris */
  }

  .custom-pdf-btn:hover {
    background-color: #5a6268;
    transform: scale(1.05);
  }

  .custom-link-btn {
    background-color: #28a745; /* Verde */
  }

  .custom-link-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
  }

  .custom-delete-btn {
    background-color: #dc3545; /* Rojo */
  }

  .custom-delete-btn:hover {
    background-color: #c82333;
    transform: scale(1.05);
  }

  .custom-copy-btn {
    background-color: #28a745; /* Verde para el botón Copiar */
  }

  .custom-copy-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
  }

  /* Estilos personalizados para la tabla */
  .custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  .custom-table th, .custom-table td {
    padding: 12px;
    text-align: center;
  }

  .custom-table th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: bold;
  }

  .custom-table tr {
    transition: background-color 0.3s ease;
  }

  .custom-table tr:hover {
    background-color: #f1f1f1;
  }

  .custom-row td {
    border-bottom: 1px solid #ddd;
  }

  .custom-row td {
    transition: background-color 0.2s ease;
  }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/facturas/index.blade.php ENDPATH**/ ?>