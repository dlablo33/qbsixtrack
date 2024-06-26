@extends('layouts.master')

@section('content')
<div style="display: flex; justify-content: space-between;">
  <h1 class="title">Factura Y Remisiones</h1>
  <div class="download-button-container">
    <form action="{{ route('invoice.create') }}" method="GET">
      @csrf
      <button type="submit" class="btn btn-primary download-button">Añadir nueva</button>
    </form>
  </div>
</div>

<!-- Modal -->
@if (count($facturas) > 0)
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Codigo Facturacion</th>
        <th>Cliente ID</th>
        <th>Nombre Cliente</th>
        <th>Nombre Producto</th>
        <th>Producto ID</th>
        <th>Numero de Invoice</th>
        <th>Bol</th>
        <th>Trailer</th>
        <th>Estatus</th>
        <th>Cantidad</th>
        <th>Total</th>
        <th>Fecha Creación</th>
        <th>Fecha Vencimiento</th>
        <th>Accion</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($facturas as $factura)
        <tr>
          <td>{{ $factura->id }}</td>
          <td>{{ $factura->code_factura }}</td>
          <td>{{ $factura->cliente_id }}</td>
          <td>{{ $factura->cliente_name }}</td>
          <td>{{ $factura->producto_name }}</td>
          <td>{{ $factura->producto_id }}</td>
          <td>{{ $factura->Numero_Factura }}</td>
          <td>{{ $factura->bol }}</td>
          <td>{{ $factura->trailer }}</td>
          <td>{{ $factura->estatus }}</td>
          <td>{{ number_format($factura->cantidad, 2, '.', ',') }}</td>
          <td>${{ number_format(number_format($factura->total, 2, '.', ''), 0, ',', ',') }}</td>
          <td>{{ $factura->fecha_create }}</td>
          <td>{{ $factura->due_fecha }}</td>
          <td>
            <a href="{{ route('invoice.showPdf', ['id' => $factura->id]) }}" class="btn btn-sm btn-info">Ver PDF</a>
            <a href="" class="btn btn-sm btn-info" data-toggle="modal" data-target="#sendEmailModal-{{ $factura->id }}">Enviar PDF</a>
            @if ($factura->code_factura == null)
              <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#linkInvoiceModal-{{ $factura->id }}">Enlazar Factura</button>
            @endif
            <form action="{{ route('invoice.delete', ['id' => $factura->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta remisión?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>

        <!-- Modal -->
        <div class="modal fade" id="linkInvoiceModal-{{ $factura->id }}" tabindex="-1" role="dialog" aria-labelledby="linkInvoiceModalLabel-{{ $factura->id }}" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="linkInvoiceModalLabel-{{ $factura->id }}">Enlazar Factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{ route('invoice.link', ['id' => $factura->id]) }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <label for="invoice_number">Número de Factura</label>
                    <input type="text" name="invoice_number" class="form-control" id="invoice_number" placeholder="Ingrese el número de factura">
                  </div>
                  <button type="submit" class="btn btn-primary">Enlazar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </tbody>
  </table>
@else
  <p>No Remisiones.</p>
@endif

@endsection



