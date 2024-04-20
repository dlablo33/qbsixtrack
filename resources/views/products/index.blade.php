@extends('layouts.master')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Products</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Products</li>
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
                    <h3 class="card-title mr-auto">All Products</h3>

                    <div class="d-flex">
                        <a href="/products/syncItems" class="btn btn-danger mr-2">Sync Items</a>
                        <a href="/settings/products/create/" class="btn btn-info">Add Product</a>
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
                    <th>Item</th>
                      <th>Description</th>
                    <th>Type</th>
                    <th>Unit Price</th>

                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($qb_products as $product)

                  <tr>
                    <td width="30%">{{$product->Name}}</td>
                      <td width="30%">{{$product->Description}}</td>
                    <td width="20%">{{$product->Type}}</td>
                    <td width="20%">{{$product->UnitPrice}}</td>

                    <td>

                        <a href="{{ route('products.edit',  $product->id) }}" class="btn btn-success"><i class="nav-icon fas fa-edit"></i></a>

                    </td>
{{--                    <td><a href="{{route('products.destroy',['id'=> $product->id])}}" class="btn btn-danger">Delete</a></td>--}}
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
