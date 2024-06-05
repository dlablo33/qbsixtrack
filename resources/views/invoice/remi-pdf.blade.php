@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header text-center">
<head>
    <title>Factura {{ $factura->Numero_Factura }}</title>
</head>
<body>
    <h1>Factura: {{ $factura->Numero_Factura }}</h1>
    <p>Cliente: {{ $factura->cliente_name }}</p>
    <p>Producto: {{ $factura->producto_name }}</p>
    <p>Fecha de creación: {{ $factura->fecha_create }}</p>
    <p>Fecha de vencimiento: {{ $factura->due_fecha }}</p>
    <p>Cantidad: {{ $factura->cantidad }}</p>
    <p>Total: {{ $factura->total }}</p>
    <p>Bol: {{ $factura->bol }}</p>
    <p>Trailer: {{ $factura->trailer }}</p>
    <a href="" >Descargar PDF</a>
</body>
</html>
</div>
</div>
</div>
</div>
</div>

@endsection