@extends('layouts.master')

@section('content')
    <style>
        /* Estilos para la tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .row-highlight {
            background-color: #ffcccc;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Estilo para el filtro */
        .filter-form {
            margin-bottom: 20px;
        }

        #filter-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>

    <div class="container">
        <h1>Listado de Bluewing</h1>
        <div class="text-right mb-3">
            <a href="{{ route('bluewi.upload.form') }}" class="btn btn-primary">Subir archivo</a>
        </div>
        <div class="text-right mb-3">
            <a href="{{ route('bluewi.compare.bol') }}" class="btn btn-primary">Comparar con Invoice</a>
        </div>

        <form action="{{ route('bluewi.index') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label for="filter">Bol no creados</label>
                <input type="checkbox" id="filter" name="filter" value="1" {{ request('filter') ? 'checked' : '' }}>
                <button type="submit" class="btn btn-primary">Aplicar filtro</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>BOL#</th>
                        <th>BOL Ver.</th>
                        <th>Order Type</th>
                        <th>Status</th>
                        <th>BOL Date</th>
                        <th>Position Holder</th>
                        <th>Supplier</th>
                        <th>Customer</th>
                        <th>Destination</th>
                        <th>Carrier</th>
                        <th>PO</th>
                        <th>Truck</th>
                        <th>Trailer</th>
                        <th>Bay</th>
                        <th>Product</th>
                        <th>Scheduled Amount (USG)</th>
                        <th>Gross(USG)</th>
                        <th>Net(USG)</th>
                        <th>Temperature</th>
                        <th>Gravity</th>
                        <th>Tank</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bluewi as $row)
                        <tr class="{{ !isset($row->bol_number) || trim($row->bol_number) === '' ? 'row-highlight' : '' }}">
                            <td>{{ $row->order_number }}</td>
                            <td>{{ $row->bol_number != null ? ($row->bol_number) : '' }}</td>
                            <td>{{ $row->bol_version }}</td>
                            <td>{{ $row->order_type }}</td>
                            <td>{{ $row->status }}</td>
                            <td>{{ $row->bol_date }}</td>
                            <td>{{ $row->position_holder }}</td>
                            <td>{{ $row->supplier }}</td>
                            <td>{{ $row->customer }}</td>
                            <td>{{ $row->destination }}</td>
                            <td>{{ $row->carrier }}</td>
                            <td>{{ $row->po }}</td>
                            <td>{{ $row->truck }}</td>
                            <td>{{ $row->trailer }}</td>
                            <td>{{ $row->bay }}</td>
                            <td>{{ $row->product }}</td>
                            <td>{{ $row->scheduled_amount_usg }}</td>
                            <td>{{ $row->gross_usg }}</td>
                            <td>{{ $row->net_usg }}</td>
                            <td>{{ $row->temperature }}</td>
                            <td>{{ $row->gravity }}</td>
                            <td>{{ $row->tank }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $bluewi->appends(request()->input())->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterCheckbox = document.getElementById('filter');
            const filterButton = document.getElementById('filter-btn');

            filterCheckbox.addEventListener('change', function () {
                filterButton.disabled = !filterCheckbox.checked;
            });

            // Al cargar la página, asegurarse de que el botón esté en el estado correcto
            filterButton.disabled = !filterCheckbox.checked;
        });
    </script>

@endsection

