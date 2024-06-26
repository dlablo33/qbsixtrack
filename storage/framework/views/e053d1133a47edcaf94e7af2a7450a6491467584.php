<?php $__env->startSection('content'); ?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Factura Detallada</title>
  <style>
    /* Estilos CSS */
    body {
      font-family: "Helvetica Neue", sans-serif;
      margin: 20px;
      padding: 0;
      background-color: #f2f2f2; /* Fondo gris claro */
    }

    .container {
      max-width: 800px; /* Limitar el ancho del contenedor para la respuesta */
      margin: 0 auto; /* Centrar el contenedor horizontalmente */
      padding: 20px;
      background-color: #fff; /* Fondo blanco para el contenido */
      border-radius: 5px; /* Esquinas redondeadas para un aspecto pulido */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra sutil para profundidad */
    }

    /* Agregar más estilos según sea necesario */

    /* Estilos específicos para la factura */
    .factura-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      border-bottom: 1px solid #ddd; /* Borde inferior para separación */
    }

    .factura-header h1 {
      font-size: 24px;
      margin: 0;
      color: #003c71; /* Color azul oscuro para el encabezado */
    }

    .company-info {
      text-align: right;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th,
    table td {
      border: 1px solid #ddd;
      padding: 8px;
      animation: fadeIn 0.5s ease-in-out; /* Animación de desvanecimiento sutil para tablas */
    }

    @keyframes  fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    table th {
      text-align: left;
      background-color: #f2f2f2;
      color: #333;
    }

    .button-container {
      text-align: right;
      margin-top: 20px;
    }

    .btn-regreso, .btn-pdf, .btn-email {
      background-color: #27ae60;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-left: 10px;
    }

    .btn-pdf {
      background-color: #e74c3c; /* Color rojo */
    }

    .btn-email {
      background-color: #3498db; /* Color azul */
    }
  </style>
</head>

<body>
  <div class="factura-header">
    <h1>Factura Detallada</h1>
    
  </div>

  <?php if($items->isNotEmpty()): ?>
    <?php
      $cliente = $items[0]->customer_name; // Obtener el valor de "Cliente" del primer elemento
      $facturacion = $items[0]->bill_line2; // Obtener el valor de "Facturación" del primer elemento
      $numeroFactura = $items[0]->NumeroFactura;
      $bol = $items[0]->bol;
      $trailer = $items[0]->Trailer;
      $fechaCreacion = $items[0]->create_time;
      $ultimaModificacion = $items[0]->last_updated_time;
    ?>

    <!-- Contenido de la factura -->

    <table>
      <tbody>
        <tr>
          <th colspan="5" style="text-align: right;">Cliente</th>
          <td><?php echo e($cliente); ?></td>
        </tr>
        <tr>
          <th colspan="5" style="text-align: right;">Facturación</th>
          <td><?php echo e($facturacion); ?></td>
        </tr>
      </tbody>
    </table>

    <!-- Otro contenido de la factura -->

    <table>
      <thead>
        <tr>
          <th>Número Factura</th>
          <th>BOL</th>
          <th>Trailer</th>
          <th>Fecha Creación</th>
          <th>Última Modificación</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo e($numeroFactura); ?></td>
          <td><?php echo e($bol); ?></td>
          <td><?php echo e($trailer); ?></td>
          <td><?php echo e($fechaCreacion); ?></td>
          <td><?php echo e($ultimaModificacion); ?></td>
        </tr>
      </tbody>
    </table>

    <!-- Otra tabla o contenido -->

    <table>
      <thead>
        <tr>
          <th>Servicio</th>
          <th>Tipo</th>
          <th>Cantidad</th>
          <th>Rate</th>
          <th>Monto Total</th>
          <th>Moneda</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr>
            <td><?php echo e($Item->item_names); ?></td>
            <td><?php echo e($Item->item_account_name); ?></td>
            <?php if($Item->item_names != null): ?>
            <td><?php echo e(number_format($Item->quantity, 2, '.', ',')); ?></td>
            <?php else: ?>
            <td><?php echo e($Item->quantity); ?></td>
            <?php endif; ?>
            <td><?php echo e($Item->unit_price); ?></td>
            <?php if($Item->item_names != null): ?>
              <td>$<?php echo e(number_format($Item->quantity * $Item->unit_price, 2, '.', '')); ?></td>
            <?php else: ?>
              <td>$<?php echo e($Item->total_amt); ?></td>
            <?php endif; ?>
            <td><?php echo e($Item->currency_value); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>

    <!-- Botón de regreso -->
    <!-- Botones para generar PDF y enviar correo -->
    <div class="button-container">
      <a href="<?php echo e(route('invoice.invoice-list')); ?>" class="btn-regreso">Regresar</a>
      <a href="<?php echo e(route('item.generatePDF', ['NumeroFactura' => $numeroFactura])); ?>" class="btn-pdf">Generar PDF</a>
      
      <!-- Formulario para enviar el correo -->
      <form action="<?php echo e(route('item.sendEmail', ['NumeroFactura' => $numeroFactura])); ?>" method="POST" style="display:inline;">
        <?php echo csrf_field(); ?>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <button type="submit" class="btn-email">Enviar por correo</button>
      </form>
    </div>
  <?php else: ?>
    <p>No se encontraron elementos para la factura especificada.</p>
  <?php endif; ?>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
  <script type="text/javascript">
    // Agrega aquí tu código JavaScript
  </script>
</body>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/show.blade.php ENDPATH**/ ?>