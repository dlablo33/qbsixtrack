@extends('layouts.master')

@section('content')
<div style="display: flex; justify-content: space-between;">
  <h1 style="margin: 0;">Petrollium</h1>
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
      <th>Estatus</th>
      <th>Date</th>
      <th>Amount</th>
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
        <td>
          <form action="{{ route('invoice.update.status', $invoice->id) }}" method="POST">
            @csrf
            @if ($invoice->estatus == 'Pendiente')
              <select name="estatus">
                <option value="Pendiente">Pendiente</option>
                <option value="Completado">Completado</option>
              </select>
              <button type="submit">Actualizar</button>
            @else
              {{ $invoice->estatus }}
            @endif
          </form>
        </td>
        <td>{{ $invoice->last_updated_time }}</td>
        <td>{{ $invoice->total_amt }}</td>
        <td>
          <a href="{{ route('invoice.remi', $invoice->NumeroFactura) }}">Crear Remicion</a>
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


