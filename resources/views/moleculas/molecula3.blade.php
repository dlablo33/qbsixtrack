@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Molecula 3 - Pagos</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Botón de migrar BoLs -->
        <form action="{{ route('migrar.bols') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary mb-3">Migrar BoLs</button>
        </form>
        
        <!-- Botón para sincronizar los BOLs con las facturas -->
        <form action="{{ route('sync.bols.factura') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning mb-3">Sincronizar BOLs con Facturas</button>
        </form>

        <!-- Formulario de pago -->
        <form id="pago-form" action="{{ route('pagar.bols') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success mb-3" id="pagar-button">Pagar BoLs</button>

            <h3>Total seleccionado: <span id="total-suma">0.00</span></h3>

            <table id="example1" class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>BoL</th>
                        <th>Numero de Factura</th>
                        <th>Precio Molecula 1</th>
                        <th>Precio Molecula 3</th>
                        <th>Service Fee</th>
                        <th>Transportación Fee</th>
                        <th>Weight Controller</th>
                        <th>Total</th>
                        <th>Empresa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bols as $bol)
                        <tr>
                        <td>
                                @if($bol->status == 'pagado')
                                    <!-- Mostrar palomita si el BoL ya está pagado -->
                                    <i class="fa fa-check" aria-hidden="true" style="color: green;"></i>
                                @else
                                    <!-- Mostrar checkbox si el BoL está pendiente -->
                                    <input type="checkbox" name="bol_ids[]" value="{{ $bol->bol_id }}">
                                @endif
                            </td>
                            <td>{{ $bol->bol_id }}</td>
                            <td>{{ $bol->NumeroFactura }}</td>
                            <td>{{ $bol->precio_molecula1 }}</td>
                            <td>{{ $bol->precio_molecula3 }}</td>
                            <td>{{ number_format($bol->resultado, 2) }}</td>
                            <td>{{ number_format($bol->transportation_fee, 2) }}</td>
                            <td>{{ number_format($bol->weight_controller, 2) }}</td>
                            <td>{{ number_format($bol->total, 2) }}</td>
                            <td>{{ $bol->customer_name }}</td>
                            <td>
                                @if($bol->status === 'pagado')
                                    <span class="badge bg-success">Pagado</span>
                                @else
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>

    <script>
        // Función para actualizar la suma total seleccionada
        function actualizarSumaTotal() {
            let totalSuma = 0;
            document.querySelectorAll('.bol-checkbox:checked').forEach(function(checkbox) {
                totalSuma += parseFloat(checkbox.dataset.total);
            });
            document.getElementById('total-suma').innerText = totalSuma.toFixed(2);
        }

        // Seleccionar todos los checkboxes
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.bol-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            actualizarSumaTotal(); // Actualizar la suma total
        });

        // Actualizar la suma cada vez que se selecciona/deselecciona un checkbox
        document.querySelectorAll('.bol-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', actualizarSumaTotal);
        });

        // Enviar formulario de pago
        document.getElementById('pago-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var form = this;
            var formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            }).then(response => response.blob())
            .then(blob => {
                // Descargar el PDF
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'boLs_pagados.pdf';
                link.click();

                // Refrescar la página después de la descarga
                window.location.reload();
            }).catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
@endsection

