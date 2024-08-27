<!-- resources/views/aduana/agentes/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Agregar Nuevo Agente Aduanal</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('aduana.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}" required>
            </div>

            <div class="form-group">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc" class="form-control" value="{{ old('rfc') }}" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('aduana.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
