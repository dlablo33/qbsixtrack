@extends('layouts.master')

@section('content')
<style>
    /* Estilo para la cabecera de la modal */
    .modal-header {
        background-color: #007bff;
        color: white;
        animation: slideDown 0.5s ease-out;
    }

    /* Estilo para los botones de la modal */
    .modal-footer {
        display: flex;
        justify-content: space-between;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    /* Estilo para las filas alternas de la tabla */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }

    /* Estilo para los encabezados de la tabla */
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        animation: fadeIn 0.5s ease-in-out;
    }

    /* Estilo para las celdas de la tabla */
    .table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    /* Animación para el contenido de la modal */
    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Animación para el contenido de la tabla */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>

<div>
    <h1 class="text-center mb-4">Molecula 1</h1>

    <!-- Total de Facturas Pendientes -->
    <div class="alert alert-info mt-4">
        <strong>Total de Facturas con Estatus Pendiente:</strong> ${{ number_format($totalPendiente, 2, '.', ',') }}
    </div>

    <!-- Botón para abrir la ventana modal -->
    <button type="button" class="btn btn-primary btn-lg mt-2" data-toggle="modal" data-target="#calculateModal">
        Calcular Mejores Opciones
    </button>

    <!-- Ventana modal -->
    <div class="modal fade" id="calculateModal" tabindex="-1" role="dialog" aria-labelledby="calculateModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calculateModalLabel">Calcular Mejores Opciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para introducir el presupuesto -->
                    <form id="calculateForm" action="{{ route('moleculas.calculateBestOptions') }}" method="POST" class="mt-2">
                        @csrf
                        <div class="form-group">
                            <label for="budget">Presupuesto:</label>
                            <input type="number" step="0.01" class="form-control" id="budget" name="budget" placeholder="Introduce el presupuesto" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Calcular</button>
                    </form>
                    <!-- Resultados de las mejores opciones -->
                    <div id="results" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito y error -->
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

    <!-- Botón para migrar los datos -->
    <form action="{{ route('moleculas.migrateLogisticaToMolecula1') }}" method="POST" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-primary btn-lg">Migrar Datos a Molecula 1</button>
    </form>

    @if ($molecula1Records->count() > 0)
        <table id="" class="table table-striped table-hover mt-4">
            <thead>
                <tr>
                    <th>BOL</th>
                    <th>Litros</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Fecha de Creación</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($molecula1Records as $record)
                    <tr>
                        <td>{{ $record->bol_number }}</td>
                        <td>{{ number_format($record->litros, 2, '.', ',') }}</td>
                        <td>${{ number_format($record->rate, 2, '.', ',') }}</td>
                        <td>${{ number_format($record->total, 2, '.', ',') }}</td>
                        <td>{{ $record->created_at }}</td>
                        <td>{{ $record->estatus }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="mt-4">No hay registros en Molecula 1.</p>
    @endif
</div>

<!-- Scripts para manejar el formulario y mostrar los resultados en la modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Asegurar que todos los elementos SVG tengan un width correctamente asignado
    var svgElement = document.querySelector('svg');
    if (svgElement) {
        svgElement.setAttribute('width', '100'); // Asigna el ancho deseado, ajusta según sea necesario
        svgElement.setAttribute('height', '100'); // También asigna una altura si es necesario
    } else {
        console.error('Elemento SVG no encontrado');
    }

    // Asegurarse de que el DOM está cargado antes de ejecutar cualquier código
    $('#calculateForm').on('submit', function(event) {
        event.preventDefault();
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Verificar si el elemento existe antes de establecer el contenido
                let resultsElement = document.getElementById('results');
                if (resultsElement) {
                    resultsElement.innerHTML = data.html;
                } else {
                    console.error('Elemento de resultados no encontrado');
                }
            } else {
                alert('Error al calcular las mejores opciones');
            }
        })
        .catch(error => console.error('Error en la solicitud:', error));
    });

    // Código para inicializar Sparkline
    if (document.getElementById('example1')) {
        new Sparkline(document.getElementById('example1'), {
            // Configuración de Sparkline
        });
    } else {
        console.error('Elemento para Sparkline no encontrado');
    }

    // Inicializar Vector Map si es necesario
    if ($('#vmap').length > 0) {
        // Establecer el tamaño explícito antes de inicializar
        $('#vmap').css({
            'width': '600px', // Ajustar según sea necesario
            'height': '400px' // Ajustar según sea necesario
        });

        // Inicializar el mapa vectorial
        $('#vmap').vectorMap({
            map: 'world_mill',
            backgroundColor: '#ffffff',
            regionStyle: {
                initial: {
                    fill: '#e4ecef'
                }
            }
        });
    } else {
        console.error('Mapa vectorial no encontrado');
    }
});
</script>



<style>
    /* Estilo personalizado para el tamaño de la modal */
    .custom-modal-dialog {
        max-width: 80%;
        margin: 1.75rem auto;
    }

    /* Estilo personalizado para el contenido de la modal */
    .modal-lg .modal-content {
        width: 100%;
    }

    /* Estilo para hover de las filas de la tabla */
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
        animation: fadeIn 0.5s ease-in-out;
    }
</style>
@endsection


