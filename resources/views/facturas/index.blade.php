@extends('layouts.master')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <h1 class="title" style="font-size: 24px; color: #333; font-weight: bold;">Factura Y Remisiones</h1>
  <div class="download-button-container">
    <form action="{{ route('facturas.transferLogisticaToFactura') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-primary custom-btn">Transferir a Factura</button>
    </form>
  </div>
</div>

<!-- Modal -->
@if (count($facturas) > 0)
  <table class="table table-striped custom-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Codigo Facturacion</th>
        <th>Nombre Cliente</th>
        <th>Nombre Producto</th>
        <th>Bol</th>
        <th>Precio</th>
        <th>Trailer</th>
        <th>Estatus</th>
        <th>Cantidad</th>
        <th>Precio Sin IVA</th>
        <th>Total</th>
        <th>Fecha Creación</th>
        <th>Pedimento</th>
        <th>Codigo o Referencia</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($facturas as $factura)
        <tr class="custom-row">
          <td>{{ $factura->id }}</td>
          <td>{{ $factura->Numero_Factura }}</td>
          <td>{{ $factura->cliente_name }}</td>
          <td>{{ $factura->producto_name }}</td>
          <td>{{ $factura->bol }}</td>
          <td>${{ number_format($factura->precio, 2, '.', ',') }}</td>
          <td>{{ $factura->trailer }}</td>
          <td>{{ $factura->estatus }}</td>
          <td>{{ number_format($factura->cantidad, 2, '.', ',') }}</td>
          <td>${{ number_format((($factura->precio - 0.137205) / 1.16), 2, '.', ',') }}</td>
          <td>${{ number_format($factura->total, 2, '.', ',') }}</td>
          <td>{{ $factura->fecha_create }}</td>
          <td>
            {{ $factura->pedimento }}
            @if($factura->pedimento)
              <button class="btn btn-sm btn-success custom-copy-btn" onclick="copyToClipboard('{{ $factura->pedimento }} pipa {{ $factura->trailer }} bol {{ $factura->bol }}')">Copiar</button>
            @endif
          </td>
          <td>{{ $factura->code_factura }}</td>
          <td>
            <!-- Botón Ver PDF (Gris) -->
            <a href="{{ route('facturas.showPdf', ['id' => $factura->id]) }}" class="btn btn-sm btn-secondary custom-pdf-btn">Ver PDF</a>
            <!-- Botón Enlazar Factura (Verde) -->
            @if ($factura->Numero_Factura == null)
              <button type="button" class="btn btn-sm btn-success custom-link-btn" data-toggle="modal" data-target="#linkInvoiceModal-{{ $factura->id }}">Enlazar Factura</button>
            @endif
            <!-- Botón Eliminar (Rojo) -->
            <form action="{{ route('facturas.delete', ['id' => $factura->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta remisión?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger custom-delete-btn">Eliminar</button>
            </form>
          </td>
        </tr>

        <!-- Modal para enlazar la factura -->
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
                <form action="{{ route('facturas.link', ['id' => $factura->id]) }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <label for="invoice_number">Número de Factura</label>
                    <input type="text" name="invoice_number" class="form-control" id="invoice_number" placeholder="Ingrese el número de factura">
                  </div>
                  <button type="submit" class="btn btn-success custom-link-btn">Enlazar</button>
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

<script>
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
      alert('Texto copiado al portapapeles: ' + text);
    }, function() {
      alert('Error al copiar el texto.');
    });
  }
</script>

<style>
  /* Estilos personalizados para los botones */
  .custom-btn {
    border: none;
    color: white;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .custom-pdf-btn {
    background-color: #6c757d; /* Gris */
  }

  .custom-pdf-btn:hover {
    background-color: #5a6268;
    transform: scale(1.05);
  }

  .custom-link-btn {
    background-color: #28a745; /* Verde */
  }

  .custom-link-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
  }

  .custom-delete-btn {
    background-color: #dc3545; /* Rojo */
  }

  .custom-delete-btn:hover {
    background-color: #c82333;
    transform: scale(1.05);
  }

  .custom-copy-btn {
    background-color: #28a745; /* Verde para el botón Copiar */
  }

  .custom-copy-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
  }

  /* Estilos personalizados para la tabla */
  .custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  .custom-table th, .custom-table td {
    padding: 12px;
    text-align: center;
  }

  .custom-table th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: bold;
  }

  .custom-table tr {
    transition: background-color 0.3s ease;
  }

  .custom-table tr:hover {
    background-color: #f1f1f1;
  }

  .custom-row td {
    border-bottom: 1px solid #ddd;
  }

  .custom-row td {
    transition: background-color 0.2s ease;
  }
</style>

@endsection
