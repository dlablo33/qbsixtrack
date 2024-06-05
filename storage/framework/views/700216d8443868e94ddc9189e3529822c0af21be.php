

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header text-center">

                    <?php
                    $cliente = $items[0]->customer_name;
                    $facturacion = $items[0]->bill_line2;
                    $numeroFactura = $items[0]->NumeroFactura;
                    $bol = $items[0]->bol;
                    $trailer = $items[0]->Trailer;
                    $fechaCreacion = $items[0]->create_time;
                    $ultimaModificacion = $items[0]->last_updated_time;
                    ?>

                    <h3 class="card-title">Crear Cuenta por Cobrar</h3>
                </div>
                <form class="card-body" action="<?php echo e(route('invoice.store')); ?>" method="POST" style="padding: 20px;">
                    <?php echo csrf_field(); ?>

                    <div class="form-group row">
                        <label for="customer_id" class="col-sm-3 col-form-label">Selecciona Cliente</label>
                        <div class="col-sm-9">
                            <select class="form-control select2" name="customer_id" id="customer_id" required>
                                <option value="">-- Selecciona Cliente --</option>
                                <?php
                                $seenCustomers = [];
                                foreach ($clientes as $cliente) {
                                    if (!in_array($cliente->cliente_id, $seenCustomers)) {
                                        $seenCustomers[] = $cliente->cliente_id;
                                        echo "<option value='" . $cliente->cliente_id . "'>" . $cliente->cliente_name . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="product_id" class="col-sm-3 col-form-label">Selecciona Producto</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="product_id" id="product_id" required>
                                <option value="">-- Selecciona Producto --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="price_id" class="col-sm-3 col-form-label">Selecciona Precio</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="price_id" id="price_id" required>
                                <option value="">-- Selecciona Precio --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="invoice_date" class="col-sm-3 col-form-label">Fecha de remicion</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                        <label for="due_date" class="col-sm-3 col-form-label">Fecha de Vencimiento</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                    </div>

                    <!-- ===================================================================================================================================================================== -->

                    <input type="hidden" name="bol" value="<?php echo e($bol); ?>">
                    <input type="hidden" name="trailer" value="<?php echo e($trailer); ?>">
                    <input type="hidden" name="numeroFactura" value="<?php echo e($numeroFactura); ?>">

                    <!-- ===================================================================================================================================================================== -->

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Número Factura</th>
                                    <th>BOL</th>
                                    <th>Trailer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo e($numeroFactura); ?></td>
                                    <td><?php echo e($bol); ?></td>
                                    <td><?php echo e($trailer); ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(is_null($Item->item_names)): ?>
                                        <?php continue; ?>
                                    <?php endif; ?>
                                    <tr>
                                        <td><?php echo e($Item->item_names); ?></td>
                                        <td class="item-quantity"><?php echo e($Item->quantity); ?></td>
                                        <input type="hidden" name="quantity" value="<?php echo e($items[0]->quantity); ?>">
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-sm-12 text-center">
                            <button type="button" id="add_product_btn" class="btn btn-primary">Añadir Producto</button>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered" id="product_table">
                            <thead>
                                <tr>
                                    <th>Nombre del Producto</th>
                                    <th>Precio Unitario</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="products_list"></tbody>
                        </table>
                    </div>

                    <div class="card cardTotal">
                        <div class="card-header">
                            <h5 class="card-title">Total</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="total_before_discount" name="total_before_discount" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-info">Enviar</button>
                        </div>
                        <div class="form-group row mt-3">
                            <div class="col-sm-12 text-center">
                                <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary btn-block">Regresar</a>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('customer_id').addEventListener('change', function() {
        var customerId = this.value;
        fetch(`/get-products-by-customer/${customerId}`)
            .then(response => response.json())
            .then(data => {
                var productSelect = document.getElementById('product_id');
                productSelect.innerHTML = '<option value="">-- Selecciona Producto --</option>';
                var seenProducts = [];
                data.forEach(product => {
                    if (!seenProducts.includes(product.producto_id)) {
                        seenProducts.push(product.producto_id);
                        var option = document.createElement('option');
                        option.value = product.producto_id;
                        option.textContent = product.producto_name;
                        productSelect.appendChild(option);
                    }
                });
            })
            .catch(error => console.error('Error al obtener los productos:', error));
    });

    document.getElementById('product_id').addEventListener('change', function() {
        var productId = this.value;
        var customerId = document.getElementById('customer_id').value;
        fetch(`/get-prices-by-product-and-customer/${customerId}/${productId}`)
            .then(response => response.json())
            .then(data => {
                var priceSelect = document.getElementById('price_id');
                priceSelect.innerHTML = '<option value="">-- Selecciona Precio --</option>';
                data.forEach(price => {
                    var option = document.createElement('option');
                    option.value = price.id;
                    option.textContent = `${price.precio}`;
                    priceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al obtener los precios:', error));
    });

    document.getElementById('add_product_btn').addEventListener('click', function() {
        var productSelect = document.getElementById('product_id');
        var priceSelect = document.getElementById('price_id');
        var selectedProductOption = productSelect.options[productSelect.selectedIndex];
        var selectedPriceOption = priceSelect.options[priceSelect.selectedIndex];

        if (!selectedProductOption.value || !selectedPriceOption.value) {
            alert('Selecciona un producto y un precio.');
            return;
        }

        var productId = selectedProductOption.value;
        var productName = selectedProductOption.text;
        var unitPrice = parseFloat(selectedPriceOption.text);
        var quantity = parseFloat(document.querySelector('.item-quantity').textContent); // Get quantity from items

        // Crear una nueva fila en la tabla con los detalles del producto
        var newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${productName}</td>
            <td>${unitPrice.toFixed(2)}</td>
            <td>${quantity}</td>
            <td class="total">${(unitPrice * quantity).toFixed(2)}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove">Eliminar</button></td>
        `;

        document.getElementById('products_list').appendChild(newRow);
        updateTotals();

        newRow.querySelector('.remove').addEventListener('click', function() {
            newRow.remove();
            updateTotals();
        });
    });

    function updateTotals() {
        var total = 0;
        document.querySelectorAll('.total').forEach(function(totalCell) {
            total += parseFloat(totalCell.textContent);
        });
        document.getElementById('total_before_discount').value = total.toFixed(2);
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/remi.blade.php ENDPATH**/ ?>