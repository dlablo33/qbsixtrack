@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Depósitos en la parte superior derecha -->
        <div class="col-md-4 offset-md-8 text-right" id="depositos-section">
            <h4>Depósitos del Cliente: {{ $cliente->NOMBRE_COMERCIAL }}</h4>
            <div class="depositos-content">
                <h5>Depósitos Registrados</h5>
                @if($depositos->isEmpty())
                    <p>No hay depósitos registrados para este cliente.</p>
                @else
                    <ul class="list-unstyled">
                        @foreach($depositos as $deposito)
                            <li class="deposito-item">
                                <strong>ID:</strong> {{ $deposito->id }} |
                                <strong>Banco:</strong> {{ $deposito->banco->banco ?? 'N/A' }} |
                                <strong>Saldo MXN:</strong> ${{ number_format($deposito->saldo_mxn, 2) }} |
                                <strong>Saldo USD:</strong> ${{ number_format($deposito->saldo_usd, 2) }} |
                                <strong>Fecha de Registro:</strong> {{ $deposito->created_at->format('d/m/Y') }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Tabla de Pagos centrada -->
        <div class="col-md-10 offset-md-1">
            <div class="table-responsive" id="pagos-section">
                <h4 class="text-center">Pagos para el Cliente: {{ $cliente->NOMBRE_COMERCIAL }}</h4>
                @if($factura)
                    <h5 class="text-center">Última Factura: #{{ $factura->id }} | Total: ${{ number_format($factura->total, 2) }}</h5>
                @else
                    <p class="text-center">No hay facturas asociadas a este cliente.</p>
                @endif

                <h5 class="text-center mt-3">Pagos Registrados:</h5>
                @if($pagos->isEmpty())
                    <p class="text-center">No hay pagos registrados para este cliente.</p>
                @else
                <form action="{{ route('pagos.asignar_datos') }}" method="POST">
                    @csrf
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Monto</th>
                                <th>Fecha de Pago</th>
                                <th>Referencia</th>
                                <th>Banco Proveniente</th>
                                <th>Número de Cuenta</th>
                                <th>Complemento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $ultimoComplemento = null; @endphp
                            @foreach($pagos as $pago)
                                <tr>
                                    <td>{{ $pago->id }}</td>
                                    <input type="hidden" name="pago_ids[]" value="{{ $pago->id }}">
                                    <td>${{ number_format($pago->monto, 2) }}</td>
                                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                    <td>{{ $pago->complemento }}</td>
                                    
                                    <td>
                                        @if($pago->banco_proveniente)
                                            {{ $pago->banco_proveniente }}
                                        @else
                                            @if($pago->complemento !== $ultimoComplemento)
                                                <input type="text" name="banco_proveniente[{{ $pago->complemento }}]" class="form-control form-control-sm" placeholder="Banco Proveniente" value="{{ old('banco_proveniente.'.$pago->complemento) }}">
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($pago->numero_cuenta)
                                            {{ $pago->numero_cuenta }}
                                        @else
                                            @if($pago->complemento !== $ultimoComplemento)
                                                <input type="text" name="numero_cuenta[{{ $pago->complemento }}]" class="form-control form-control-sm" placeholder="Número de Cuenta" value="{{ old('numero_cuenta.'.$pago->complemento) }}">
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($pago->serial_baunche)
                                            {{ $pago->serial_baunche }}
                                        @else
                                            @if($pago->complemento !== $ultimoComplemento)
                                                <input type="text" name="serial_baunche[{{ $pago->complemento }}]" class="form-control form-control-sm" value="{{ old('serial_baunche.'.$pago->complemento, $pago->serial_baunche) }}" placeholder="Serial Baunche">
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($pago->complemento !== $ultimoComplemento)
                                            <a href="{{ route('pagos.descargar.lote', $pago->lote_pago_id) }}" class="btn btn-primary btn-sm">
                                                Descargar PDF
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @php $ultimoComplemento = $pago->complemento; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success">Actualizar Pagos</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pagosSection = document.getElementById('pagos-section');
            pagosSection.style.opacity = 0;
            setTimeout(() => {
                pagosSection.style.opacity = 1;
                pagosSection.style.transition = 'opacity 0.5s ease-in-out';
            }, 200);
        });
    </script>
@endsection

<style>
    /* Fuentes y Estilos Generales */
    body {
        font-family: 'Lato', sans-serif;
    }

    h4, h5 {
        font-family: 'Montserrat', sans-serif;
    }

    .container-fluid {
        padding: 15px;
    }

    /* Depósitos en la parte superior derecha */
    #depositos-section {
        margin-top: 20px;
    }

    .depositos-content {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }

    .deposito-item {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #ffffff;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .deposito-item:hover {
        background-color: #f1f1f1;
    }

    /* Tabla centrada y responsiva */
    #pagos-section {
        margin-top: 30px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        background-color: #fff;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    /* Botones centrados */
    .btn {
        font-family: 'Montserrat', sans-serif;
        font-weight: bold;
    }

    .btn-lg {
        padding: 10px 25px;
        font-size: 18px;
    }

    /* Media Queries para Responsividad */
    @media (max-width: 768px) {
        .col-md-4 {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 576px) {
        .deposito-item {
            font-size: 14px;
        }

        .btn-lg {
            font-size: 16px;
            padding: 8px 20px;
        }
    }
</style>
