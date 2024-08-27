@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Listado de Agentes Aduanales</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('aduana.create') }}" class="btn btn-primary mb-3">Agregar Nuevo Agente</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>RFC</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agentes as $agente)
                    <tr>
                        <td>{{ $agente->id }}</td>
                        <td>{{ $agente->nombre }}</td>
                        <td>{{ $agente->codigo }}</td>
                        <td>{{ $agente->rfc }}</td>
                        <td>{{ $agente->telefono }}</td>
                        <td>{{ $agente->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
