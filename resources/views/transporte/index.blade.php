@extends('layouts.master')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Transportes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Transportes</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex mb-2">
                    <a href="{{ route('transporte.create') }}" class="btn btn-info">Agregar Transporte</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Transportista</th>
                                    <th>Destino</th>
                                    <th>Tarifa USA</th>
                                    <th>Tarifa México</th>
                                    <th>Retención</th>
                                    <th>Moneda</th>
                                    <th>TC Fijo</th>
                                    <th>Total a Pagar</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tarifas as $tarifa)
                                    <tr>
                                        <td>{{ $tarifa->id }}</td>
                                        <td>{{ $tarifa->transportista->nombre }}</td>
                                        <td>{{ $tarifa->destino->nombre }}</td>
                                        <td>{{ $tarifa->tar_usa }}</td>
                                        <td>{{ $tarifa->tar_mex }}</td>
                                        <td>{{ $tarifa->retencion }}</td>
                                        <td>{{ $tarifa->moneda }}</td>
                                        <td>{{ $tarifa->tc_fijo }}</td>
                                        <td>{{ $tarifa->iva }}</td>
                                        <td>
                                        <a href="{{ route('transporte.edit', $tarifa->id) }}" class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </a> 
                                        <form action="{{ route('transporte.destroy', $tarifa->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este transporte?')">
                                            <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
