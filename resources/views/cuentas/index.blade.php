@extends('layouts.master')

@section('content')
    <h1>Saldos</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Saldo Restante</th>
                <th>Saldo a Favor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($deudasPorCliente as $cliente)
            <tr>
                <td>{{ $cliente->cliente_name }}</td>
                <td>{{ number_format($cliente->saldoRestante, 2, '.', '') }}</td>
                <td>{{ number_format($cliente->saldoAFavor, 2, '.', '') }}</td>
                <td>
                    <a href="{{ route('cuentas.cnc-detalle', ['cliente_name' => $cliente->cliente_name]) }}" class="btn btn-primary">Estados de cuentas</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>
@endsection







