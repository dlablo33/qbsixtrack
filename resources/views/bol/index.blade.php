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
                                                    Numero de Factura: {{ $invoice->NumeroFactura }}<br>
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

                                            </td>
                                            <td>

                                            </td>
                                            <td>

                                            </td>
                                            <td>

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
