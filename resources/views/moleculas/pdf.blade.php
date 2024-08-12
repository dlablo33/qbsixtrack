<!DOCTYPE html>
<html>
<head>
    <title>Pago PDF</title>
    <style>
        /* Estilos para el PDF */
        table {
            width: 60%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reporte de Pagos</h1>

    @if ($records->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>BOL</th>
                    <th>Litros</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Fecha de Creación</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        <td>{{ $record->id }}</td>
                        <td>{{ $record->bol_number }}</td>
                        <td>{{ number_format($record->litros, 2, '.', ',') }}</td>
                        <td>${{ number_format($record->rate, 2, '.', ',') }}</td>
                        <td>${{ number_format($record->total, 2, '.', ',') }}</td>
                        <td>{{ $record->created_at }}</td>
                        <td>{{ $record->estatus }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay registros para mostrar.</p>
    @endif
</body>
</html>




