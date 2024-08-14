@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Molecula 2</h2>

<form action="{{ route('moleculas.migrateDataForMolecula2') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Migrar Datos para Molecula 2</button>
</form>

    <form method="POST" action="{{ route('moleculas.molecula2.process') }}">
        @csrf
        <table id="example1" class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>BOL</th>
                    <th>Cliente</th>
                    <th>Destino</th>
                    <th>Transportista</th>
                    <th>Litros</th>
                    <th>Precio</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($records as $record)
            @php
                // Encuentra el nombre del cliente basado en el ID
                $clienteNombre = $clientes->where('id', $record->cliente)->first()->NOMBRE_COMERCIAL ?? 'Cliente no asignado';

                // Encuentra el nombre del destino basado en el ID
                $destinoNombre = $destinos->where('id', $record->destino_id)->first()->nombre ?? 'Destino no asignado';
            @endphp
                <tr>
                    <td><input type="checkbox" name="selected_records[]" value="{{ $record->id }}"></td>
                    <td>{{ $record->bol }}</td>
                    <td>{{ $clienteNombre }}</td>
                    <td>{{ $destinoNombre }}</td>
                    <td>{{ $record->linea }}</td>
                    <td>{{ $record->litros }}</td> 
                    <td>{{ $record->precio }}</td>
                    <td>{{ $record->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Procesar Pagos</button>
    </form>
</div>
@endsection
