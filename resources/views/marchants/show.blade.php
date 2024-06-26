@extends('layouts.app')

@section('content')
<div class="container">
    <style>
        .table-heading {
            color: #333;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 8px 16px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table th {
            background-color: #f2f2f2;
            color: #333;
        }

        .btn-regresar {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-regresar:hover {
            background-color: #0056b3;
            border-color: #004080;
        }
    </style>

    <div class="container-fluid">
        <a href="{{ url()->previous() }}" class="btn-regresar">Regresar</a>
        @if ($precios->isNotEmpty())
            <h2>Historial de precios del cliente: {{ $precios->first()->cliente_id }} - {{ $precios->first()->cliente_name }}</h2>

            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Numero de Producto</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Fecha de Actualizacion</th>
                        <th>Fecha de Vigencia</th>
                        <th>Semana</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($precios as $precio)
                        <tr>
                            <td>{{ $precio->id }}</td>
                            <td>{{ $precio->producto_id }}</td>
                            <td>{{ $precio->producto_name }}</td>
                            <td>{{ $precio->precio }}</td>
                            <td>{{ $precio->updated_at }}</td>
                            <td>{{ $precio->fecha_vigencia }}</td>
                            <td>{{ $precio->semana }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Cliente no encontrado.</p>
        @endif
    </div>
</div>
@endsection
