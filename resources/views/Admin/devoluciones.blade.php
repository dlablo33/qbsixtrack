@extends('layouts.app') <!-- Asegúrate de que la plantilla base sea la correcta -->

@section('content')
<div class="container">
    <h1>Registrar Devolución</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <form action="{{ route('refund-deposit') }}" method="POST">
        @csrf
        <input type="hidden" name="deposito_id" id="deposito_id" value="{{ old('deposito_id') }}">
        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}">
        <input type="hidden" name="banco_id" id="banco_id" value="{{ old('banco_id') }}">
        
        <div class="form-group">
            <label for="banco">Banco</label>
            <select name="banco" id="banco" class="form-control" onchange="setBancoId(this)">
                <option value="">Selecciona un banco</option>
                
                @foreach($bancos as $banco)
                    <option value="{{ $banco->id }}" {{ old('banco') == $banco->id ? 'selected' : '' }}>{{ $banco->nombre }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" step="0.01" value="{{ old('cantidad') }}" required>
        </div>
        
        <div class="form-group">
            <label for="moneda">Moneda</label>
            <select name="moneda" id="moneda" class="form-control" required>
                <option value="MXN" {{ old('moneda') == 'MXN' ? 'selected' : '' }}>MXN</option>
                <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>USD</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar Devolución</button>
    </form>
</div>

<script>
    function setBancoId(select) {
        var bancoIdField = document.getElementById('banco_id');
        bancoIdField.value = select.value;
    }
</script>
@endsection



