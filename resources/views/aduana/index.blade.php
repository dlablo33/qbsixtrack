@extends('layouts.master')

@section('content')

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

<!-- Fila para los botones -->
<div class="row mb-4">
    <div class="col-md-6 text-left">
        <form action="{{ route('aduana.migrateAll') }}" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" class="btn btn-info">Migrar Todos los BoLs</button>
        </form>
        <form action="{{ route('aduana.listado') }}" method="POST" class="d-inline-block ml-2">
            @csrf
            <button type="submit" class="btn btn-info">Agentes Aduanales</button>
        </form>
    </div>
    <div class="col-md-6 text-right">
        <button id="showFileUpload" class="btn btn-info">Subir Archivo Excel</button>
        <form id="fileUploadForm" action="{{ route('aduana.subir.excel') }}" method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            <div class="mt-3">
                <label for="file">Seleccionar archivo:</label>
                <input type="file" name="file" required>
                <button type="submit"class="btn btn-info">Subir y Asignar Pedimentos</button>
            </div>
        </form>
    </div>
</div>

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

                                <!-- Campo para ingresar el tipo de cambio -->
                                <div class="form-group d-inline-block float-right mb-3">
                                    <label for="tipo_de_cambio_global" class="mr-2">Tipo de Cambio (Obligatorio):</label>
                                    <input type="float" name="tipo_de_cambio_global" class="form-control d-inline-block w-auto" placeholder="Ingresa el tipo de cambio" required>
                                </div>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>BoL</th>
                                            <th>Pedimento</th>
                                            <th>Transporte</th>
                                            <th>Numero de Pipa</th>
                                            <th>Seleccionar Agente</th>
                                            <th>Honorarios (MXN)</th>
                                            <th>Comisión (USD)</th>
                                            <th>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aduanas as $aduana)
                                            <tr>
                                                <td>
                                                @if ($aduana->status !== 'pagado')
                    <input type="checkbox" class="select-row" name="selected_bols[]" value="{{ $aduana->id }}" data-honorarios="{{ $aduana->honorarios }}" data-comision="{{ $aduana->dls }}">
                @else
                    <!-- Mostrar un mensaje o ícono si ya está pagado -->
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @endif
                                                </td>
                                                <td>{{ $aduana->bol_number }}</td>
                                                <td>{{ $aduana->pedimento }}</td>
                                                <td>{{ $aduana->linea }}</td>
                                                <td>{{ $aduana->no_pipa }}</td>
                                                <td>
                                                    <select name="agents[{{ $aduana->id }}]" class="form-control" {{ $aduana->id_agente ? 'disabled' : '' }}>
                                                        <option value="">Seleccione un agente</option>
                                                        @foreach ($agentes as $agente)
                                                            <option value="{{ $agente->id }}" {{ $aduana->id_agente == $agente->id ? 'selected' : '' }}>
                                                                {{ $agente->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{ $aduana->honorarios ?? 'N/A' }}</td>
                                                <td>{{ $aduana->dls ?? 'N/A' }}</td>
                                                <td>{{ $aduana->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Botón para guardar todos los cambios -->
                                <button type="submit" class="btn btn-primary mt-3">Guardar Asignaciones</button>
                            </form>

                            <!-- Mostrar total de honorarios y comisión seleccionados -->
                            <div class="mt-3">
                                <strong>Total Honorarios Seleccionados: </strong><span id="totalSelectedHonorarios">0.00</span> MXN<br>
                                <strong>Total Comisión Seleccionada: </strong><span id="totalSelectedComision">0.00</span> USD
                            </div>

                            <!-- Formulario para procesar el pago de los seleccionados -->
                            <form id="payForm" action="{{ route('aduana.paySelected') }}" method="POST">
                                @csrf
                                <input type="hidden" name="selected_ids" id="selectedIds">
                                <button type="submit" class="btn btn-warning mt-3" id="payButton" disabled>Pagar Seleccionados</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Mostrar formulario de carga de archivo con animación
    document.getElementById('showFileUpload').addEventListener('click', function() {
        const fileForm = document.getElementById('fileUploadForm');
        if (fileForm.style.display === 'none' || fileForm.style.display === '') {
            fileForm.style.display = 'block';
            fileForm.classList.add('fadeIn');
        } else {
            fileForm.style.display = 'none';
        }
    });

    // Seleccionar/Deseleccionar todos los checkboxes
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.select-row');
        let totalHonorarios = 0;
        let totalComision = 0;
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            if (checkbox.checked) {
                totalHonorarios += parseFloat(checkbox.getAttribute('data-honorarios')) || 0;
                totalComision += parseFloat(checkbox.getAttribute('data-comision')) || 0;
            }
        });
        updateTotals(totalHonorarios, totalComision);
    });

    // Actualizar totales cuando se selecciona un checkbox individual
    document.querySelectorAll('.select-row').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let totalHonorarios = 0;
            let totalComision = 0;
            document.querySelectorAll('.select-row:checked').forEach(checkedBox => {
                totalHonorarios += parseFloat(checkedBox.getAttribute('data-honorarios')) || 0;
                totalComision += parseFloat(checkedBox.getAttribute('data-comision')) || 0;
            });
            updateTotals(totalHonorarios, totalComision);
        });
    });

    // Actualizar los totales en la vista
    function updateTotals(totalHonorarios, totalComision) {
        document.getElementById('totalSelectedHonorarios').textContent = totalHonorarios.toFixed(2);
        document.getElementById('totalSelectedComision').textContent = totalComision.toFixed(2);
        document.getElementById('payButton').disabled = totalHonorarios === 0 && totalComision === 0;
        
        // Actualizar los IDs seleccionados para el pago
        let selectedIds = [];
        document.querySelectorAll('.select-row:checked').forEach(checkbox => {
            selectedIds.push(checkbox.value);
        });
        document.getElementById('selectedIds').value = selectedIds.join(',');
    }
</script>

@endsection

