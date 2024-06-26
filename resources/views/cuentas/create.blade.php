@extends('layouts.app')

@section('content')
    <h1>Registrar Pago para Factura #{{ $factura->id }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cuentas.store') }}" method="POST">
        @csrf
        <input type="hidden" name="factura_id" value="{{ $factura->id }}">

        <div class="form-group">
            <label for="monto">Monto:</label>
            <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="fecha_pago">Fecha de Pago:</label>
            <input type="date" name="fecha_pago" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" name="referencia" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Pago</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Regresar</a>
    </form>
@endsection

