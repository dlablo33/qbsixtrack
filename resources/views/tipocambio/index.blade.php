<!-- resources/views/tipo_cambio/index.blade.php -->

@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Tipos de Cambio Registrados</h2>

    <a href="{{ route('tipocambio.create') }}" class="btn btn-primary mb-3">Registrar Nuevo Tipo de Cambio</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo de Cambio (MXN)</th>
                <th>Tipo de Cambio (USD)</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tiposCambio as $tipoCambio)
                <tr>
                    <td>{{ $tipoCambio->id }}</td>
                    <td>{{ $tipoCambio->fecha->format('Y-m-d') }}</td>
                    <td>{{ number_format($tipoCambio->tipo_cambio_mxn, 4) }}</td>
                    <td>{{ number_format($tipoCambio->tipo_cambio_usd, 4) }}</td>
                    <td>{{ $tipoCambio->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $tipoCambio->updated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
