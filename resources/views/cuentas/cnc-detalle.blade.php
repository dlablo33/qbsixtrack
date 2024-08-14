@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Facturas del Cliente: {{ $cliente_name }}</h1>
        <h2>Saldo a Favor: ${{ number_format($saldoAFavor, 2, '.', '') }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Total</th>
                    <th>Abonos</th>
                    <th>Restante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($facturas as $factura)
            <tr>
                <td>{{ $factura->id }}</td>
                <td>{{ $factura->producto_name }}</td>
                <td> $ {{ number_format($factura->total, 2, '.', '') }}</td>
                <td>
                    @foreach ($factura->pagos as $pago)
                        <p><a>{{ $pago->fecha_pago }}</a>: <a>${{ number_format($pago->monto, 2, '.', '') }}</a></p>
                    @endforeach
                </td>
                <td> $ {{ number_format($factura->montoPendiente(), 2, '.', '') }}</td>
                <td>
                   <!-- <form action="{{ route('cuentas.pagarCompleto', $factura->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="referencia">Referencia (en caso de pago completo):</label>
                            <input type="text" name="referencia" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Pagar</button>
                    </form> -->
                    <!--<th><a href="{{ route('cuentas.create', $factura->id) }}" class="btn btn-primary">Abono</a></th>-->
                    
                        <form action="{{ route('cuentas.usarSaldo', $factura->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Usar Saldo</button>
                        </form>
                    
                </td>
            </tr>
        @endforeach
            </tbody>
        </table>
        <div class="form-group row mt-3">
            <div class="col-sm-12 text-center">
                <a href="{{ route('cuentas.index')}}" class="btn btn-secondary btn-block">Regresar</a>
            </div>
        </div>
    </div>
@endsection


