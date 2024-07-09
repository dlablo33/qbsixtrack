@extends('layouts.master')

@section('content')
<div >
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
    <div class="table-responsive">
        <table class="table">
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
                    <th>Transportista</th>
                    <th >Estatus</th>
                    <th>Litros</th>
                    <th>Cruce</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logis as $logi)
                    <tr>
                        <td>{{ $logi->bol }}</td>
                        <td>{{ $logi->order_number }}</td>
                        <td>{{ $logi->semana }}</td>
                        <td>{{ $logi->fecha }}</td>
                        <td>{{ $logi->linea }}</td>
                        <td>{{ $logi->no_pipa }}</td>
                        <td>
                            <form action="{{ route('logistica.asignar_cliente') }}" method="POST">
                                @csrf
                                <input type="hidden" name="logistica_id" value="{{ $logi->id }}">
                                <select name="cliente" class="form-control" {{ $logi->cliente ? 'disabled' : '' }}>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ $logi->cliente == $cliente->id ? 'selected' : '' }}>{{ $cliente->NOMBRE_COMERCIAL }}</option>
                                    @endforeach
                                </select>
                        </td>
                        <td>
                            <select name="destino" class="form-control" {{ $logi->destino_id ? 'disabled' : '' }} {{ strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : '' }}>
                                <option value="">Selecciona un destino</option>
                                @foreach($destinos as $destino)
                                    <option value="{{ $destino->id }}" {{ $logi->destino_id == $destino->id ? 'selected' : '' }}>{{ $destino->nombre }}</option>
                                @endforeach
                                <option value="FOB" {{ $logi->destino == 'FOB' ? 'selected' : '' }}>FOB</option>
                            </select>
                        </td>
                        <td>
                            <select name="transportista" class="form-control" {{ $logi->transportista_id ? 'disabled' : '' }} {{ strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : '' }}>
                                <option value="">Selecciona un transportista</option>
                                @foreach($transportistas as $transportista)
                                    <option value="{{ $transportista->id }}" {{ $logi->transportista_id == $transportista->id ? 'selected' : '' }}>{{ $transportista->nombre }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <select name="status" class="form-control">
                                <option value="pendiente" {{ $logi->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="cargada" {{ $logi->status == 'cargada' ? 'selected' : '' }}>Cargada</option>
                                <option value="descargada" {{ $logi->status == 'descargada' ? 'selected' : '' }}>Descargada</option>
                            </select>
                        </td>
                        <td>{{ $logi->litros }}</td>
                        <td>
                            <select name="cruce" class="form-control ">
                                <option value="verde" {{ $logi->cruce == 'verde' ? 'selected' : '' }}>Verde</option>
                                <option value="rojo" {{ $logi->cruce == 'rojo' ? 'selected' : '' }}>Rojo</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary mt-2">Guardar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
    }
</style>
@endpush
