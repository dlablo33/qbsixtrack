@extends('layouts.master')

@section('content')


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Cliente</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Cliente</a></li>
                        <li class="breadcrumb-item active">Editar Cliente</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-info">
                        <div class="card-header text-center">
                            <h3 class="card-title">Editar Cliente</h3>
                        </div>

                        <form class="card-body" style="margin: 10px" action="{{ route('customers.update', ['id' => $customer->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('qb-flash-message')

                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Cliente</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="clp" id="clp" placeholder="" value="{{ $customer->CLIENTE_LP }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Nombre Comercial</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nc" id="nc" placeholder="" value="{{ $customer->NOMBRE_COMERCIAL }}">
                                </div>
                            </div>
                            <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                            <div class="form-group row">
                                <label for="STATUS" class="col-sm-3 col-form-label">Status</label>
                                  <div class="col-sm-9">
                                       <div class="custom-control custom-switch">
                                 <input type="checkbox" class="custom-control-input" id="STATUS" name="STATUS" {{ $customer->STATUS == 'Activo' ? 'checked' : '' }}>
                                     <label class="custom-control-label" for="STATUS">Activo</label>
                                 </div>
                             </div>
                        </div>
                            <!--//////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
                            <div class="form-group row">
                                <label for="address" class="col-sm-3 col-form-label">RFC</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="rfc" id="rfc" placeholder="" value="{{ $customer->RFC }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country" class="col-sm-3 col-form-label">Razon Social</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="rs" id="rs" placeholder="" value="{{ $customer->RAZON_SOCIAL }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="city" class="col-sm-3 col-form-label">Empresa Vendedora</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="ev" id="ev" placeholder="" value="{{ $customer->EMPRESA_VENDEDORA }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Correo Electronico</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="" value="{{ $customer->email }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="state" class="col-sm-3 col-form-label">Codigo Cuenta Contable</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="ccc" id="ccc" placeholder="" value="{{ $customer->CODIGO_CUENTA_CONTABLE }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="zip" class="col-sm-3 col-form-label">Codigo Cliente Comercial</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cco" id="cco" placeholder="" value="{{ $customer->CODIGO_CLIENTE_COMERCIAL }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="zip" class="col-sm-3 col-form-label">Denominacion Serial</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="ds" id="ds" placeholder=" " value="{{ $customer->DENOMINACION_SERIAL }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-info">Actualizar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection
