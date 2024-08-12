@extends('layouts.master')

@section('content')
<style>
    /* Estilos omitidos por brevedad */
</style>

<div class="container mt-4">
    <h1 class="display-6">Resumen General de Ingresos y Devoluciones</h1>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="thead-custom">
                <tr>
                    <th>ID</th>
                    <th>Banco</th>
                    <th>Cliente</th>
                    <th>Saldo en MXN</th>
                    <th>Saldo en USD</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($depositos as $deposito)
                    <tr>
                        <td>{{ $deposito->id }}</td>
                        <td>{{ $deposito->banco->banco }}</td>
                        <td>{{ $deposito->cliente->cliente }}</td>
                        <td>${{ number_format($deposito->saldo_mxn, 2, '.', ',') }}</td>
                        <td>${{ number_format($deposito->saldo_usd, 2, '.', ',') }}</td>
                        <td>{{ $deposito->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if(!$deposito->asignado)
                                <form action="{{ route('depositos.asignarSaldo', $deposito->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="cliente_id">Asignar a Cliente:</label>
                                        <select name="cliente_id" id="cliente_id" class="form-control">
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->NOMBRE_COMERCIAL }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($deposito->saldo_usd > 0)
                                        <div class="form-group">
                                            <label for="tipo_cambio">Tipo de Cambio:</label>
                                            <input type="text" name="tipo_cambio" id="tipo_cambio" class="form-control" required>
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-success btn-animated">Asignar a Saldo a Favor</button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary btn-animated" disabled>Asignado</button>
                            @endif
                        </td>
                    </tr>
                    @foreach($devoluciones->where('id_deposito', $deposito->id) as $devolucion)
                        <tr class="table-warning">
                            <td>{{ $devolucion->id }}</td>
                            <td>{{ $devolucion->banco->banco }}</td>
                            <td>{{ $deposito->cliente->cliente }}</td>
                            <td>- ${{ number_format($devolucion->cantidad, 2, '.', ',') }}</td>
                            <td>-</td>
                            <td>{{ $devolucion->created_at->format('d/m/Y H:i') }}</td>
                            <td>Devolución en {{ $devolucion->moneda }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="btn-container mt-4">
        <a href="{{ route('Admin.index') }}" class="btn btn-success btn-animated">Volver</a>
    </div>
</div>
@endsection


