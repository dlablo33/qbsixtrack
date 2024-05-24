@extends('layouts.master') 
 @section('content')
 <div style="display: flex; justify-content: space-between;">
    <h1 style="margin: 0;">Invoice</h1>
     <div class="download-button-container">
        <form action="{{ route('invoice.download') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary download-button">Descargar CSV</button>
        </form>
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
        <th>Date</th>
        <th>Amount</th>
        <th> Acciones</th>  </tr>
    </thead>
    <tbody>
      @foreach ($invoices as $invoice)
        <tr>
        <td>{{ $invoice->NumeroFactura }}</td>
        <td>{{ $invoice->bol }}</td>
        <td>{{ $invoice->Trailer }}</td>
        <td>{{ $invoice->item_names }}</td>
        <td>{{ $invoice->last_updated_time }}</td>
        <td>{{ $invoice->total_amt }}</td>
        <td>
         
        <a  href="{{ route('invoices.show', $invoice->NumeroFactura) }}">Ver detalles</a>
        
        </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p>No invoices found.</p>
@endif
@endsection
