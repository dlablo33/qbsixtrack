@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h1>Clientes y Saldos</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('Admin.showDepositForm') }}" class="btn btn-primary">Depositar</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Saldo en MXN</th>
                <th>Saldo en USD</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->cliente }}</td>
                    <td>${{ number_format($cliente->saldo_mxn, 2, '.', ',') }}</td>
                    <td>${{ number_format($cliente->saldo_usd, 2, '.', ',') }}</td>
                    <td>
                    <a href="{{ route('Admin.showClientBanks', ['id' => $cliente->id]) }}" class="btn btn-info">Ver Bancos</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('Admin.index') }}" class="btn btn-success mt-3 btn-animated">Volver</a>
</div>
@endsection



