@extends('layouts.master')

@section('content')
    <h1>Registrar Gasto</h1>
    <form action="{{ route('empresa_cuenta.storeGasto') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="clasificacion">Clasificación</label>
            <input type="text" name="clasificacion" id="clasificacion" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="beneficiario">Beneficiario</label>
            <input type="text" name="beneficiario" id="beneficiario" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label for="moneda">Moneda</label>
            <select name="moneda" id="moneda" class="form-control" required>
                <option value="MXN">MXN</option>
                <option value="USD">USD</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Gasto</button>
    </form>
@endsection
