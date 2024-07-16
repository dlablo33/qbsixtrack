@extends('layouts.master')

@section('content')


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Clientes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                   
                    <div class="d-flex">
                    @if (Auth::user()->tipo_usuario == 1) 
                                <a href="/customers/syncCustomers" class="btn btn-danger mr-2"></a>
                                @endif
                                <a href="/customers/create" class="btn btn-info">Agregar Cliente</a>
                            </div>

                       
                        <div class="card-body">
                            @include('qb-flash-message')
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <table  id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                <th>ID</th>
                                <th>CLIENTE_LP</th>
                                <th>NOMBRE_COMERCIAL</th>
                                <th>STATUS</th>
                                <th>RFC</th>
                                <th>EMPRESA_VENDEDORA</th>
                                <th>CVE_CTE</th>
                                <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->id }}</td>
                                        <td>{{ $customer->CLIENTE_LP }}</td>
                                        <td>{{ $customer->NOMBRE_COMERCIAL }}</td>
                                        <td>{{ $customer->STATUS }}</td>
                                        <td>{{ $customer->RFC }}</td>
                                        <td>{{ $customer->EMPRESA_VENDEDORA }}</td>
                                        <td>{{ $customer->CVE_CTE }}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <a href="{{ route('customers.edit', ['id' => $customer->id]) }}" class="btn btn-success">
                                                <i class="fas fa-edit"></i>
                                            </a>
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

    <!-- /.content -->

@endsection