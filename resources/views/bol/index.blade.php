@extends('layouts.master')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Listado BOL</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Facturas Agrupadas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if ($invoices->isEmpty())
                            <div class="alert alert-warning">
                                No se encontraron facturas.
                            </div>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>BOL</th>
                                        <th>Trailer</th>
                                        <th>Molecula 1</th>
                                        <th>Molecula 2</th>
                                        <th>Molecula 3</th>
                                        <th>Cliente</th>
                                        <th>Transporte</th>
                                        <th>Costo de Transporte</th>
                                        <th>Total Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $bol => $groupedInvoices)
                                        <tr>
                                            <td>{{ $bol }}</td>
                                            <td>{{ $groupedInvoices->first()->Trailer }}</td>
                                            <td>
                                                @foreach ($groupedInvoices as $invoice)
                                                    @if ($invoice->item_names == 'PETROLEUM DISTILLATES')
                                                    Numero de Factura:{{ $invoice->NumeroFactura }}<br>
                                                        ${{ $invoice->total_amt }}<br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($groupedInvoices as $invoice)
                                                    @if ($invoice->item_names == 'TRANSPORTATION FEE,SERVICE FEE,WEIGHT CONTROL')
                                                    Numero de Factura:{{ $invoice->NumeroFactura }}<br>
                                                        ${{ $invoice->total_amt }}<br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($groupedInvoices as $invoice)
                                                    @if ($invoice->item_names == 'OPERATION ADJUSTED')
                                                    Numero de Factura:{{ $invoice->NumeroFactura }}<br>
                                                        ${{ $invoice->total_amt }}<br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @php
                                                    $bolDetail = $groupedInvoices->first();
                                                @endphp
                                                @if ($bolDetail->cliente_id != null)
                                                    {{ $bolDetail->cliente->NOMBRE_COMERCIAL }}
                                                @else
                                                    <form action="{{ route('bol.updateCliente', $bol) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="cliente_id" class="form-control">
                                                            <option value="">Seleccione un cliente</option>
                                                            @foreach ($clientes as $cliente)
                                                                <option value="{{ $cliente->id }}">{{ $cliente->NOMBRE_COMERCIAL }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Asignar</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('bol.updateTransporte', $bol) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="transporte_id" class="form-control">
                                                        <option value="">Seleccione un transporte</option>
                                                        @foreach ($transportes as $transporte)
                                                            <option value="{{ $transporte->id }}">{{ $transporte->transportista_nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Asignar</button>
                                                </form>
                                            </td>
                                            <td>
                                                @php
                                                    $transportistaId = $bolDetail->transporte_id;
                                                    $destinoId = $bolDetail->destino_id;
                                                    $tarifa = $transportes->where('transportista_id', $transportistaId)->where('destino_id', $destinoId)->first();
                                                    $totalTransporte = $tarifa ? $tarifa->iva : 0;
                                                @endphp
                                                ${{ $totalTransporte }}
                                            </td>
                                            <td>
                                                @php
                                                    $totalFinal = $groupedInvoices->sum('total_amt') + $totalTransporte;
                                                @endphp
                                                ${{ $totalFinal }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
