@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Pagos para el Cliente: {{ $cliente->NOMBRE_COMERCIAL }}</h1>

    @if($factura)
        <h2>Última Factura: #{{ $factura->id }}</h2>
        <h3>Total: ${{ number_format($factura->total, 2) }}</h3>
    @else
        <p>No hay facturas asociadas a este cliente.</p>
    @endif

    <h4>Pagos Registrados:</h4>
    @if($pagos->isEmpty())
        <p>No hay pagos registrados para este cliente.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>Monto</th>
                    <th>Fecha de Pago</th>
                    <th>Referencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php $ultimoComplemento = null; @endphp
                @foreach($pagos as $pago)
                    <tr>
                        <td>{{ $pago->id }}</td>
                        <td>${{ number_format($pago->monto, 2) }}</td>
                        <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                        <td>{{ $pago->complemento }}</td>
                        <td>
                            @if($pago->complemento !== $ultimoComplemento)
                                <a href="{{ route('pagos.descargar.lote', $pago->lote_pago_id) }}" class="btn btn-primary">
                                    Descargar PDF
                                </a>
                            @endif
                        </td>
                    </tr>
                    @php $ultimoComplemento = $pago->complemento; @endphp
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('cuentas.index') }}" class="btn btn-secondary">Regresar</a>
    </div>
</div>
@endsection






