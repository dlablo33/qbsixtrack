<!-- Dentro de bluewi/not-in-invoice.blade.php -->

@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Invoices no Creados</h1>

        @if ($notInInvoice->isEmpty())
            <p>No se encontraron registros en Bluewi que no estén en Invoice.</p>
        @else
            <ul>
                @foreach ($notInInvoice as $item)
                    <li>{{ $item->bol_number }}</li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('bluewi.index') }}" class="btn btn-primary">Volver a Bluewi</a>
    </div>
@endsection
