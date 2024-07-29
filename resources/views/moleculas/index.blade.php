@extends('layouts.master')

@section('content')
<div>
    <h1>Moleculas</h1>

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

    <form action="" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Transferir Datos de Logística a Moléculas</button>
    </form>

    <a href="{{ route('moleculas.create') }}" class="btn btn-secondary mt-3">Añadir Precio de Molécula</a>

    <h2 class="mt-4">Registros de Precios de Moléculas</h2>

    @if ($moleculas->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Molécula</th>
                    <th>Precio</th>
                    <th>Usuario</th>
                    <th>Fecha de Creación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($moleculas as $molecula)
                    <tr>
                        <td>{{ $molecula->molecula }}</td>
                        <td>{{ number_format($molecula->precio, 2, '.', ',') }}</td>
                        <td>{{ $molecula->usuario }}</td>
                        <td>{{ $molecula->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay registros de precios de moléculas.</p>
    @endif
</div>
@endsection

