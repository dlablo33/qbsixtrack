@extends('layouts.master')

@section('content')
<style>
    /* Estilos personalizados */
    h1 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px; /* Aumenta el espacio debajo del encabezado */
    }

    .form-group {
        margin-bottom: 1.5rem; /* Espacio entre los campos del formulario */
    }

    .form-control {
        border-radius: 0.25rem; /* Bordes redondeados en campos de formulario */
    }

    .btn-primary {
        background-color: #007bff; /* Color de fondo del botón */
        border-color: #007bff; /* Color del borde del botón */
        transition: background-color 0.3s ease, border-color 0.3s ease; /* Animación para el cambio de color */
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Color de fondo del botón al pasar el ratón */
        border-color: #004085; /* Color del borde del botón al pasar el ratón */
    }

    .btn-success {
        background-color: #28a745; /* Color de fondo del botón */
        border-color: #28a745; /* Color del borde del botón */
        transition: background-color 0.3s ease, border-color 0.3s ease; /* Animación para el cambio de color */
    }

    .btn-success:hover {
        background-color: #218838; /* Color de fondo del botón al pasar el ratón */
        border-color: #1e7e34; /* Color del borde del botón al pasar el ratón */
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

    .button-group {
        display: flex;
        gap: 10px; /* Espacio entre los botones */
    }
</style>

<div class="container mt-4">
    <h1>Registrar Depósito</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('Admin.processDeposit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="cliente">Cliente</label>
            <select name="cliente" id="cliente" class="form-control" required>
                <option value="">Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->cliente }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="banco">Banco</label>
            <select name="banco" id="banco" class="form-control" required>
                <option value="">Seleccione un banco</option>
                @foreach($bancos as $banco)
                    <option value="{{ $banco->id }}">{{ $banco->banco }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="float" name="cantidad" id="cantidad" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="moneda">Moneda</label>
            <select name="moneda" id="moneda" class="form-control" required>
                <option value="MXN">MXN</option>
                <option value="USD">USD</option>
            </select>
        </div>

        <div class="button-group mt-3">
            <button type="submit" class="btn btn-primary btn-animated">Registrar Depósito</button>
            <a href="{{ route('Admin.index') }}" class="btn btn-success btn-animated">Volver</a>
        </div>
    </form>
</div>
@endsection



