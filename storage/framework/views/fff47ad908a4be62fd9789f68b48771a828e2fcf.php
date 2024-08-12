<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remisión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 20px;
        }
        .invoice-info {
            position: absolute;
            top: 20px;
            right: 20px;
            border: 1px solid #000;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .client-info, .product-info, .total-info, .note {
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .total-info .left-align {
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Remisión</h1>
                <p><strong>Folio:</strong> <?php echo e($factura->id); ?></p>
                <p><strong>Fecha:</strong> <?php echo e(\Carbon\Carbon::parse($factura->fecha_create)->format('d/m/Y')); ?></p>
                <p><strong>Bol:</strong> <?php echo e($factura->bol); ?> </p>
            <div class="invoice-info">
                <center><p><strong>SIXTRACK INTERNACIONAL</strong></p>
                <p><strong>RFC:</strong> SIN2105269CA</p>
                <p>Av. Eugenio Garza Sada 4478-A Col. Las Brisas, Monterrey,</p>
                <p>Nuevo León México, CP: 64780</p></center>
            </div>
            <div class="client-info">
                <p></p>
                <hr>
                <a></a>
                <center><h2>Datos del Cliente</h2></center>
                <p><strong>Cliente:</strong> <?php echo e($factura->cliente_name); ?></p>
                <p><strong>RFC:</strong> <?php echo e($factura->customer ? $factura->customer->RFC : 'N/A'); ?></p>
            </div>
            <hr>
            <div class="product-info">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Unidad SAT</th>
                            <th>Pipa</th>
                            <th>Clave Prod/Servicio</th>
                            <th>Descripción</th>
                            <th>Valor unitario</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo e(number_format($factura->cantidad, 2, '.', ',')); ?></td>
                            <td>LITROS</td>
                            <td>LTS</td>
                            <td><?php echo e($factura->trailer); ?></td>
                            <td><?php echo e($factura->producto_id); ?>-<?php echo e($factura->producto_name); ?></td>
                            <td><?php echo e($factura->producto_name); ?></td>
                            <td>$<?php echo e(number_format(number_format($factura->total / $factura->cantidad, 2, '.', ''), 0, ',', ',')); ?></td>
                            <td>$<?php echo e(number_format(number_format($factura->total, 2, '.', ''), 0, ',', ',')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <P></P>
            <hr>
            <div class="total-info">
                <p><strong>Total con Letra:</strong> <?php echo e(convertirNumeroALetras($factura->total)); ?></p>
                <p class="left-align"><strong>Total:</strong> $<?php echo e(number_format(number_format($factura->total, 2, '.', ''), 0, ',', ',')); ?></p>
            </div>
            <hr>
            <div class="note">
                <p>El presente documento es de carácter informativo por lo que no cuenta con validez oficial ni con valor ni alcance legal en su contenido solo genera el derecho de cobro al proveedor quien se obliga posteriormente a expedir el comprobante fiscal correspondiente.</p>
            </div>
        </div>
        
    </div>
</div>
</body>
</html>


<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/remi-pdf.blade.php ENDPATH**/ ?>