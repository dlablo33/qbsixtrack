@extends('layouts.master')

@section('content')
    <!-- Add Bootstrap styles for better design -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" />

    <style>
        /* Additional CSS for styling enhancements */
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

        /* Add more styling as needed */
        .form-control {
            margin-bottom: 10px;
        }

        .btn-info {
            width: 100%;
        }
    </style>

    <section class="content">
        <div class="container">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="card card-info">
                        <div class="card-header text-center">
                            <h3 class="card-title">Edit Invoice</h3>
                        </div>
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form class="card-body" style="margin: 10px" action="{{ route('invoice.update', $invoice->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Customer Selection -->
                            <div class="form-group row">
                                <label for="customer_id" class="col-sm-3 col-form-label">Select Customer</label>
                                <div class="col-sm-9">
                                    @if(isset($customers) && count($customers) > 0)
                                        <select class="form-control" id="customer_id" required="" disabled>
                                            <option value="">-- Select Customer --</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" @if($invoice->customer_id == $customer->id) selected @endif>{{ $customer->name }} ({{ $customer->email }})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <p>No customers available. Please add customers first.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Selection -->
                            <div class="form-group row">
                                <label for="product_name" class="col-sm-3 col-form-label">Select Products</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Search for products" autocomplete="off">
                                    <input type="hidden" class="form-control" id="customer_id" name="customer_id" value="{{$invoice->customer_id}}" autocomplete="off">
                                    <div id="product_suggestions"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="invoice_date" class="col-sm-3 col-form-label">Invoice Date</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="invoice_date" name="invoice_date" placeholder="" value="{{$invoice->invoice_date}}" autocomplete="off" disabled>
                                </div>
                                <label for="due_date" class="col-sm-3 col-form-label">Due Date</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="due_date" name="due_date" placeholder="" autocomplete="off" value="{{$invoice->due_date}}">
                                </div>
                            </div>
                            <div class="form-group row">

                                <label for="terms" class="col-sm-3 col-form-label">Terms</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="terms" id="terms" >
                                        <option value="net_15" {{$invoice->terms == 'net_15' ? 'selected' : ''}}>Net 15</option>
                                        <option value="net_30" {{$invoice->terms == 'net_30' ? 'selected' : ''}}>Net 30</option>
                                    </select>
                                </div>

                                <label for="billing_address" class="col-sm-3 col-form-label">Billing Address</label>
                                <div class="col-sm-3">
                                    <textarea cols="2" class="form-control" id="billing_address" name="billing_address" placeholder="" autocomplete="off">{{$invoice->billing_address}}</textarea>
                                </div>

                            </div>

                            <!-- Products List -->
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="products_list">

