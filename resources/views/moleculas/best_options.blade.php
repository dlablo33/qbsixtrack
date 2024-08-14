@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container mt-4">
    <h3>Mejores Opciones según tu Presupuesto</h3>

    <p><strong>Presupuesto:</strong> ${{ number_format($budget, 2, '.', ',') }}</p>

    @if(isset($bestCombination) && count($bestCombination) > 0)
        <div class="alert alert-info mt-4">
            <strong>Total Calculado:</strong> ${{ number_format($bestTotal, 2, '.', ',') }}
        </div>
        
        <h2 class="mt-4">Mejores Opciones para Pagar</h2>
        <form id="paymentBatchForm" action="{{ route('moleculas.processPaymentBatch') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>BOL</th>
                            <th>Litros</th>
                            <th>Rate</th>
                            <th>Total</th>
                            <th>Fecha de Creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bestCombination as $record)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_records[]" value="{{ $record->id }}">
                                </td>
                                <td>{{ $record->bol_number }}</td>
                                <td>{{ number_format($record->litros, 2, '.', ',') }}</td>
                                <td>${{ number_format($record->rate, 2, '.', ',') }}</td>
                                <td>${{ number_format($record->total, 2, '.', ',') }}</td>
                                <td>{{ $record->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" id="processPaymentButton" class="btn btn-success">Procesar Pago y Descargar PDF</button>
        </form>

        <a href="{{ route('moleculas.molecula1') }}" class="btn btn-primary mt-4">Volver a la Página de Opciones</a>

    @elseif(isset($bestCombination))
        <p>No se encontraron facturas dentro del presupuesto dado.</p>
    @endif
</div>

<script>
document.getElementById('paymentBatchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    var form = document.createElement('form');
    form.method = 'POST';
    form.action = this.action;

    // Agregar el token CSRF
    var csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('input[name="_token"]').value;
    form.appendChild(csrfToken);

    // Agregar los datos del formulario
    var formData = new FormData(this);
    for (var [key, value] of formData.entries()) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();

    // Redirigir después de un breve retraso para asegurar que el formulario se envíe primero
    setTimeout(function() {
        window.location.reload();
    }, 100);
});
</script>