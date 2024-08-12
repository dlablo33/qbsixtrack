@extends('layouts.master')

@section('content')
<style>
    /* Estilos omitidos por brevedad */
</style>

<div class="container mt-4">
    <h1 class="display-6">Registrar Gasto</h1>

    <form action="{{ route('empresa_cuenta.storeGasto') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha') }}" required>
            @error('fecha')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="clasificacion">Clasificación de Cuenta</label>
            <select class="form-control @error('clasificacion') is-invalid @enderror" id="clasificacion" name="clasificacion" required>
                <option value="">Seleccionar</option>
                @foreach($clasificaciones as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
                <option value="otro">Otro</option>
            </select>
            <input type="text" class="form-control mt-2 @error('otro_clasificacion') is-invalid @enderror" id="otro_clasificacion" name="otro_clasificacion" placeholder="Especificar otro" style="display:none;">
            @error('clasificacion')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @error('otro_clasificacion')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="beneficiario">Beneficiario / Pagador</label>
            <select class="form-control @error('beneficiario') is-invalid @enderror" id="beneficiario" name="beneficiario" required>
                <option value="">Seleccionar</option>
                @foreach($beneficiarios as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
                <option value="otro">Otro</option>
            </select>
            <input type="text" class="form-control mt-2 @error('otro_beneficiario') is-invalid @enderror" id="otro_beneficiario" name="otro_beneficiario" placeholder="Especificar otro" style="display:none;">
            @error('beneficiario')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @error('otro_beneficiario')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="banco">Banco</label>
            <select class="form-control @error('banco') is-invalid @enderror" id="banco" name="banco" required>
                <option value="">Seleccionar</option>
                @foreach($bancos as $banco)
                    <option value="{{ $banco->id }}">{{ $banco->banco }}</option>
                @endforeach
            </select>
            @error('banco')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="moneda">Moneda</label>
            <select class="form-control @error('moneda') is-invalid @enderror" id="moneda" name="moneda" required>
                <option value="">Seleccionar</option>
                <option value="MXN">MXN</option>
                <option value="USD">USD</option>
            </select>
            @error('moneda')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" step="0.01" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad') }}" required>
            @error('cantidad')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Registrar Gasto</button>
    </form>
</div>

<script>
    document.getElementById('clasificacion').addEventListener('change', function() {
        var otroInput = document.getElementById('otro_clasificacion');
        if (this.value === 'otro') {
            otroInput.style.display = 'block';
        } else {
            otroInput.style.display = 'none';
        }
    });

    document.getElementById('beneficiario').addEventListener('change', function() {
        var otroInput = document.getElementById('otro_beneficiario');
        if (this.value === 'otro') {
            otroInput.style.display = 'block';
        } else {
            otroInput.style.display = 'none';
        }
    });
</script>
@endsection