{{--                                   @dd($invoice);--}}

                                    @foreach($invoice->details as $detail)
                                        <tr class="product-row" data-product-id="{{ $detail->product->id }}">
                                            <td>{{ $detail->product->Name }}</td>
                                            <td>{{ $detail->unit_price }}</td>
                                            <td hidden><input type="hidden" class="form-control product_id" name="items[{{ $detail->product->id }}][product_id]" value="{{ $detail->product_id }}" min="1" required=""></td>
                                            <td hidden><input type="hidden" class="form-control unit_price" name="items[{{ $detail->product->id }}][unit_price]" value="{{ $detail->unit_price }}" min="1" required=""></td>
                                            <td><input type="number" class="form-control quantity" name="items[{{ $detail->product->id }}][quantity]" value="{{ $detail->quantity }}" min="1" required="" oninput="calculateTotal()"></td>
                                            <td><input type="text" class="form-control row-total" name="items[{{ $detail->product->id }}][row_total]" readonly data-unit-price="{{ $detail->unit_price }}" value="{{ $detail->total }}"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <!-- Discount Field -->
                            <div class="form-group row">
                                <label for="discount_percentage" class="col-sm-3 col-form-label">Discount Percentage</label>
                                <div class="col-sm-9">
                                    <input disabled type="number" class="form-control" id="discount_percentage" name="discount_percentage" placeholder="Enter discount percentage" min="0" max="100" value="{{ $invoice->discount_percentage }}" oninput="calculateTotal()">
                                </div>
                            </div>

                            <!-- Total Card -->
                            <div class="card cardTotal">
                                <div class="card-header">
                                    <h5 class="card-title">Total</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="total_before_discount">Total Before Discount:</label>
                                            <input type="text" class="form-control" id="total_before_discount"  name="total_before_discount" readonly value="{{ $invoice->total_before_discount }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="total_after_discount">Total After Discount:</label>
                                            <input type="text" class="form-control" id="total_after_discount" name="total_after_discount" readonly value="{{ $invoice->total_after_discount }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-info">Update Invoice</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Your existing script and stylesheet links -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>

    <script type="text/javascript">
        // Function to calculate total before and after discount
        function calculateTotal() {
            var totalBeforeDiscount = 0;

            $('.product-row').each(function() {
                var quantity = parseInt($(this).find('.quantity').val());
                var unitPrice = parseFloat($(this).find('.row-total').attr('data-unit-price'));

                if(quantity && unitPrice){
                    var rowTotal = quantity * unitPrice;
                    totalBeforeDiscount += rowTotal;

                    $(this).find('.row-total').val(rowTotal.toFixed(2));
                }
            });

            // Calculate total after discount
            var discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
            var discountAmount = (totalBeforeDiscount * discountPercentage) / 100;
            var totalAfterDiscount = totalBeforeDiscount - discountAmount;

            $('#total_before_discount').val(totalBeforeDiscount.toFixed(2));
            $('#total_after_discount').val(totalAfterDiscount.toFixed(2));
        }

        $(document).ready(function() {
            $('#product_name').on('input', function() {
                var product_name = $(this).val();

                // Make an AJAX request to fetch product suggestions based on the query
                $.ajax({
                    url: '{{ route("products.autocomplete") }}', // Replace with your actual route for product autocomplete
                    method: 'GET',
                    data: { product_name: product_name },
                    dataType: 'json',
                    success: function(data) {
                        displayProductSuggestions(data);
                    }
                });
            });

            // Function to display product suggestions
            function displayProductSuggestions(data) {
                var suggestionsDiv = $('#product_suggestions');
                suggestionsDiv.empty();

                if (data.length > 0) {
                    data.forEach(function(product) {
                        var suggestion = $('<div class="product-suggestion">' + product.Name + ' - ' + product.UnitPrice + '</div>');

                        suggestion.click(function() {
                            addProductToList(product.id, product.Name, product.UnitPrice);
                            $('#product_name').val('');
                            suggestionsDiv.empty();
                            calculateTotal();
                        });

                        suggestionsDiv.append(suggestion);
                    });
                } else {
                    suggestionsDiv.append('<div>No matching products found</div>');
                }
            }

            // Function to add product to the list
            function addProductToList(productId, productName, unitPrice) {
                var productRow = '<tr class="product-row" data-product-id="' + productId + '">' +
                    '<td>' + productName + '</td>' +
                    '<td>' + unitPrice + '</td>' +
                    '<td hidden><input type="number" class="form-control unit_price" name="items[' + productId + '][unit_price]" value="' + unitPrice + '"></td>' +
                    '<td hidden><input type="number" class="form-control product_id" name="items[' + productId + '][product_id]" value="' + productId + '"></td>' +
                    '<td><input type="number" class="form-control quantity" name="items[' + productId + '][quantity]" placeholder="Quantity" min="1" required="" oninput="calculateTotal()"></td>' +
                    '<td><input type="text" class="form-control row-total" name="items[' + productId + '][row_total]" readonly data-unit-price="' + unitPrice + '"></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>';
                $('#products_list').append(productRow);
            }


            // Remove product from the list
            $('#products_list').on('click', '.remove-product', function () {
                $(this).closest('tr').remove();
                calculateTotal();
            });
        });
    </script>
@endsection
