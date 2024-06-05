@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" />

<style>
    .card {
        width: 100%;
        margin: 20px auto;
    }
    .cardTotal {
        width: 80%;
        margin: 20px auto;
    }
    .card-body {
        padding: 20px;
    }
    .form-control {
        margin-bottom: 10px;
    }
    .btn-info {
        width: 100%;
    }
</style>

<section class="content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info">
                    <div class="card-header text-center">
                        <h3 class="card-title">Crear </h3>
                    </div>

                    <form class="card-body" style="margin: 10px" action="{{ route('invoice.store') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label for="customer_id" class="col-sm-3 col-form-label">Selecciona Cliente</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="customer_id" id="customer_id" required>
                                    <option value="">-- Selecciona Cliente --</option>
                                    <?php
                                    $seenCustomers = []; // Array to store seen customer IDs
                                    foreach ($precios as $precio) {
                                        if (!in_array($precio->cliente_id, $seenCustomers)) {
                                            $seenCustomers[] = $precio->cliente_id;
                                            echo "<option value='" . $precio->cliente_id . "'>" . $precio->cliente_name . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="product_id" class="col-sm-3 col-form-label">Seleccion Producto</label>
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
                            <label for="invoice_date" class="col-sm-3 col-form-label">Fecha de Factura</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ date('Y-m-d') }}">
                            </div>

                            <label for="due_date" class="col-sm-3 col-form-label">Fecha de Vencimiento</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ date('Y-m-d') }}">
                            </div>
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

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

        // Crear una nueva fila en la tabla con los detalles del producto
        var newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${productName}</td>
            <td>${unitPrice}</td>
            <td><input type="number" class="form-control quantity" name="quantity[]" value="1" min="0" step="0.01"></td>
            <td class="total">${unitPrice}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove">Eliminar</button></td>
        `;

        document.getElementById('products_list').appendChild(newRow);
        updateTotals();

        // Añadir el evento de cambio a la cantidad
        newRow.querySelector('.quantity').addEventListener('input', function() {
            var quantity = parseFloat(this.value);
            var total = unitPrice * quantity;
            newRow.querySelector('.total').textContent = total.toFixed(2);
            updateTotals();
        });

        // Añadir el evento de eliminación
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

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#customer_id').select2();
        $('#product_id').select2();
        $('#price_id').select2();
    });
</script>

@endsection