@extends('layouts.master')

@section('content')
<div>
    <h1>Molecula 1</h1>

    <!-- Mensajes de éxito y error -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario para transferir datos -->
    <form action="" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Transferir Datos de Logística a Moléculas</button>
    </form>
    <h1 class="mt-12">Lista de Precios de Moléculas</h1>
    <a href="{{ route('moleculas.create') }}" class="btn btn-primary mb-3">Añadir Precio Molecula</a>

    <!-- Tabla para mostrar los registros de PreciosMolecula -->
    <div class="mt-4">
        <h2>Registros de Precios de Moléculas</h2>

        @if ($preciosMoleculas->count() > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>BOL</th>
                        <th>Litros</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Fecha de Creación</th>
                        <th>Fecha de Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preciosMoleculas as $molecula)
                        <tr>
                            <td>{{ $molecula->id }}</td>
                            <td>{{ $molecula->bol }}</td>
                            <td>{{ number_format($molecula->litros, 2, '.', ',') }}</td>
                            <td>${{ number_format($molecula->rate, 2, '.', ',') }}</td>
                            <td>${{ number_format($molecula->total, 2, '.', ',') }}</td>
                            <td>{{ $molecula->created_at }}</td>
                            <td>{{ $molecula->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay registros de precios de moléculas.</p>
        @endif
    </div>
</div>
@endsection
