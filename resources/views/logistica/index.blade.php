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
                    <th class="width:20%">Estatus</th>
                    <th>Litros</th>
                    <th class="width:24%">Cruce</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logis as $logi)
                    <tr>
                        <td>{{ $logi->bol }}</td>
                        <td>{{ $logi->order_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($logi->fecha)->weekOfYear }}</td>
                        <td>{{ \Carbon\Carbon::parse($logi->fecha)->format('d-m-Y') }}</td>
                        <td>{{ $logi->linea }}</td>
                        <td>{{ $logi->no_pipa }}</td>
                        <!-- Logistica de clientes-->
                        <td>
                            <form action="{{ route('logistica.asignar_cliente') }}" method="POST">
                                @csrf
                                <input type="hidden" name="logistica_id" value="{{ $logi->id }}">
                                <select name="cliente" class="form-control" {{ $logi->cliente ? 'disabled' : '' }} onchange="this.form.submit()">
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ $logi->cliente == $cliente->id ? 'selected' : '' }}>{{ $cliente->NOMBRE_COMERCIAL }}</option>
                                    @endforeach
                                </select>
                        </td>
                        <!-- Logistica de destino -->
                        <td>
                            <select name="destino" class="form-control" {{ $logi->destino_id ? 'disabled' : '' }} {{ strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : '' }}>
                                <option value="">Selecciona un destino</option>
                                @foreach($destinos as $destino)
                                    <option value="{{ $destino->id }}" {{ $logi->destino_id == $destino->id ? 'selected' : '' }}>{{ $destino->nombre }}</option>
                                @endforeach
                                <option value="FOB" {{ $logi->destino == 'FOB' ? 'selected' : '' }}>FOB</option>
                            </select>
                        </td>
                        <!-- Logistica de Transportes-->
                        <td>
                            <select name="transportista" class="form-control" {{ $logi->transportista_id ? 'disabled' : '' }} {{ strpos(optional($logi->cliente)->NOMBRE_COMERCIAL, 'FOB') !== false ? 'disabled' : '' }}>
                                <option value="">Selecciona un transportista</option>
                                @foreach($transportistas as $transportista)
                                    <option value="{{ $transportista->id }}" {{ $logi->transportista_id == $transportista->id ? 'selected' : '' }}>{{ $transportista->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <!-- Logistica de Estatus -->
                        <td>
                            <select name="status" class="form-control">
                                <option value="pendiente" {{ $logi->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="cargada" {{ $logi->status == 'cargada' ? 'selected' : '' }}>Cargada</option>
                                <option value="descargada" {{ $logi->status == 'descargada' ? 'selected' : '' }}>Descargada</option>
                            </select>
                        </td>
                        <!-- Logistica de litros-->
                        <td class="litros" id="litros-{{ $logi->id }}">{{ $logi->litros }}</td>
                        <!-- Logistica de Cruce-->
                        <td id="cruceCell">
                            <select id="cruceSelect" name="cruce" class="form-control">
                                <option value="rojo" {{ $logi->cruce == 'rojo' ? 'selected' : '' }} data-color="red">Rojo</option>
                                <option value="verde" {{ $logi->cruce == 'verde' ? 'selected' : '' }} data-color="green">Verde</option>
                            </select>
                        </td>
                        <!-- Logistica de Precio -->
                        <td>
                            @if ($logi->cliente)
                                <select name="precio" class="form-control precio-select" data-logi-id="{{ $logi->id }}" onchange="this.form.submit()">
                                    <option value="">Selecciona un precio</option>
                                    @foreach ($precios[$logi->id] as $precioId => $precio)
                                        <option value="{{ $precio }}" {{ $logi->precio == $precio ? 'selected' : '' }}>{{ $precio }}</option>
                                    @endforeach
                                </select>
                            @else
                                {{ $logi->precio }}
                            @endif
                        </td>
                        <!-- Total -->
                        <td id="total-{{ $logi->id }}">
                             @if (isset($totales[$logi->id]))
                                 ${{ $totales[$logi->id] !== null ? number_format($totales[$logi->id], 2) : '' }}
                            @endif
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Guardar</button>
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

    .form-control option[data-color="green"] {
        background-color: green;
        color: white;
    }

    .form-control option[data-color="red"] {
        background-color: red;
        color: white;
    }

    td.green {
        background-color: green;
        color: white;
    }

    td.red {
        background-color: red;
        color: white;
    }
</style>
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const precioSelects = document.querySelectorAll('.precio-select');

        function calculateTotal(precioSelect) {
    const logiId = precioSelect.getAttribute('data-logi-id');
    const selectedPrice = parseFloat(precioSelect.value) || 0;
    const litros = parseFloat(document.getElementById(`litros-${logiId}`).innerText) || 0;
    
    console.log('Logi ID:', logiId);
    console.log('Selected Price:', selectedPrice);
    console.log('Litros:', litros);

    const total = selectedPrice * litros;

    console.log('Total:', total);

    if (!isNaN(total) && total > 0) {
        document.getElementById(`total-${logiId}`).innerText = total.toFixed(2);
    } else {
        document.getElementById(`total-${logiId}`).innerText = '';
    }
}


        precioSelects.forEach(select => {
            select.addEventListener('change', function () {
                calculateTotal(select);
            });

            // Initial calculation
            calculateTotal(select);
        });

        const cruceSelects = document.querySelectorAll('#cruceSelect');
        cruceSelects.forEach(select => {
            select.addEventListener('change', function () {
                const cell = select.closest('#cruceCell');
                const selectedOption = select.options[select.selectedIndex];
                const color = selectedOption.getAttribute('data-color');

                cell.classList.remove('green', 'red');
                if (color === 'green') {
                    cell.classList.add('green');
                } else if (color === 'red') {
                    cell.classList.add('red');
                }
            });

            // Initial color setting
            const cell = select.closest('#cruceCell');
            const selectedOption = select.options[select.selectedIndex];
            const color = selectedOption.getAttribute('data-color');
            if (color === 'green') {
                cell.classList.add('green');
            } else if (color === 'red') {
                cell.classList.add('red');
            }
        });
    });
</script>
@endpush

