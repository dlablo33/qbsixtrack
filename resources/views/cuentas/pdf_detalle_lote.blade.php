<!DOCTYPE html>
<html>
<head>
    <title>Detalles del Lote de Pago</title>
    <style>
        /* Estilos para el PDF */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Detalles del Lote de Pago #{{ $lotePago->id }}</h1>
    <h3>Pagos:</h3>
    <table>
    <thead>
        <tr>
            <th>ID Pago</th>
            <th>Monto</th>
            <th>Fecha de Pago</th>
            <th>Referencia</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lotePago->pagos as $pago)
            <tr>
                <td>{{ $pago->id }}</td>
                <td>${{ number_format($pago->monto, 2) }}</td>
                <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td> <!-- Aquí está la llamada a format() -->
                <td>{{ $pago->complemento }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
