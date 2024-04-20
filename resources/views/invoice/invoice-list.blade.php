@extends('layouts.master') 
 @section('content')
<h1>Invoice</h1>
@if (count($invoices) > 0)
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Invoice ID</th>
        <th>bol</th>
        <th>Trailer</th>
        <th>Servicio</th>
        <th>Customer</th>
        <th>Date</th>
        <th>Amount</th>
        <th></th>  </tr>
    </thead>
    <tbody>
      @foreach ($invoices as $invoice)
        <tr>
        <td>{{ $invoice->NumeroFactura }}</td>
        <td>{{ $invoice->bol }}</td>
        <td>{{ $invoice->Trailer }}</td>
        <td>{{ $invoice->item_names }}</td>
        <td>{{ $invoice->Cliente }}</td> 
        <td>{{ $invoice->last_updated_time }}</td>
        <td>{{ $invoice->total_amt }}</td>  <td>
        <a href="{{ route('invoices.show', $invoice->id) }}">Ver detalles</a>  </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p>No invoices found.</p>
@endif

@endsection
