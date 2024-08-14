@extends('layouts.master')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Agregar Transporte</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transporte.index') }}">Transportes</a></li>
                    <li class="breadcrumb-item active">Agregar Transporte</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
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
                        <form action="{{ route('transporte.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="transportista_id">Transportista</label>
                                <select class="form-control" id="transportista_id" name="transportista_id" required>
                                    <option value="">Seleccione un transportista</option>
                                    @foreach($transportistas as $transportista)
                                        <option value="{{ $transportista->id }}">{{ $transportista->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="destino_id">Destino</label>
                                <select class="form-control" id="destino_id" name="destino_id" required>
                                    <option value="">Seleccione un destino</option>
                                    @foreach($destinos as $destino)
                                        <option value="{{ $destino->id }}">{{ $destino->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tar_usa">Tarifa USA</label>
                                <input type="float" class="form-control" id="tar_usa" name="tar_usa" value="{{ old('tar_usa') }}" >
                            </div>
                            <div class="form-group">
                                <label for="tar_mex">Tarifa México</label>
                                <input type="float" class="form-control" id="tar_mex" name="tar_mex" value="{{ old('tar_mex') }}" >
                            </div>
                            <div class="form-group">
                                <label for="retencion">Retención 4%</label>
                                <input type="float" class="form-control" id="retencion" name="retencion" value="{{ old('retencion') }}" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="moneda">Moneda</label>
                                <select class="form-control" id="moneda" name="moneda" required>
                                    <option value="">Seleccione una moneda</option>
                                    <option value="MXN" {{ old('moneda') == 'MXN' ? 'selected' : '' }}>MXN</option>
                                    <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tc_fijo">TC Fijo</label>
                                <input type="float" class="form-control" id="tc_fijo" name="tc_fijo" value="{{ old('tc_fijo') }}" >
                            </div>
                            <div class="form-group">
                                <label for="iva">Total a Pagar</label>
                                <input type="float" class="form-control" id="iva" name="iva" value="{{ old('iva') }}" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('transporte.index') }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tarUSA = document.getElementById('tar_usa');
        const tarMex = document.getElementById('tar_mex');
        const retencion = document.getElementById('retencion');
        const iva = document.getElementById('iva');

        function calculateValues() {
            let tarifa = parseFloat(tarUSA.value) || parseFloat(tarMex.value) || 0;
            let retencionValue = tarifa * 0.04;
            let totalValue = tarifa * 1.16;

            retencion.value = retencionValue.toFixed(2);
            iva.value = totalValue.toFixed(2);
        }

        tarUSA.addEventListener('input', calculateValues);
        tarMex.addEventListener('input', calculateValues);
    });
</script>

@endsection
