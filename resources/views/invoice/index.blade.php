@extends('layouts.master')

@section('content')
<div style="display: flex; justify-content: space-between;">
  <h1>Remisiones</h1>
  <div class="download-button-container">
    <form action="{{ route('invoice.create') }}" method="GET">
      @csrf
      <button type="submit" class="btn btn-primary download-button">Crear Remision</button>
    </form>
  </div>
</div>

@if (count($facturas) > 0)
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente ID</th>
        <th>Nombre Cliente</th>
        <th>Nombre Producto</th>
        <th>Producto ID</th>
        <th>Numero de Factura</th>
        <th>Bol</th>
        <th>Trailer</th>
        <th>Cantidad</th>
        <th>Total</th>
        <th>Fecha Creación</th>
        <th>Fecha Vencimiento</th>
        <th></th> </tr>
    </thead>
    <tbody>
      @foreach ($facturas as $factura)
        <tr>
          <td>{{ $factura->id }}</td>
          <td>{{ $factura->cliente_id }}</td>
          <td>{{ $factura->cliente_name }}</td>
          <td>{{ $factura->producto_name }}</td>
          <td>{{ $factura->producto_id }}</td>
          <td>{{ $factura->Numero_Factura }}</td>
          <td>{{ $factura->bol }}</td>
          <td>{{ $factura->trailer }}</td>
          <td>{{ $factura->cantidad }}</td>
          <td>{{ $factura->total }}</td>
          <td>{{ $factura->fecha_create }}</td>
          <td>{{ $factura->due_fecha }}</td>
          <td>
            <td><a href="" class="btn btn-sm btn-info">Ver PDF</a></td>
            
           <td><a href="" class="btn btn-sm btn-info">Enviar PDF</a></td>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p>No Remisiones.</p>
@endif

@endsection