@extends('layouts.master')

@section('content')

 <div class="content-header">
   <div class="container-fluid">
     <div class="row mb-2">
       <div class="col-sm-6">
         <h1 class="m-0">Precios</h1>
       </div><div class="col-sm-6">
         <ol class="breadcrumb float-sm-right">
           <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
           <li class="breadcrumb-item active">Precios</li>
         </ol>
       </div></div></div></div>
 <section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mr-auto">Listado de precios</h3>

            <div class="d-flex">
              <a href="{{ route('marchants.create') }}" class="btn btn-danger mr-2">Añadir precio</a>
              <a href="/settings/products/create/" class="btn btn-info">Añadir Producto</a>
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
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Folio</th>
                  <th>Customer</th>
                  <th>Customer name</th>
                  <th>Product</th>
                  <th>Product name</th>
                  <th>Precio</th>
                  <th>Fecha</th>
                  <th>Historial</th>
                </tr>
              </thead>
              <tbody>
@php
$clientesMostrados = []; // Array para almacenar IDs de clientes mostrados
@endphp

@foreach ($marchants as $marchant)

    @if (!in_array($marchant->cliente_id, $clientesMostrados))
        <tr>
            <td>{{ $marchant->id }}</td>
            <td> {{$marchant->cliente_id}}</td>
            <td>{{$marchant->cliente_name}}</td>
            </td>
            <td>{{ $marchant->producto_id }}</td>
            <td>{{ $marchant->producto_name }}
            </td>
            <td>{{ $marchant->precio}}</td>
            <td>{{ $marchant->updated_at }}</td>
            <td>
                     <a href="{{ route('marchants.show',  $marchant->cliente_id) }}" class="btn btn-success"><i class="nav-icon fas fa-edit"></i></a>
{{--                                            <form action="{{ route('marchants.destroy' ,$marchant->id) }}" method="POST" style="display:inline;">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this merchant?')">Delete</button>--}}
{{--                                            </form>--}}
                    </td>
        </tr>

        @php
            $clientesMostrados[] = $marchant->cliente_id; // Agregar ID del cliente al array
        @endphp
    @endif

@endforeach
              </tbody>
            </table>
          </div>
          </div>
      </div>
      </div>
    </div>
  </section>
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script
  src="https://code.jquery.com/jquery-migrate-3.3.2.min.js"
  integrity="sha256-Ap4KLoCf1rXb52q+i3p0k2vjBsmownyBTE1EqlRiMwA="
  crossorigin="anonymous"></script>
<script>

</script>
@endsection


