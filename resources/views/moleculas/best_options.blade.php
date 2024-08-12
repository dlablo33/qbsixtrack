<div class="container mt-4">
    <h3>Mejores Opciones según tu Presupuesto</h3>

    <p><strong>Presupuesto:</strong> ${{ number_format($budget, 2, '.', ',') }}</p>

    @if(isset($bestCombination) && count($bestCombination) > 0)
        <div class="alert alert-info mt-4">
            <strong>Total Calculado:</strong> ${{ number_format($bestTotal, 2, '.', ',') }}
        </div>
        
        <h2 class="mt-4">Mejores Opciones para Pagar</h2>
        <form action="{{ route('moleculas.processPaymentBatch') }}" method="POST">
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

            <button type="submit" class="btn btn-success">Procesar Pago y Descargar PDF</button>
        </form>

    @elseif(isset($bestCombination))
        <p>No se encontraron facturas dentro del presupuesto dado.</p>
    @endif
</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        table-layout: fixed;
    }

    th, td {
        word-wrap: break-word;
        text-align: center;
    }

    thead th {
        background-color: #f8f9fa;
    }
</style>

