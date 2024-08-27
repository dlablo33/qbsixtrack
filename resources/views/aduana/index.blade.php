@extends('layouts.master')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Listado Aduanas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Aduanas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Botón para migrar todos los BoLs -->
<form action="{{ route('aduana.migrateAll') }}" method="POST" class="mb-3">
    @csrf
    <button type="submit" class="btn btn-success">Migrar Todos los BoLs</button>
</form>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if ($aduanas->isEmpty())
                            <div class="alert alert-warning">
                                No se encontraron registros.
                            </div>
                        @else
                            <!-- Formulario para la asignación de agentes aduanales -->
                            <form id="saveAgentsForm" action="{{ route('aduana.saveAllAgents') }}" method="POST">
                                @csrf
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>BoL</th>
                                            <th>Pedimento</th>
                                            <th>Transporte</th>
                                            <th>Numero de Pipa</th>
                                            <th>Seleccionar Agente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aduanas as $aduana)
                                            <tr>
                                                <td>{{ $aduana->bol_number }}</td>
                                                <td>{{ $aduana->pedimento }}</td>
                                                <td>{{ $aduana->linea }}</td>
                                                <td>{{ $aduana->no_pipa }}</td>
                                                <td>
                                                    <select name="agentes[{{ $aduana->id }}]" class="form-control">
                                                        <option value="">Seleccione un agente</option>
                                                        @foreach ($agentes as $agente)
                                                            <option value="{{ $agente->id }}" {{ $aduana->id_agente == $agente->id ? 'selected' : '' }}>
                                                                {{ $agente->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Botón para guardar todos los cambios -->
                                <button type="submit" class="btn btn-primary mt-2">Guardar Todos</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para ingresar el tipo de cambio -->
@if (session('showTipoCambioModal'))
    <div class="modal fade" id="tipoCambioModal" tabindex="-1" aria-labelledby="tipoCambioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tipoCambioModalLabel">Asignar Tipo de Cambio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('aduana.assignTipoCambio') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tipo_de_cambio_global">Tipo de Cambio</label>
                            <input type="number" name="tipo_de_cambio_global" class="form-control" placeholder="Ingresa el tipo de cambio" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Tipo de Cambio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        @if (session('showTipoCambioModal'))
            $('#tipoCambioModal').modal('show');
        @endif
    });
</script>
@endsection



