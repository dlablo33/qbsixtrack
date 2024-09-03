<!DOCTYPE html>
<html>
<head>
    <title>BoLs Pagados</title>
    <style>
        table {
            width: 100%;
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
    <h1>BoLs Pagados</h1>
    <table>
        <thead>
            <tr>
                <th>BoL ID</th>
                <th>Precio Molecula 1</th>
                <th>Precio Molecula 3</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bolsPagados as $bol)
            <tr>
                <td>{{ $bol->bol_id }}</td>
                <td>{{ $bol->precio_molecula1 }}</td>
                <td>{{ $bol->precio_molecula3 }}</td>
                <td>{{ $bol->total }}</td>
                <td>{{ $bol->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
