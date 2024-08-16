@extends('layouts.master')

@section('styles')
    <style>
        /* Importar la fuente empresarial */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        /* Estilos generales */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }

        /* Contenedor principal */
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Título principal */
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 2.2rem;
            position: relative;
            animation: slideInLeft 0.6s ease-out;
        }

        /* Título secundario */
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #28a745;
            font-weight: 500;
            font-size: 1.8rem;
            animation: slideInRight 0.6s ease-out;
        }

        /* Estilo de la tabla */
        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            animation: fadeInUp 0.6s ease-in-out;
        }

        /* Encabezados de la tabla */
        .table thead th {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #0056b3;
            text-align: center;
        }

        /* Filas de la tabla */
        .table tbody tr {
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        /* Hover en las filas de la tabla */
        .table tbody tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
        }

        /* Celdas de la tabla */
        .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }

        /* Estilo de los botones */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-transform: uppercase;
            border-radius: 50px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        /* Hover en los botones */
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            padding: 10px 20px;
            text-transform: uppercase;
            border-radius: 50px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInLeft {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideInRight {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
@endsection

@section('content')
    <div id="example" class="container">
        <h1>Facturas del Cliente: {{ $cliente_name }}</h1>
        <h2>Saldo a Favor: ${{ number_format($saldoAFavor, 2, '.', '') }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Total</th>
                    <th>Abonos</th>
                    <th>Restante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($facturas as $factura)
                <tr>
                    <td>{{ $factura->id }}</td>
                    <td>{{ $factura->producto_name }}</td>
                    <td>$ {{ number_format($factura->total, 2, '.', '') }}</td>
                    <td>
                        @foreach ($factura->pagos as $pago)
                            <p><a>{{ $pago->fecha_pago }}</a>: <a>${{ number_format($pago->monto, 2, '.', '') }}</a></p>
                        @endforeach
                    </td>
                    <td>$ {{ number_format($factura->montoPendiente(), 2, '.', '') }}</td>
                    <td>
                        <form action="{{ route('cuentas.usarSaldo', $factura->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Usar Saldo</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="form-group row mt-3">
            <div class="col-sm-12 text-center">
                <a href="{{ route('cuentas.index')}}" class="btn btn-secondary btn-block">Regresar</a>
            </div>
        </div>
    </div>
@endsection
