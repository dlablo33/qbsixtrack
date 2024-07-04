<!DOCTYPE html>
<html>
<head>
    <title>Datos de Bluewi</title>
</head>
<body>
    <h1>Lista de Datos de Bluewi</h1>
    <table>
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
                <th>Gross (USG)</th>
                <th>Net (USG)</th>
                <th>Temperature</th>
                <th>Gravity</th>
                <th>Tank</th>
                <th>Order Number (Dup)</th>
                <th>#N/D</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bluewis as $data)
                <tr>
                    <td>{{ $data->order_number }}</td>
                    <td>{{ $data->bol_number }}</td>
                    <td>{{ $data->bol_version }}</td>
                    <td>{{ $data->order_type }}</td>
                    <td>{{ $data->status }}</td>
                    <td>{{ $data->bol_date }}</td>
                    <td>{{ $data->position_holder }}</td>
                    <td>{{ $data->supplier }}</td>
                    <td>{{ $data->customer }}</td>
                    <td>{{ $data->destination }}</td>
                    <td>{{ $data->carrier }}</td>
                    <td>{{ $data->po }}</td>
                    <td>{{ $data->truck }}</td>
                    <td>{{ $data->trailer }}</td>
                    <td>{{ $data->bay }}</td>
                    <td>{{ $data->product }}</td>
                    <td>{{ $data->scheduled_amount_usg }}</td>
                    <td>{{ $data->gross_usg }}</td>
                    <td>{{ $data->net_usg }}</td>
                    <td>{{ $data->temperature }}</td>
                    <td>{{ $data->gravity }}</td>
                    <td>{{ $data->tank }}</td>
                    <td>{{ $data->order_number_dup }}</td>
                    <td>{{ $data->nd }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
