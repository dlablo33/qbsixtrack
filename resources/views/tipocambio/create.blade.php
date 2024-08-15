@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Registrar Tipo de Cambio</h2>

    <form action="{{ route('tipocambio.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tipo_cambio_mxn">Tipo de Cambio MXN:</label>
            <input type="text" name="tipo_cambio_mxn" class="form-control" id="tipo_cambio_mxn" required>
        </div>

        <div class="form-group">
            <label for="tipo_conversion">Tipo de Conversión:</label>
            <select name="tipo_conversion" class="form-control" id="tipo_conversion" required>
                <option value="mxn_to_usd">MXN a USD</option>
                <option value="usd_to_mxn">USD a MXN</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection
