@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Actualiza el precio</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('marchants.update') }}">
                            @csrf

                            <div class="form-group">
                                <label for="customer_id">Customer:</label>
                                <select name="customer_id" id="customer_id" class="form-control">
                                    @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->CVE_CTE . '|' . $cliente->NOMBRE_COMERCIAL }}">{{ $cliente->NOMBRE_COMERCIAL }}</option>
                                           @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_id">Product:</label>
                                <select name="product_id" id="product_id" class="form-control">
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->clv_producto . '|' . $producto->nombre }}">{{ $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="text" name="price" id="price" class="form-control">
                            </div>

                            

                            <button type="submit" class="btn btn-primary">Actualizacion</button>
                            <a href="{{ route('marchants.index') }}" class="btn btn-primary">Regresar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
