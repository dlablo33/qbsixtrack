@extends('layouts.master') 
 @section('content')
 <div style="display: flex; justify-content: space-between;">
    <h1 style="margin: 0;">Remisiones</h1>
     <div class="download-button-container">
        <form action="{{ route('invoice.create') }}" method="GET">
            @csrf
            <button type="submit" class="btn btn-primary download-button">Crear Remicion</button>
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
            <th>Fecha Creación</th>
            <th>Fecha Vencimiento</th>
            <th>Total</th> </tr>
    </thead>
    <tbody>
    @foreach ($facturas as $factura)
    <tr>
        <td>{{ $factura->id }}</td>
        <td>{{ $factura->cliente_id }}</td>
        <td>{{ $factura->cliente_name }}</td>
        <td>{{ $factura->producto_name }}</td>
        <td>{{ $factura->producto_id }}</td>
        <td>{{ $factura->fecha_create }}</td>
        <td>{{ $factura->due_fecha }}</td>
        <td>{{ $factura->total }}</td>
    </tr>
@endforeach
    </tbody>
  </table>
@else
  <p>No Remiciones.</p>
@endif

@endsection
