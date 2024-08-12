@extends('layouts.master')

@section('content')
<style>
    h1.display-6 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .thead-custom {
        background-color: #007bff;
        color: #ffffff;
    }

    .table-hover tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-animated {
        position: relative;
        overflow: hidden;
    }

    .btn-animated::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.5s ease;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        z-index: 0;
    }

    .btn-animated:hover::after {
        transform: translate(-50%, -50%) scale(1);
    }

    .btn-animated span {
        position: relative;
        z-index: 1;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-container a {
        margin: 0 5px;
    }
</style>

<div class="container mt-4">
    <h1 class="display-6">Historial de Depósitos para {{ $cliente->cliente }}</h1>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="thead-custom">
                <tr>
                    <th>ID</th>
                    <th>Banco</th>
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
                        <td>${{ number_format($deposito->saldo_mxn, 2, '.', ',') }}</td>
                        <td>${{ number_format($deposito->saldo_usd, 2, '.', ',') }}</td>
                        <td>{{ $deposito->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if(!$devoluciones->where('id_deposito', $deposito->id)->count())
                                <button type="button" class="btn btn-success btn-animated"
                                        onclick="openRefundModal({{ $deposito->id }}, '{{ $deposito->banco->banco }}', {{ $deposito->saldo_mxn }}, {{ $deposito->saldo_usd }}, {{ $deposito->banco_id }})">
                                    Agregar Devolución
                                </button>
                            @endif
                        </td>
                    </tr>
                    @foreach($devoluciones->where('id_deposito', $deposito->id) as $devolucion)
                        <tr class="table-warning">
                            <td>{{ $devolucion->id }}</td>
                            <td>{{ $deposito->banco->banco }}</td>
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
    <div class="btn-container">
        <a href="{{ route('Admin.index') }}" class="btn btn-success btn-animated">Volver</a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="devolucionModal" tabindex="-1" role="dialog" aria-labelledby="devolucionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('Admin.refundDeposit') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="devolucionModalLabel">Agregar Devolución</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="deposito_id" id="deposito_id">
                    <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                    <input type="hidden" name="banco_id" id="banco_id">

                    <div class="form-group">
                        <label for="banco">Banco</label>
                        <input type="text" class="form-control" id="banco" name="banco" readonly>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad a devolver</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="moneda">Moneda</label>
                        <select class="form-control" id="moneda" name="moneda" required>
                            <option value="MXN">MXN</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Devolución</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRefundModal(depositoId, banco, saldoMXN, saldoUSD, bancoId) {
    document.getElementById('deposito_id').value = depositoId;
    document.getElementById('banco').value = banco;
    document.getElementById('banco_id').value = bancoId;

    // Determinar qué saldo se debe mostrar y establecer la moneda predeterminada
    var saldo = saldoMXN > 0 ? saldoMXN : saldoUSD;
    var moneda = saldoMXN > 0 ? 'MXN' : 'USD';

    document.getElementById('cantidad').value = saldo;
    document.getElementById('moneda').value = moneda;

    $('#devolucionModal').modal('show');
}
</script>
@endsection

