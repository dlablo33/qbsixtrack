@extends('layouts.app')

@section('content')
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
                <form class="card-body" action="{{ route('invoice.store') }}" method="POST" style="padding: 20px;">
                    @csrf

                    <div class="form-group row">
                        <label for="optional_invoice_code" class="col-sm-3 col-form-label">Factura</label>
                            <div class="col-sm-9">
                        <input type="checkbox" id="optional_invoice_code" name="optional_invoice_code">
                            </div>
                    </div>

                    <!-- ===================================================================================================================================================================== -->
                    
                    <div class="form-group row" id="num_fac_field" style="display:none;">
                        <label for="num_fac" class="col-sm-3 col-form-label">Número de Factura</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="num_fac" name="num_fac" min="0" step="1">
                        </div>
                    </div>

                    <!-- ===================================================================================================================================================================== -->
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
                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <label for="due_date" class="col-sm-3 col-form-label">Fecha de Vencimiento</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <!-- ===================================================================================================================================================================== -->

                    <input type="hidden" name="bol" value="{{ $bol }}">
                    <input type="hidden" name="trailer" value="{{ $trailer }}">
                    <input type="hidden" name="numeroFactura" value="{{ $numeroFactura }}">

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
                                    <td>{{ $numeroFactura }}</td>
                                    <td>{{ $bol }}</td>
                                    <td>{{ $trailer }}</td>
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
                                @foreach ($items as $Item)
                                    @if (is_null($Item->item_names))
                                        @continue
                                    @endif
                                    <tr>
                                        <td>{{ $Item->item_names }}</td>
                                        <td class="item-quantity">{{ number_format($Item->quantity, 2, '.', ',') }}</td>
                                        <input type="hidden" name="quantity" value="{{ $items[0]->quantity }}">
                                    </tr>
                                @endforeach
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
                                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">Regresar</a>
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
        // Manejo del checkbox para mostrar/ocultar el campo de número de factura
        document.getElementById('optional_invoice_code').addEventListener('change', function() {
            var numFacField = document.getElementById('num_fac_field');
            if (this.checked) {
                numFacField.style.display = 'block';
            } else {
                numFacField.style.display = 'none';
            }
        });

        // Cargar productos basados en el cliente seleccionado
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

        // Cargar precios basados en el producto y cliente seleccionados
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

        // Añadir producto a la tabla
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


@endsection