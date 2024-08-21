@extends('layouts.master ')

@section('content')
<div class="container mt-5">
    <h1>Invoices de QuickBooks</h1>

    <a href="{{ route('quickbooks.fetch') }}" class="btn btn-primary mb-3">Migrar Facturas</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha de Transacción</th>
                <th>Número de Documento</th>
                <th>Total</th>
                <th>Saldo</th>
                <th>Estado de Correo</th>
                <th>Nota Privada</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->Id }}</td>
                    <td>{{ $invoice->CustomerRef->value }}</td>
                    <td>{{ $invoice->TxnDate }}</td>
                    <td>{{ $invoice->DocNumber }}</td>
                    <td>{{ $invoice->TotalAmt }}</td>
                    <td>{{ $invoice->Balance }}</td>
                    <td>{{ $invoice->EmailStatus }}</td>
                    <td>{{ $invoice->PrivateNote }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay facturas disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
