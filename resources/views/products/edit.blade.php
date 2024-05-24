@extends('layouts.master')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Producto</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Editar Producto</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Editar Producto</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('products.update', $products->id) }}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                <label for="nombre">Nombre del Producto</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $products->nombre }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="clv_producto">Clave del Producto</label>
                                <input type="text" name="clv_producto" class="form-control" value="{{ $products->clv_producto }}" required>
                            </div>
                            
                            <!-- Agrega más campos según sea necesario -->

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Actualizar Producto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
