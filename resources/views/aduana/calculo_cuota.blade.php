<h1>Resultados de Cálculo de Cuotas</h1>

<p>Tipo de Cambio aplicado: {{ $tipoCambio }}</p>
<p>Cuota Base por BoL: {{ $cuotaBase }}</p>

<table>
    <thead>
        <tr>
            <th>BoL ID</th>
            <th>Total a Pagar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($totales as $id => $total)
            <tr>
                <td>{{ $id }}</td>
                <td>{{ $total ? number_format($total, 2) : 'No disponible' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
