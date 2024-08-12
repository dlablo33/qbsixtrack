@extends('layouts.master')

@section('content')
<div>
    <h1>Logística</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{ route('logistica.transferData') }}" id="transferButton" class="btn btn-primary mb-3">Sincronizar Datos</a>
    <div id="loading" style="display: none;">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <form action="{{ route('logistica.guardar_todos') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success mb-3">Guardar Todos los Cambios</button>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>BOL</th>
                        <th>Order Number</th>
                        <th>Semana</th>
                        <th>Fecha</th>
                        <th>Linea</th>
                        <th>No Pipa</th>
                        <th>Cliente</th>
                        <th>Destino</th>
                        <th>Estatus</th>
                        <th>Cruce</th>
                        <th>Fecha Salida</th>
                        <th>Fecha Entrega</th>
                        <th>Fecha Descarga</th>
                        <th>Pedimento</th>
                        @if (Auth::user()->tipo_usuario == 1)
                            <th>Precio</th>
                            <th>Total</th>
                        @endif
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logis as $logi)
                        <tr>
                            <td>{{ $logi->bol }}</td>
                            <td>{{ $logi->order_number }}</td>
                            <td>{{ $logi->fecha->weekOfYear }}</td>
                            <td>{{ $logi->fecha->format('d-m-Y') }}</td>
                            <td>{{ $logi->linea }}</td>
                            <td>{{ $logi->no_pipa }}</td>
                            <td>
                                <input type="hidden" name="logistica[{{ $logi->id }}][id]" value="{{ $logi->id }}">
                                <select name="logistica[{{ $logi->id }}][cliente]" class="form-control cliente-select" {{ $logi->cliente ? 'disabled' : '' }}>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ $logi->cliente == $cliente->id ? 'selected' : '' }}>{{ $cliente->NOMBRE_COMERCIAL }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="logistica[{{ $logi->id }}][destino]" class="form-control destino-select" {{ $logi->destino_id ? 'disabled' : '' }}>
                                    <option value="">Selecciona un destino</option>
                                    @foreach($destinos as $destino)
                                        <option value="{{ $destino->id }}" {{ $logi->destino_id == $destino->id ? 'selected' : '' }}>{{ $destino->nombre }}</option>
                                    @endforeach
                                    <option value="5" {{ $logi->destino_id == 5 ? 'selected' : '' }}>FOB</option>
                                </select>
                            </td>
                            <td class="status">
                                <select name="logistica[{{ $logi->id }}][status]" class="form-control status-select">
                                    <option value="pendiente" {{ $logi->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="cargada" {{ $logi->status == 'cargada' ? 'selected' : '' }}>Cargada</option>
                                    <option value="descargada" {{ $logi->status == 'descargada' ? 'selected' : '' }}>Descargada</option>
                                </select>
                            </td>
                            <td class="cruce">
                                <select name="logistica[{{ $logi->id }}][cruce]" class="form-control cruce-select">
                                    <option value="rojo" {{ $logi->cruce == 'rojo' ? 'selected' : '' }}>Rojo</option>
                                    <option value="verde" {{ $logi->cruce == 'verde' ? 'selected' : '' }}>Verde</option>
                                </select>
                            </td>
                            <td><input type="date" name="logistica[{{ $logi->id }}][fecha_salida]" class="form-control" value="{{ $logi->fecha_salida }}"></td>
                            <td><input type="date" name="logistica[{{ $logi->id }}][fecha_entrega]" class="form-control" value="{{ $logi->fecha_entrega }}"></td>
                            <td><input type="date" name="logistica[{{ $logi->id }}][fecha_descarga]" class="form-control" value="{{ $logi->fecha_descarga }}"></td>
                            <td><input type="text" name="logistica[{{ $logi->id }}][pedimento]" class="form-control" value="{{ $logi->pedimento }}"></td>
                            @if (Auth::user()->tipo_usuario == 1)
                                <td>
                                    @if ($logi->cliente)
                                        <select name="logistica[{{ $logi->id }}][precio]" class="form-control precio-select" data-logi-id="{{ $logi->id }}">
                                            <option value="">Selecciona un precio</option>
                                            @foreach ($precios[$logi->id] as $precioId => $precio)
                                                <option value="{{ $precio }}" {{ $logi->precio == $precio ? 'selected' : '' }}>{{ $precio }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{ $logi->precio }}
                                    @endif
                                </td>
                                <td id="total-{{ $logi->id }}">
                                    @if (isset($totales[$logi->id]))
                                        ${{ number_format($totales[$logi->id], 2) }}
                                    @endif
                                </td>
                            @endif
                            <td>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar Todos los Cambios</button>
    </form>
</div>
@endsection

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        font-size: 0.8rem;
    }

    .table th, .table td {
        white-space: nowrap;
        padding: 0.5rem;
    }

    .form-control {
        font-size: 0.8rem;
    }

    .status select {
        background-color: transparent;
    }

    .cruce select {
        background-color: transparent;
    }
</style>
@endpush

