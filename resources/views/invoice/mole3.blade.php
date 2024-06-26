@extends('layouts.master')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Lista de Facturas Molecula 3</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Facturas Molecula 3</li>
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
                        @if ($invoices->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>bol</th>
                                        <th>Trailer</th>
                                        <th>Servicio</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->id }}</td>
                                            <td>{{ $invoice->bol }}</td>
                                            <td>{{ $invoice->Trailer }}</td>
                                            <td>{{ $invoice->item_names }}</td>
                                            <td>{{ $invoice->last_updated_time }}</td>
                                            <td>${{ number_format(number_format($invoice->total_amt, 2, '.', ''), 0, ',', ',') }}</td>
                                            <td>
                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No se encontraron facturas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection