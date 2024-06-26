@extends('layouts.master')

@section('content')
<div style="display: flex; justify-content: space-between;">
  <h1 style="margin: 0;">Traking</h1>
  <div class="download-button-container">
  </div>
</div>

@if (count($invoices) > 0)
<table class="table table-striped">
  <thead>
    <tr>
      <th>Invoice ID</th>
      <th>bol</th>
      <th>Trailer</th>
      <th>Servicio</th>
      <th>Fecha</th>
      <th>Total</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($invoices as $invoice)
      @if ($invoice->item_names == 'PETROLEUM DISTILLATES')
      <tr>
        <td>{{ $invoice->NumeroFactura }}</td>
        <td>{{ $invoice->bol }}</td>
        <td>{{ $invoice->Trailer }}</td>
        <td>{{ $invoice->item_names }}</td>
        <td>{{ $invoice->last_updated_time }}</td>
        <td>${{ number_format(number_format($invoice->total_amt, 2, '.', ''), 0, ',', ',') }}</td>
        <td>
          <a href="{{ route('invoice.remi', $invoice->NumeroFactura) }}">Crear Factura</a>
        </td>
      </tr>
      @endif
    @endforeach
  </tbody>
</table>
@else
<p>No invoices found.</p>
@endif

@if (session('status'))
  <div class="alert alert-success" role="alert">
    {{ session('status') }}
  </div>
@endif

@endsection