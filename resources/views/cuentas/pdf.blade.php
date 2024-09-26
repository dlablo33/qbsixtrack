<h1>Estado de Cuenta de Clientes</h1>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Saldo Restante</th>
            <th>Saldo a Favor</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($deudasPorCliente as $cliente)
        <tr>
            <td>{{ $cliente->cliente_name }}</td>
            <td>${{ number_format($cliente->saldoRestante, 2, '.', ',') }}</td>
            <td>${{ number_format($cliente->saldoAFavor, 2, '.', ',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
