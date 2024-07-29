@if(isset($bestCombination) && count($bestCombination) > 0)
    <div class="alert alert-info mt-4">
        <strong>Total Calculado:</strong> ${{ number_format($bestTotal, 2, '.', ',') }}
    </div>
    
    <h2 class="mt-4">Mejores Opciones para Pagar</h2>
    <table class="table table-striped">
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
            @foreach ($bestCombination as $record)
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
@elseif(isset($bestCombination))
    <p>No se encontraron facturas dentro del presupuesto dado.</p>
@endif
