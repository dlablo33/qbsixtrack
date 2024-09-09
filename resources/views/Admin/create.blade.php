@extends('layouts.master')

@section('content')
<div class="container">

    <!-- Mostrar mensaje de éxito si existe -->
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <h2>Crear Cliente</h2>

    <form action="{{ route('admin.store') }}" method="POST">
        @csrf
        <!-- Campo para el nombre del cliente -->
        <div class="form-group">
            <label for="cliente">Nombre del Cliente:</label>
            <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Ingresa el nombre del cliente" required>
        </div>

        <!-- Botón para enviar el formulario -->
        <button type="submit" class="btn btn-primary">Crear Cliente</button>
    </form>
</div>
@endsection

