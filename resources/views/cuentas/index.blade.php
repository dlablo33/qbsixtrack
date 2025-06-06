@extends('layouts.master')

@section('styles')
    <style>
        /* Estilos personalizados */
        h1 {
            text-align: center;
            color: #0056b3;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 2.5rem;
        }

        .table {
            width: 85%;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s forwards;
        }

        .table th, .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }

        .table thead {
            background-color: #0056b3;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            text-transform: uppercase;
            border-radius: 50px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <h1>Estado de Cuenta de Clientes</h1>

    <a href="{{ route('estado_cuenta.descargar_pdf') }}" class="btn btn-primary">Descargar Estado de Cuenta</a>


    <table id="example1" class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Saldo Restante</th>
                <th>Saldo a Favor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($deudasPorCliente as $cliente)
            <tr>
                <td>{{ $cliente->cliente_name }}</td>
                <td>${{ number_format($cliente->saldoRestante, 2, '.', ',') }}</td>
                <td>${{ number_format($cliente->saldoAFavor, 2, '.', ',') }}</td>
                <td>
                    <a href="{{ route('cuentas.cnc-detalle', ['cliente_name' => $cliente->cliente_name]) }}" class="btn btn-primary">Estado de Cuenta</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#example1').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                }
            });
        });
    </script>
@endsection
