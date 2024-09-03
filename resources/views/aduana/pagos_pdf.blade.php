<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoLs Pagados</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 10px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>BoLs Pagados</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Bol Number</th>
                <th>Honorarios</th>
                <th>Fecha de Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagos as $pago)
                <tr>
                    <td>{{ $pago->id }}</td>
                    <td>{{ $pago->aduana->bol_number }}</td>
                    <td>{{ $pago->cantidad }}</td>
                    <td>{{ $pago->fecha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
