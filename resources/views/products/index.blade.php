@extends('layouts.master')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Productos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Productos</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mr-auto">Todos los productos</h3>

                    <div class="d-flex">
                    @if (Auth::user()->tipo_usuario == 1)
                        <a href="/products/syncItems" class="btn btn-danger mr-2">?</a>
                        @endif
                        <a href="/settings/products/create/" class="btn btn-info">Agregar Product</a>
                    </div>
                </div>

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
              <div class="card-body">
              <!-- /.card-header -->
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Clave del Producto</th>
                    <th>Fecha de Creacion</th>
                    

                  <!-- <th>Editar</th> -->
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($products as $product)

                  <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->nombre}}</td>
                    <td>{{$product->clv_producto}}</td>
                    <td>{{$product->created_at}}</td>

 {{--            <a href="{{ route('products.edit',  $product->id) }}" class="btn btn-success"><i class="nav-icon fas fa-edit"></i></a> 

                    </td>
                <td><a href="{{route('products.destroy',['id'=> $product->id])}}" class="btn btn-danger">Delete</a></td>--}}
                  </tr>

                  @endforeach
                  </tbody>
                  <tfoot>

                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
    <script
        src="https://code.jquery.com/jquery-migrate-3.3.2.min.js"
        integrity="sha256-Ap4KLoCf1rXb52q+i3p0k2vjBsmownyBTE1EqlRiMwA="
        crossorigin="anonymous"></script>
    <script>

        $( document ).ready(function() {
            $( "#products" ).change(function() {
                var id = $("#products").val();
                console.log(id);
                $('#createUrl').attr("href", "/settings/products/create/" + id);
            });
        });


    </script>
@endsection